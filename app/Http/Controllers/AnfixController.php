<?php

namespace App\Http\Controllers;

use App\Anfix\Anfix;
use App\Anfix\Entities\Company;
use App\Anfix\Entities\CompanyAccountingAccount;
use App\Anfix\Entities\CompanyAccountingEntryReference;
use App\Anfix\Entities\CompanyAccountingPeriod;
use App\Anfix\Exceptions\AnfixException;
use App\Anfix\Exceptions\AnfixResponseException;
use App\Enterprise;
use App\Jobs\AnfixEntry;
use App\Order;
use App\OrderReturn;
use App\PointOfSale;
use App\Ticket;
use DB;
use Illuminate\Support\Facades\Log;

class AnfixController extends Controller
{
    public function __construct()
    {
        Anfix::$debug = true;
    }

    /**
     * Comprueba y crea el plan/ejercicio contable correspondiente a una entrada
     *
     * @param $companyId
     * @param Ticket $ticket
     */
    public static function checkAccountingPeriod($companyId, Ticket $ticket): void
    {
        // Creación de plan contable y ejercicio contable
        $accountingPeriodYear = CompanyAccountingPeriod::where(['AccountingPeriodYear' => $ticket->updatedAt->year], $companyId)->get();
        if (count($accountingPeriodYear) === 0) {
            CompanyAccountingPeriod::createwithplan([
                'AccountingPeriodYear' => $ticket->updatedAt->year,
                'CompanyAccountingPeriodInitDate' => $ticket->updatedAt->startOfYear(),
                'CompanyAccountingPeriodEndDate' => $ticket->updatedAt->endOfYear(),
                'CompanyAccountingPlanId' => '1'],
                $companyId);
            // self::printResult('Ejercicio creado', $accountingPeriodYear);
        }
        // self::printResult('Ya existe el ejercicio', $accountingPeriodYear);
    }

    /**
     * Comprueba si la cuenta contable existe para el modelo y si no la crea
     *
     * @param $companyId
     * @param $modelToCheck
     * @param Ticket $ticket
     * @return mixed
     * @throws AnfixException
     * @throws AnfixResponseException
     */
    public static function checkCompanyAccountingNumber($companyId, $modelToCheck, Ticket $ticket)
    {
        $companyAccountingAccountNumber = $modelToCheck->anfixCompanyAccountingAccountNumber;

        if ($modelToCheck->anfixCompanyAccountingAccountNumber) {
            $accountingAccount = CompanyAccountingAccount::select($ticket->updatedAt->year, $companyAccountingAccountNumber, $companyId);
        }
        //  No existe la cuenta, la creamos
        if (!isset($accountingAccount)) {
            if ($modelToCheck instanceof Enterprise) {
                $maxNumber = DB::table('Enterprise')->max('anfixCompanyAccountingAccountNumber');
                $maxNumber ? $number = $maxNumber + 1 : $number = 5525001;
                $description = $modelToCheck->name;
            } else {
                $maxNumber = DB::table('PointOfSale')->max('anfixCompanyAccountingAccountNumber');
                $maxNumber ? $number = $maxNumber + 1 : $number = 5700001;
                $description = $modelToCheck->name;
            }
            $accountingAccount = CompanyAccountingAccount::select($ticket->updatedAt->year, $number, $companyId);
            if (!$accountingAccount) {
                $accountingAccount = CompanyAccountingAccount::create([
                    'AccountingPeriodYear' => $ticket->updatedAt->year,
                    'CompanyAccountingAccount' => array((object) ['Action' => 'ADD',
                        'CompanyAccountingAccountDescription' => $description,
                        'CompanyAccountingAccountNumber' => $number
                    ])
                ], $companyId);
            }
            $modelToCheck->anfixCompanyAccountingAccountNumber = $number;
            $modelToCheck->saveWithoutEvents();
        }
        return $accountingAccount;
    }

