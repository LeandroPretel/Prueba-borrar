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

//1) Obtención de sugerencia de vencimientos
    $expirations = App\Anfix\Expiration::compute(['ExpirationsQuantity' => 1000.23, 'ExpirationsStartDate' => '01/01/2019'],$companyId);
	print_result('Sugerencia de vencimientos',array_map(function($e){ return array(
		"ExpirationDate" => $e->ExpirationDate,
	    "ExpirationQuantity" => $e->ExpirationQuantity,
	    "ExpirationPercentage" => $e->ExpirationPercentage
	); },$expirations));

//2) Obtención del siguiente número de documento (factura emitida)
    $nextNumberFromSerial = App\Anfix\SerialAndNumber::getnextserialandnumber(['EntityTypeId'=> '5'],$companyId);
		print_result('Siguiente número de Factura',$nextNumberFromSerial);

//3) Obtención de sugerencia de esquema de asiento en base a un predefinido y una cuenta
	$completePredefined = App\Anfix\CompanyAccountingEntryReference::completepredefined(['AccountingPeriodYear' => 2019, 'AccountingEntryPredefinedEntryId' => 'P', 'CompanyAccountingAccountNumber' => 4300000], $companyId);
    print_result('Sugerencia de asiento en base al predefinido',$completePredefined);

//4) Transferir movimientos de una cuenta a otra
	$accountingEntryNotesTransferred = App\Anfix\CompanyAccountingEntryReference::transfer(['AccountingPeriodYear' => 2019, 'SourceCompanyAccountingAccountNumber' => 4300004, 'DestinationCompanyAccountingAccountNumber' => 43000000, 'CompanyAccountingEntryReferenceNote' => [array('AccountingEntryId' => 1, 'AccountingEntryNoteId' => 1, 'CompanyAccountingAccountNumber' => 430000)]], $companyId);
    print_result('Movimientos transferidos de cuenta',$accountingEntryNotesTransferred);

//5) Cerrar ejercicio
//    $accountingPeriodYearClosed = Anfix\CompanyAccountingPeriod::close(['AccountingPeriodYear' => 2019, 'CompanyAccountingPeriodRegularizationEntryDate' => '31/12/2017', 'CompanyAccountingPeriodOpenEntryDate' => '01/01/2018', 'CompanyAccountingPeriodRegularizationEntryDate' => '31/12/2017'], $companyId);
//    print_result('Ejercicio cerrado',$accountingPeriodYearClosed);

//6) Crear un ejercicio fiscal a partir de un plan contable determinado
//	$accountingPeriodYear = Anfix\CompanyAccountingPeriod::createwithplan(['AccountingPeriodYear' => 2011,'CompanyAccountingPeriodInitDate' => '01/01/2019', 'CompanyAccountingPeriodEndDate' => '31/12/2019', 'CompanyAccountingPlanId' => '1'], $companyId);
//    print_result('Ejercicio creado',$accountingPeriodYear);

//7) Bloquear/Desbloquear un ejercicio
//	$accountingPeriodYear = Anfix\CompanyAccountingPeriod::lockunlock(['AccountingPeriodYear' => 2019,'CompanyAccountingPeriodLocked' => false], $companyId);
//    print_result('Ejercicio bloqueado',$accountingPeriodYear);

//8) Recalcular saldos de un ejercicio contable
	$accountingPeriodYear = App\Anfix\CompanyAccountingPeriod::regeneratebalance(2019, $companyId);
    print_result('Actualización de saldos',$accountingPeriodYear);

//9) Obtención de la plantilla base de parametrización de una empresa
	$parametrizationBaseTemplate = App\Anfix\CompanyParameter::parameterinitialize(['SourceAccountingPlanId' => '1'], $companyId);
    print_result('Parametrización Base del plan contable Plan General de Contabilidad 2008',$parametrizationBaseTemplate);

//10) Obtención de una cuenta contable para posteriormente modificarla
	$accountingAccount = App\Anfix\CompanyAccountingAccount::select(2019, 1000000,$companyId);
    print_result('Obtención de datos de una cuenta contable',$accountingAccount);

//11) Copia de un predefinido
	$predefinedAccountingEntryCopy = App\Anfix\PredefinedAccountingEntry::copy(['AccountingPeriodYear' => 2019, 'Action' => 'COPY','CompanyIdSource' => $companyId, 'PredefinedEntriesId' => ['P']],$companyId);
    print_result('Copia de un predefinido',$predefinedAccountingEntryCopy);

//12) Búsqueda de asientos predefinidos por tipo de asiento
	$predefinedAccountingEntry = App\Anfix\PredefinedAccountingEntry::where(['EntryTypeToPredefinedEntryEntryTypeId' => '2'],$companyId)->get([],5,1,[],'','searchbyentrytype',['AccountingPeriodYear' => 2019]);
    print_result('Predefinido en base a un tipo de asiento',array_map(function($e){ return array(
	    "PredefinedAccountingEntryCode" => $e->PredefinedAccountingEntryCode,
	    "PredefinedAccountingEntryTypeName" => $e->PredefinedAccountingEntryTypeName,
		"PredefinedAccountingEntryDescription" => $e->PredefinedAccountingEntryDescription,
	    "PredefinedAccountingEntryNote" => $e->PredefinedAccountingEntryNote,
	    "PredefinedAccountingEntryTaxLine" => $e->PredefinedAccountingEntryTaxLine
	); },$predefinedAccountingEntry));

//13) Obtención de un predefinido contable para posteriormente modificarlo
	$predefinedAccountingEntry = App\Anfix\CompanyAccountingEntryReference::selectPredefined(2019, '1',$companyId);
    print_result('Predefinido en base a un tipo de asiento',$predefinedAccountingEntry);

//14) Fijar una plantilla por defecto
	$template = App\Anfix\Template::setdefault('7', '1', $companyId);
    print_result('Fijada una plantilla por defecto',$template);

//15) Duplicar un presupuesto
	$customerBudget = App\Anfix\CustomerBudget::duplicate(['LQYEKEtJY'],$companyId);
	print_result('Presupuesto duplicado',$customerBudget);

//16) Duplicar una factura emitida
	$issuedInvoice = App\Anfix\IssuedInvoice::duplicate(['1aWViTNwps'],$companyId);
	print_result('Factura emitida duplicada',$issuedInvoice);

//17) Duplicar una factura recibida
	$receivedInvoice = App\Anfix\ReceivedInvoice::duplicate(['MbS010Qe8'],$companyId);
	print_result('Factura recibida duplicada',$receivedInvoice);
