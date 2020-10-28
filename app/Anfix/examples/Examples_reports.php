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
*  Date: 26/4/16 16:53
*  @author anfix <info@anfix.com>
*  @copyright  anfix
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

/**
 * Catálogo de ejemplos de uso de los endpoints de algunas utilidades de la API de anfix
 * Antes de ejecutar ninguno de estos ejemplso asegúrese que dispone de un token y key válidos previamente generados
 * según se especifica en la QuickStart de Readme.md
 * Puede ejecutar este fichero como un test general
 */

include 'example_utils.php';

$companyId = firstCompanyId(); //Obtención del id de la primera empresa disponible (función únicamente válida para ejemplos)

$invoice = App\Anfix\IssuedInvoice::first([],$companyId);
print_result('Documento temporal creado', $eInvoice);
//1) Generación de un preview de un presupuesto
    $preview = App\Anfix\CustomerBudget::preview(['CustomerBudgetSerialNum' => 'P2019', 'CustomerBudgetTemplateId' => '65', 'CustomerBudgetTemplateLanguage' => 1],$companyId);
    print_result('Documento temporal creado',$preview);

//2) Generación de un preview de una factura emitida
    $preview = App\Anfix\IssuedInvoice::preview(['IssuedInvoiceSerialNum' => 'F2019', 'IssuedInvoiceTemplateId' => '5Y', 'IssuedInvoiceTemplateLanguage' => 1],$companyId);
    print_result('Documento temporal creado', $preview);

//3) Listado del Plan Contable
    $accountingAccountPlan = App\Anfix\CompanyAccountingAccount::where(['CompanyAccountingAccountLevelInclude' => [1, 2, 3, 4, 5, 6]], $companyId)->accountPrint(['AccountingPeriodYear' => 2019, 'HierarchyLevel' => true, 'ReportFormat' => 'PDF', 'ShowAccountsWithoutEntries' => true, 'ShowBalance' =>true], [],null ,null ,['CompanyAccountingAccountNumber'],['ASC']);
    print_result('Plan Contable', $accountingAccountPlan);

//4) Libro Mayor
    $ledger = App\Anfix\Ledger::report(['AccountingEntryInitDate' => '01/01/2019', 'AccountingEntryEndDate' => '31/12/2019', 'AccountingPeriodYear' => 2019, 'CompanyAccountingInitNumber' => 1000000, 'CompanyAccountingEndNumber' => 9999999, 'FlagDisplayNullItems' => false, 'FlagIncludeAccountWithNoActivity' => false, 'FlagIncludeAccumulatedBalance' => false, 'FlagIncludeClosureEntries' => true, 'FlagIncludeDottedEntries' => true, 'FlagIncludeInternalEntryNumber' => true, 'FlagIncludeNotDottedEntries' => true, 'FlagIncludeOpeningEntries' => false, 'FlagIncludePageBreak' => false], $companyId);
    print_result('Libro mayor', $ledger);

    $ledger = App\Anfix\Ledger::report(['LedgerFormat' => 'PDF', 'AccountingEntryInitDate' => '01/01/2019', 'AccountingEntryEndDate' => '31/12/2019', 'AccountingPeriodYear' => 2019, 'CompanyAccountingInitNumber' => 1000000, 'CompanyAccountingEndNumber' => 9999999, 'FlagDisplayNullItems' => false, 'FlagIncludeAccountWithNoActivity' => false, 'FlagIncludeAccumulatedBalance' => false, 'FlagIncludeClosureEntries' => true, 'FlagIncludeDottedEntries' => true, 'FlagIncludeInternalEntryNumber' => true, 'FlagIncludeNotDottedEntries' => true, 'FlagIncludeOpeningEntries' => false, 'FlagIncludePageBreak' => false], $companyId);
    print_result('Libro mayor', $ledger);

//5) Libro Diario
    $journal = App\Anfix\Journal::report(['AccountingEntryInitDate' => '01/01/2019', 'AccountingEntryEndDate' => '31/12/2019', 'AccountingPeriodYear' => 2019, 'FlagIncludeAccountDescription' => true, 'JournalTypeId' => '1'], $companyId);
    print_result('Libro diario', $journal);

    $journal = App\Anfix\Journal::report(['JournalFormat' => 'PDF', 'AccountingEntryInitDate' => '01/01/2019', 'AccountingEntryEndDate' => '31/12/2019', 'AccountingPeriodYear' => 2019, 'FlagIncludeAccountDescription' => true, 'JournalTypeId' => '1'], $companyId);
    print_result('Libro diario', $journal);

//6) Sumas y Saldos
    $trialBalance = App\Anfix\TrialBalance::report(['AccountingEntryInitDate' => '01/01/2019', 'AccountingEntryEndDate' => '31/12/2019', 'AccountingPeriodYear' => 2019, 'CompanyAccountingInitNumber' => 1000000, 'CompanyAccountingEndNumber' => 9999999, 'FlagDisplayNullItems' => false, 'FlagIncludeClosureEntries' => false, 'FlagIncludeOpeningEntries' => true, 'HierarchyLevel' => true, 'CompanyAccountingAccountLevelInclude' => ['1', '2', '3', '4', '5', '6']], $companyId);
    print_result('Sumas y Saldos', $trialBalance);

    $trialBalance = App\Anfix\TrialBalance::report(['TrialBalanceFormat' => 'PDF','AccountingEntryInitDate' => '01/01/2019', 'AccountingEntryEndDate' => '31/12/2019', 'AccountingPeriodYear' => 2019, 'CompanyAccountingInitNumber' => 1000000, 'CompanyAccountingEndNumber' => 9999999, 'FlagDisplayNullItems' => false, 'FlagIncludeClosureEntries' => false, 'FlagIncludeOpeningEntries' => true, 'HierarchyLevel' => true, 'CompanyAccountingAccountLevelInclude' => ['1', '2', '3', '4', '5', '6']], $companyId);
    print_result('Sumas y Saldos', $trialBalance);

//7) Balance
    $balance = App\Anfix\Sheet::balance(['AccountingEntryInitMonth' => 1, 'AccountingEntryEndMonth' => 12, 'AccountingPeriodYear' => 2019, 'FlagDisplayNullItems' => false, 'SheetFormat' => 'SHEET_PDF', 'TemplateId' => '8'], $companyId);
    print_result('Balance', $balance);

//8) PyG
    $profitLost = App\Anfix\Sheet::balance(['AccountingEntryInitMonth' => 1, 'AccountingEntryEndMonth' => 12, 'AccountingPeriodYear' => 2019, 'FlagDisplayNullItems' => false, 'SheetFormat' => 'SHEET_PDF', 'TemplateId' => 'n'], $companyId);
    print_result('PyG', $profitLost);

//9) efactura
    $invoice = App\Anfix\IssuedInvoice::first([],$companyId);
    $eInvoice = $invoice->generateEInvoice('facturae32',true);
    print_result('Documento temporal creado', $eInvoice);