    /*
       Tipos de asiento contable (AccountingEntryTypeId):
       1	Apunte
       2	Factura emitida
       3	Rectificativa - Factura emitida
       4	Factura recibida
       5	Rectificativa - Factura recibida
       6	Bien de inversión - Compra
       7	Rectificativa - Bien de inversión - Compra
       8	Provisión de fondos
       9	Asiento de Regularización
       a	Asiento de Cierre
       b	Asiento de Apertura
       c	Cobro
       d	Pago
       Tipo de apunte contable (AccountingEntryNoteTypeId):
        1	Apunte
        2	Apunte de IVA
        3	Apunte de Recargo de equivalencia
        4	Apunte de Retención (IRPF)
        5	Apunte de contrapartida de cobro/pago
        6	Nómina
        7	Retención por rendimientos de trabajo
        */
    /**
     * @param Order $order
     * @param Ticket $ticket
     * @throws AnfixException
     * @throws AnfixResponseException
     */
    public static function createEntry(Order $order, Ticket $ticket): void
    {
        Log::info('Creando apunte contable en Anfix');
        $companyId = self::firstCompanyId();
        self::checkAccountingPeriod($companyId, $ticket);

        $pointOfSale = $order->pointOfSale;
        /** @var Enterprise $enterprise */
        $enterprise = Enterprise::leftJoin('Enterprisable', 'Enterprise.id', '=', 'Enterprisable.enterpriseId')
            ->where('Enterprisable.enterprisableId', $order->pointOfSaleId)
            ->where('Enterprisable.enterprisableType', PointOfSale::class)
            ->first();

        // Cuenta DEBE (promotor)
        $companyAccountingAccount = self::checkCompanyAccountingNumber($companyId, $enterprise, $ticket);
        // Si ha sido por TPV, a la cuenta puente, si no a la cuenta del PV (caja) HABER
        $debitCompanyAccountingAccount = $order->via === 'assisted' ? self::checkCompanyAccountingNumber($companyId, $pointOfSale, $ticket) : 5550002;

        // dd($companyAccountingAccount, $debitCompanyAccountingAccount);
        $entryReference = CompanyAccountingEntryReference::create([
            "AccountingEntryTypeId" => "1",
            "PredefinedAccountingEntryId" => "1",
            "AccountingEntryDate" => $ticket->updatedAt->format('d/m/Y'),
            "FlagCapitalAssets" => false, // true o false indicando si se trata de un bien de inversión o no, respectivamente
            "AccountingEntryPredefinedEntryId" => "1",
            "Action" => "ADD",
            "AccountingPeriodYear" => $ticket->updatedAt->year,
            "CompanyAccountingEntryNote" => array([
                "AccountingEntryNoteTypeId" => "1",
                "AccountingEntryConcept" => "Entrada",
                "CompanyAccountingAccountNumber" => $companyAccountingAccount->CompanyAccountingAccountNumber,
                "AccountingEntryTypeId" => "1",
                "PredefinedAccountingEntryId" => "b",
                "AccountingEntryAmountDebit" => (float) $ticket->amountWithDiscount,
                "AccountingEntryDocumentDescription" => $ticket->locator,
                "Action" => "ADD",
                "AccountingEntryIsDebitAmount" => true, //true o false indicando si la cantidad anotada corresponde al debe o al haber, respectivamente
                "AccountingEntryAmount" => (float) $ticket->amountWithDiscount,
                "AccountingEntryNoteAmountExpression" => "?"
            ], [
                "AccountingEntryNoteTypeId" => "1",
                "AccountingEntryConcept" => "Entrada",
                "CompanyAccountingAccountNumber" => $debitCompanyAccountingAccount instanceof CompanyAccountingAccount
                    ? $debitCompanyAccountingAccount->CompanyAccountingAccountNumber
                    : $debitCompanyAccountingAccount,
                "AccountingEntryTypeId" => "1",
                "AccountingEntryAmountCredit" => (float) $ticket->amountWithDiscount,
                "IdTemporal" => 1,
                "Action" => "ADD",
                "AccountingEntryIsDebitAmount" => false,
                "AccountingEntryAmount" => (float) $ticket->amountWithDiscount,
                "AccountingEntryNoteAmountExpression" => "?"
            ],),
        ], $companyId);

        // Actualizo el apunte contable de la entrada
        $ticket->anfixAccountingEntryId = $entryReference->AccountingEntryId;
        $ticket->saveWithoutEvents();

        Log::info('Apunte Anfix creado: ' . $ticket->anfixAccountingEntryId);
    }

