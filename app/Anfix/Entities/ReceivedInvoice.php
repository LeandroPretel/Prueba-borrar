<?php

/*
* 2006-2015 Lucid Networks
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
*
* DISCLAIMER
*
*  Date: 9/2/16 18:57
*  @author Lucid Networks <info@lucidnetworks.es>
*  @copyright  2006-2015 Lucid Networks
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

namespace App\Anfix\Entities;

use App\Anfix\Exceptions\AnfixException;
use App\Anfix\Exceptions\AnfixResponseException;

class ReceivedInvoice extends BaseModel
{
    protected $applicationId = 'e';
    protected $apiUrlSufix = 'expense/receivedinvoice/';
    protected $update = true;
    protected $create = true;
    protected $delete = true;

    /**
     * Duplicación de una factura recibida.
     * @param string $receivedInvoiceIds Identificadores únicos de las facturas recibidas a duplicar
     * @param string $companyId
     * @return Object
     */
    public static function duplicate($receivedInvoiceIds,$companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send(['ReceivedInvoiceIds' => $receivedInvoiceIds],$companyId,'duplicate');
        return $result->outputData->{$obj->Model};
    }

    /**
     * Crea un cobro de una factura
     * @param string $date
     * @param float $amount
     * @throws AnfixException
     * @throws AnfixResponseException
     * @return Payment
     */
    public function createPayment($date, $amount, $accountId, $accountTypeId){
        $formapago = $this->IssuedInvoicePayChargeMethodId == 'c' ? 'Domiciliación' : 'Tarjeta';
        return Payment::create([
            'PaymentSourceId' => $this->{$this->primaryKey},
            'PaymentSourceType' => '1', //2 Fra recibida
            'PaymentDate' => $date,
            'PaymentDescription' => 'Pago de la factura '.$this->ReceivedInvoiceRefNumber,
            'PaymentAmount' => $amount > 0 ? $amount : $amount * -1,
            'PaymentComments' => 'Pago mediante '.$formapago,
            'PaymentIsRefund' => $amount < 0,
            'PaymentAccountId' => $accountId,
            'PaymentAccountTypeId' => $accountTypeId
        ], $this->companyId);
    }

    /**
     * Informe de IVA soportado
     * @param array $params Parámetros para el reporte, AccountingPeriodYear es obligatorio
     * @param string $companyId Identificador de la empresa
     * @return Object
     */
     public static function reportReceived($params,$companyId){
        $obj = new static([],false,$companyId);
        $urlBase = str_replace('/cm/','/',$obj->apiBaseUrl);
        $urlBase = str_replace('/expense/','/',$obj->apiBaseUrl);
        $result = self::_send($params,$companyId,'report/receivedinvoice',[],[],$urlBase);
        if(!empty($result->outputData->{$obj->Model}))
            return $result->outputData->{$obj->Model};
        return $result->outputData;
    }
}