    /**
     * @param OrderReturn $orderReturn
     * @throws AnfixException
     * @throws AnfixResponseException
     */
    public static function createReturn(OrderReturn $orderReturn): void
    {
        Log::info('Creando devolución contable en Anfix');
        $companyId = self::firstCompanyId();

        $pointOfSale = $orderReturn->pointOfSale;
        /** @var Enterprise $enterprise */
        $enterprise = Enterprise::leftJoin('Enterprisable', 'Enterprise.id', '=', 'Enterprisable.enterpriseId')
            ->where('Enterprisable.enterprisableId', $orderReturn->pointOfSaleId)
            ->where('Enterprisable.enterprisableType', PointOfSale::class)
            ->first();

        foreach ($orderReturn->tickets as $ticket) {
            self::checkAccountingPeriod($companyId, $ticket);
            // Cuenta HABER (promotor) AL contrario que el apunte
            $debitCompanyAccountingAccount = self::checkCompanyAccountingNumber($companyId, $enterprise, $ticket);
            // Si ha sido por TPV, a la cuenta puente, si no a la cuenta del PV (caja) DEBE
            $companyAccountingAccount = $orderReturn->redsysNumber === 'assisted' ? self::checkCompanyAccountingNumber($companyId, $pointOfSale, $ticket) : 5550002;

            $entryReference = CompanyAccountingEntryReference::create([
                "AccountingEntryTypeId" => "1",
                "PredefinedAccountingEntryId" => "1",
                "AccountingEntryDate" => $ticket->updatedAt->format('d/m/Y'),
                "FlagCapitalAssets" => false, // true o false indicando si se trata de un bien de inversión o no, respectivamente
                "AccountingEntryPredefinedEntryId" => "1",
                "Action" => "ADD",
                "AccountingPeriodYear" => $ticket->updatedAt->year,
                "CompanyAccountingEntryNote" => array([
                    "AccountingEntryNoteTypeId" => "1",
                    "AccountingEntryConcept" => "Entrada",
                    "CompanyAccountingAccountNumber" => $companyAccountingAccount->CompanyAccountingAccountNumber,
                    "AccountingEntryTypeId" => "1",
                    "PredefinedAccountingEntryId" => "b",
                    "AccountingEntryAmountDebit" => (float) $ticket->amountWithDiscount,
                    "AccountingEntryDocumentDescription" => $ticket->locator,
                    "Action" => "ADD",
                    "AccountingEntryIsDebitAmount" => true, //true o false indicando si la cantidad anotada corresponde al debe o al haber, respectivamente
                    "AccountingEntryAmount" => (float) $ticket->amountWithDiscount,
                    "AccountingEntryNoteAmountExpression" => "?"
                ], [
                    "AccountingEntryNoteTypeId" => "1",
                    "AccountingEntryConcept" => "Entrada",
                    "CompanyAccountingAccountNumber" => $debitCompanyAccountingAccount instanceof CompanyAccountingAccount
                        ? $debitCompanyAccountingAccount->CompanyAccountingAccountNumber
                        : $debitCompanyAccountingAccount,
                    "AccountingEntryTypeId" => "1",
                    "AccountingEntryAmountCredit" => (float) $ticket->amountWithDiscount,
                    "IdTemporal" => 1,
                    "Action" => "ADD",
                    "AccountingEntryIsDebitAmount" => false,
                    "AccountingEntryAmount" => (float) $ticket->amountWithDiscount,
                    "AccountingEntryNoteAmountExpression" => "?"
                ],),
            ], $companyId);

            // Actualizo el apunte contable (devolución) de la entrada
            $ticket->returnAnfixAccountingEntryId = $entryReference->AccountingEntryId;
            $ticket->saveWithoutEvents();

            Log::info('Devolución Anfix creada: ' . $ticket->returnAnfixAccountingEntryId);
        }
    }

    /**
     * @throws AnfixException
     * @throws AnfixResponseException
     */
    public function test(): void
    {
        // $companyId = self::firstCompanyId(); //Obtención del id de la primera empresa disponible (función únicamente válida para ejemplos)
        // self::printResult('Primera empresa ', $companyId);
        /** @var Ticket $ticket */
        $ticket = Ticket::with('order')
            ->whereNull('anfixAccountingEntryId')
            ->where('amountWithDiscount', '>', 0)
            ->first();
        if ($ticket) {
            // self::createEntry($ticket->order, $ticket);
            AnfixEntry::dispatch($ticket)->onQueue('anfix');
        }
        self::printResult('Asiento de prueba creado', $ticket->anfixAccountingEntryId);
    }

    /**
     * Función auxiliar para imprimir contenido en la pantalla
     * @param $title
     * @param $value
     * @param false $die
     */
    private static function printResult($title, $value, $die = false)
    {
        echo "<div style='color: #6d6d6d; background-color: #eee; font-family: Arial; padding: 5px 0 5px 10px; margin: 5px 0 15px;'>
	<b style='color:#007acc;'>$title</b><pre>" . print_r($value, true) . '</pre></div>';

        if ($die) {
            die();
        }
    }

    /**
     * Devuelve el companyId de la primera empresa disponible
     */
    private static function firstCompanyId()
    {
        static $companyId;
        if (empty($companyId)) {
            $company = Company::firstOrFail([]);
            $companyId = $company->CompanyId;
        }
        return $companyId;
    }
}
