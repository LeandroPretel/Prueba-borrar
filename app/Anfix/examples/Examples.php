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
print_result('Primera empresa ',$companyId);

// 1) Creación de directorio
	// $directoryCreated = Anfix\MyDocuments::createdirectory(['HumanReadableName' => 'temp', 'HumanReadablePath'=> '/', 'OwnerId' => 'L7DZmNGqU','OwnerTypeId' => '2'],$companyId);
	// print_result('Creación de una carpeta en Documentos',$directoryCreated);

//Módulo de Facturación

// Añadir los dos campos tipo e id
//2) Creación de cobro
	/*$charge = Anfix\Charge::create([
		'ChargeAmount' => 50.05,
		'ChargeCustomerName'=> 'Pepe',
		'ChargeDate' => '11/05/2019',
		'ChargeDescription' => 'Pago adelantado factura',
		'ChargeIsRefund' => false,
		'ChargeSourceId' => '1aWV6;awHk',
		"ChargeSourceType" => '2',
		"ChargeAccountId" => "1aWUmCH5xI",
		"ChargeAccountTypeId" => "4"
	],$companyId);
	print_result('Creación de un cobro asociado a una factura emitida',$charge->ChargeId);*/

//3) Actualización de cobro
	// $chargeToUpdate = Anfix\Charge::where(['ChargeId' => $charge->ChargeId],$companyId)->get();
	// print_result('Cobro a actualizar',$chargeToUpdate[$charge->ChargeId]);

	// $chargeToUpdate[$charge->ChargeId]->ChargeDescription = 'Anticipo';
	// $chargeToUpdate[$charge->ChargeId]->ChargeAmount = 35.03;
  //   $chargeToUpdate[$charge->ChargeId]->save(); //Actualizo el cobro
	// print_result('Actualización de un cobro asociado a una factura emitida',$chargeToUpdate[$charge->ChargeId]->getArray());

//4) Eliminación de cobro
	// $chargeToDelete = Anfix\Charge::firstOrFail([],$companyId);
	// print_result('Cobro a eliminar',$chargeToDelete);
	// $result = $chargeToDelete-> delete();
	// print_result('Número de cobros eliminados',$result);

	// $customer = Anfix\Customer::create([
	// 	'CustomerAccountingAccountNumber' => 4300006,
	// 	'CustomerCompanyTypeId' => '1',
	// 	'CustomerName'=> 'Alberto',
	// 	'CustomerIdentificationTypeId' => '1',
	// 	'CustomerIdentificationNumber' => '11111111H',
	// 	'CustomerUniqueDebitMandateReference' => 'abcdefghijklmnopqrstuvwxyz012345678',
	// 	'CustomerMandateSignatureDate' => '20/01/2014 17:34:00',
	// 	'AccountingPeriodYear' => 2012,
	// 	'CustomerFixedDiscount' => 25.0,
	// 	'CustomerTaxTypeId' => '1',
	// 	'CustomerIncludeEquivalentCharge' => true,
	// 	'CustomerComments' => 'Este cliente cambia de domicilio a partir de Septiembre.',
	// 	'Action' => 'ADD',
	// 	'FiscalAddress' => ['Action' => 'ADD', 'AddressText' => 'Plaza España, 13, 3',  'AddressCountryId'=> '1'],
	// 	'Contact' => array(['Action' => 'ADD', 'ContactPersonName' => 'Alberto']),
	// 	'BankAccount' => ['Action' => 'ADD', 'BankAccountIBAN' => 'ES4801822370420000000011'],
	// 	'CustomerIVATypeId' => '1',
	// 	],$companyId);

	// print_result('Cliente creado',$customer->CustomerId);

//5) Modificación de cliente
	// $customerToUpdate = Anfix\Customer::where(['CustomerId' => $customer->CustomerId],$companyId)->get();

	// $customerToUpdate[$customer->CustomerId]->CustomerName = 'Miguel';
	// $customerToUpdate[$customer->CustomerId]->CustomerFixedDiscount = 50;
  //   $customerToUpdate[$customer->CustomerId]->save(); //Actualizo el cliente

	// print_result('Actualización de un cliente',$customerToUpdate[$customer->CustomerId]->getArray());

//6) Eliminación de cliente
	/*$customerToDelete = Anfix\Customer::firstOrFail([],$companyId);
	print_result('Cliente a eliminar',$customerToDelete);
	$result = $customerToDelete-> delete();
	print_result('Número de clientes eliminados',$result);*/

//6) Creación de presupuesto
    /*$customerToUse = Anfix\Customer::firstOrFail([],$companyId);
    print_result('Código de Cliente al que hacer un presupuesto',$customerToUse->CustomerName);

	$customerBudget = Anfix\CustomerBudget::create([
				'CustomerBudgetCustomerId' => $customerToUse->CustomerId,
				'CustomerBudgetCustomerIdentificationNumber' => $customerToUse->CustomerIdentificationNumber,
				'CustomerBudgetCustomerIdentificationTypeId' => $customerToUse->CustomerIdentificationTypeId,
				'CustomerBudgetCustomerName'=> $customerToUse->CustomerName,
				'CustomerBudgetCustomerTaxTypeId' => $customerToUse->CustomerTaxTypeId,
				'CustomerBudgetCustomerVATTypeId' => $customerToUse->CustomerIVATypeId,
				'CustomerBudgetDiscountPercentage' => $customerToUse->CustomerFixedDiscount,
				'CustomerBudgetDate' => '01/05/2019',
				'CustomerBudgetSerialNum' => 'P2019',
				'CustomerBudgetStateId' => '1',
				'Action' => 'ADD',
				'CustomerBudgetLine' => array(['Action' => 'ADD', 'CustomerBudgetLineItemRef' => 'MTO',
											'CustomerBudgetLineItemDescription' => 'Mantenimiento trimestral',
											'CustomerBudgetLineQuantity' => 1,
											'CustomerBudgetLinePrice' => 100,
											'CustomerBudgetLineVAT' => 21,
											'CustomerBudgetLineES' => 5.2]),
				],$companyId);

	print_result('Presupuesto creado',$customerBudget->CustomerBudgetId);*/

//8) Modificación de presupuesto
	/*$customerBudgetToUpdate = Anfix\CustomerBudget::where(['CustomerBudgetId' => $customerBudget->CustomerBudgetId],$companyId)->get();

	$customerBudgetToUpdate[$customerBudget->CustomerBudgetId]->CustomerBudgetCustomerName = 'Miguel';
	$customerBudgetToUpdate[$customerBudget->CustomerBudgetId]->CustomerBudgetDate = '02/05/2019';
    $customerBudgetToUpdate[$customerBudget->CustomerBudgetId]->save(); //Actualizo el presupuesto

	print_result('Actualización de un presupuesto',$customerBudgetToUpdate[$customerBudget->CustomerBudgetId]->getArray());*/

//9) Eliminación de presupuesto
	/*$customerBudgetToDelete = Anfix\CustomerBudget::firstOrFail([],$companyId);
	print_result('Presupuesto a eliminar',$customerBudgetToDelete);
	$result = $customerBudgetToDelete-> delete();
	print_result('Número de presupuestos eliminados',$result);*/

//10) Creación de factura emitida indicando asientos contables
	/*$customerToUse = Anfix\Customer::firstOrFail([],$companyId, 'searchwithpendinginvoices');

		print_result('Código de Cliente al que hacer una factura',$customerToUse->CustomerName);

	$issuedInvoice = Anfix\IssuedInvoice::create([
		'Action' => 'ADD',
    'IssuedInvoiceComments' => 'Observaciones',
		'IssuedInvoiceCustomerId' => $customerToUse->CustomerId,
		'IssuedInvoiceCustomerIdentificationNumber' => $customerToUse->CustomerIdentificationNumber,
		'IssuedInvoiceCustomerIdentificationTypeId' => $customerToUse->CustomerIdentificationTypeId,
		'IssuedInvoiceCustomerName'=> $customerToUse->CustomerName,
		'IssuedInvoiceCustomerTaxTypeId' => $customerToUse->CustomerTaxTypeId,
		'IssuedInvoiceCustomerVATTypeId' => $customerToUse->CustomerIVATypeId,
    'IssuedInvoiceDate' => '20/01/2019 15:05:27',
    'IssuedInvoiceNumber' => 30,
    'IssuedInvoiceRefNumber' => 'Rf-987030',
    'IssuedInvoiceSerialNum' => '12345',
    'IssuedInvoiceStateId' => '1',
    'IssuedInvoiceTemplateId' => '65',
    'IssuedInvoiceTemplateLanguage' => 1,
    'IssuedInvoiceTotalValue' => 121,
    'EntriesGenerated'=>array([
      'CompanyAccountingEntryReference'=>[
        'CompanyAccountingEntryNote'=>array([
          'Action' => 'ADD',
          'AccountingEntryNoteTypeId' => '1',
          'AccountingEntryAmount' => 12,
          'AccountingEntryIsDebitAmount' => true,
          'CompanyAccountingAccountNumber' => 6000000,
          'CompanyAccountingAccountNumberBalance' => 7000000,
          'AccountingEntryConcept' => 'Prueba'
        ],
        [
          'AccountingEntryNoteTypeId' => '1',
          'Action' => 'ADD',
          'AccountingEntryAmount' => 12,
          'AccountingEntryIsDebitAmount' => false,
          'CompanyAccountingAccountNumber' => 7000000,
          'CompanyAccountingAccountNumberBalance' => null,
          'AccountingEntryConcept' => 'Prueba'
        ]),
        'AccountingEntryDate' => '20/01/2019',
        'Action' => 'ADD',
        'PredefinedAccountingEntryId' => 'S',
        'AccountingEntryPredefinedEntryId' => 'S',
        'AccountingEntryTypeId' => '2',
        'CompanyId' => $companyId,
        'AccountingPeriodYear' => 2019,
        'DocumentList'=>array([
          'DocumentId' => 'a',
          'DocumentTypeId' => '8'
        ])
      ]
    ]),
    'Address'=>array([
      'Action' => 'ADD',
      'AddressId' => null,
      'AddressCity' => 'Barcelona',
      'AddressCountryId' => '1',
      'AddressPostalCode' => '8007',
      'AddressProvince' => 'Barcelona',
      'AddressText' => 'Diputación'
    ]),
    'Contact'=>array([
      'Action' => 'ADD',
      'ContactId' => null,
      'ContactPersonName' => 'Castillo',
      'ContactPhoneNumber' => '902 793 840',
      'ContactFaxNumber' => '000 777 840'
    ]),
    'IssuedInvoiceLine'=>array([
      'Action' => 'ADD',
      'IssuedInvoiceLineItemDescription' => 'Descripción 1',
      'IssuedInvoiceLinePrice' => 100,
      'IssuedInvoiceLinePriceisDirty' => true,
      'IssuedInvoiceLinePriceTaxIncluded' => true,
      'IssuedInvoiceLineQuantity' => 1,
      'IssuedInvoiceLineTotal' => 121,
      'IssuedInvoiceLineVAT' => 21,
      'IssuedInvoiceLineDirTaxValueId' => 'g'
    ]),
    'IssuedInvoiceHasToSendMessage' => false
	],$companyId);

	print_result('Factura emitida creada',$issuedInvoice->IssuedInvoiceId);

	$customerBudget = Anfix\CustomerBudget::firstOrFail([],$companyId)->get();
	print_result('Presupuesto a facturar',$customerBudget[key($customerBudget)]);

	$customerBudgetToBill = $customerBudget[key($customerBudget)];

	$invoice = $customerBudgetToBill->generatedocuments([$customerBudgetToBill->CustomerBudgetId],3,$companyId);
    print_result('Conversión de presupuesto en factura',$invoice); */

//11) Modificación de factura emitida
    /*$invoice = Anfix\IssuedInvoice::firstOrFail([],$companyId);
    print_result('Factura a modificar', $invoice);

    $invoice->IssuedInvoiceDate = '05/05/2020 13:00:00';
    $result = $invoice->save();

    print_result('Factura modificada',$result);*/

//12) Eliminación de factura emitida
    /*$invoice = Anfix\IssuedInvoice::firstOrFail([],$companyId);
    print_result('Factura a eliminar', $invoice);

    $result = $invoice->delete([
			AccountingPeriodYear" => 2019]);
    print_result('Factura eliminada',$result);*/

//13) Creación de pago Añadir el tipo y el id del producto bancario contra caja
    /*$payment = Anfix\Payment::create([
			'PaymentAmount' => 50.05,
			'PaymentSupplierName'=> 'Pepe',
			'PaymentDate' => '11/05/2019','PaymentDescription' => 'Pago adelantado factura',
			'PaymentIsRefund' => false,
			'PaymentSourceId' => '1aWViTNwps',
			"PaymentSourceType" => '1',
			"PaymentAccountId" => "1aWUmCH5xI",
			"PaymentAccountTypeId" => "4"
		],$companyId);
	print_result('Creación de un pago asociado a una factura recibida',$payment->PaymentId);*/

//14) Modificación de pago
	/*$paymentToUpdate = Anfix\Payment::where(['PaymentId' => $payment->PaymentId],$companyId)->get();
	print_result('Pago a actualizar',$paymentToUpdate[$payment->PaymentId]);

	$paymentToUpdate[$payment->PaymentId]->PaymentDescription = 'Anticipo';
	$paymentToUpdate[$payment->PaymentId]->PaymentAmount = 35.03;
    $paymentToUpdate[$payment->PaymentId]->save(); //Actualizo el pago
	print_result('Actualización de un pago asociado a una factura recibida',$paymentToUpdate[$payment->PaymentId]->getArray());	*/

//15) Eliminación de pago
	/*$paymentToDelete = Anfix\Payment::firstOrFail([],$companyId);
	print_result('Pago a eliminar',$paymentToDelete);
	$result = $paymentToDelete-> delete();
	print_result('Número de pagos eliminados',$result);	*/

	//16) Creación de factura recibida

	/*$receivedInvoice = Anfix\ReceivedInvoice::create([
		'ReceivedInvoiceSupplierIdentificationNumber' => '11111111H',
    'ReceivedInvoiceSupplierIdentificationTypeId' => '1',
    'ReceivedInvoiceSupplierName'=> 'Proveedor',
		'ReceivedInvoiceHasSupplier' => true,
    'ReceivedInvoiceSerialAndNumber'=>'F201810-4',
    'ReceivedInvoiceCategoryTypeId'=>'d',
    'ReceivedInvoiceDate'=>'01/09/2018',
    'ReceivedInvoiceOperationDate'=>'01/09/2018',
    'ReceivedInvoiceIncludeEquivalentCharge'=>true,
    'ReceivedInvoiceCarriageCosts'=>10.56,
    'ReceivedInvoiceCarriageCostsVAT'=>21.00,
    'ReceivedInvoiceCarriageCostTaxValueId'=>'g',
    'ReceivedInvoiceCarriageCostsES'=>5.20,
    'ReceivedInvoicePromptPaymentPercentage'=>9.98,
    'ReceivedInvoiceSupplierTaxTypeId'=>'1',
    'ReceivedInvoiceSupplierVATTypeId'=>'1',
    'ReceivedInvoiceDiscountPercentage'=>10,
    'ReceivedInvoiceFinancingPercentage'=>20.10,
    'ReceivedInvoiceDeductionPercentage'=>31.04,
    'ReceivedInvoicePriceTaxIncluded'=>true,
    'EntriesGenerated'=>array([
      'CompanyAccountingEntryReference'=> [
        'CompanyAccountingEntryNote'=>array([
          'AccountingEntryNoteTypeId'=>'1',
          'AccountingEntryAmount'=>12,
          'AccountingEntryIsDebitAmount'=>true,
          'CompanyAccountingAccountNumber'=>6000000,
          'CompanyAccountingAccountNumberBalance'=>7000000,
          'Action'=>'ADD',
          'AccountingEntryConcept'=>'Prueba'
        ], [
          'AccountingEntryNoteTypeId'=>'1',
          'AccountingEntryAmount'=>12,
          'AccountingEntryIsDebitAmount'=>false,
          'CompanyAccountingAccountNumber'=>7000000,
          'CompanyAccountingAccountNumberBalance'=>null,
          'Action'=>'ADD',
          'AccountingEntryConcept'=>'Prueba'
        ]),
        'AccountingEntryDate'=>'01/09/2018',
        'PredefinedAccountingEntryId'=>'S',
        'AccountingEntryPredefinedEntryId'=>'S',
        'AccountingEntryTypeId'=>'4',
        'Action'=>'ADD',
        'CompanyId'=>$companyId,
        'AccountingPeriodYear'=>2018,
        'DocumentList'=>array([
          'DocumentId'=>'a',
          'DocumentTypeId'=>'3'
        ])
			]
    ]),
    'ReceivedInvoiceLine'=>array([
        'Action'=>'ADD',
        'IdTemporal'=>1,
        'ReceivedInvoiceLineItemDescription'=>'Desc Item prueba 1',
        'ReceivedInvoiceLineQuantity'=>1,
        'ReceivedInvoiceLinePrice'=>10,
        'ReceivedInvoiceLineVAT'=>21.0,
        'ReceivedInvoiceLineES'=>5.20,
        'ReceivedInvoiceLineDiscountPercentage'=>10.0,
        'ReceivedInvoiceLineLinealDiscount'=>1,
        'ReceivedInvoiceLineDirTaxValueId'=>'g'
      ], [
        'Action'=>'ADD',
        'IdTemporal'=>2,
        'ReceivedInvoiceLineItemDescription'=>'Desc Item2 prueba 1',
        'ReceivedInvoiceLineQuantity'=>2,
        'ReceivedInvoiceLinePrice'=>20,
        'ReceivedInvoiceLineVAT'=>21.0,
        'ReceivedInvoiceLineES'=>5.20,
        'ReceivedInvoiceLineDiscountPercentage'=>10.0,
        'ReceivedInvoiceLineLinealDiscount'=>2,
				'ReceivedInvoiceLineDirTaxValueId'=>'g'
			]),
      'ReceivedInvoiceIsEqualExpiration'=>false,
      'Expiration'=>array([
          'Action'=>'ADD',
          'IdTemporal'=>1,
          'ExpirationDate'=>'01/11/2018',
          'ExpirationQuantity'=>100.0
        ], [
          'Action'=>'ADD',
          'IdTemporal'=>2,
          'ExpirationDate'=>'05/12/2018',
					'ExpirationQuantity'=>250.0
        ]),
        'Address'=>array([
          'Action'=>'ADD',
          'AddressCity'=>'VALLADOLID',
          'AddressCountry'=>'España',
          'AddressCountryId'=>'1',
          'AddressPostalCode'=>'47010',
          'AddressProvince'=>'Valladolid',
          'AddressText'=>'direccion en texto'
        ]),
        'Contact'=>array([
          'Action'=>'ADD',
          'ContactEMail'=>'correo@dominio.com',
          'ContactPersonName'=>'nombre de contacto',
          'ContactPhoneNumber'=>'87666666666'
				])
		], $companyId);

	print_result('Factura recibida creada',$receivedInvoice->ReceivedInvoiceId);*/

//17) Modificación de factura recibida
	/*$receivedInvoiceToUpdate = Anfix\ReceivedInvoice::where(['ReceivedInvoiceId' => $receivedInvoice->ReceivedInvoiceId],$companyId)->get();
	print_result('Factura recibida a actualizar',$receivedInvoiceToUpdate[$receivedInvoice->ReceivedInvoiceId]);

	$receivedInvoiceToUpdate[$receivedInvoice->ReceivedInvoiceId]->ReceivedInvoiceSupplierName = 'Miguel';
	$receivedInvoiceToUpdate[$receivedInvoice->ReceivedInvoiceId]->ReceivedInvoiceDate = '02/05/2019';
    $receivedInvoiceToUpdate[$receivedInvoice->ReceivedInvoiceId]->save(); //Actualizo la factura recibida
	print_result('Actualización de una factura recibida',$receivedInvoiceToUpdate[$receivedInvoice->ReceivedInvoiceId]->getArray());*/

//18) Eliminación de factura recibida
	/*$receivedInvoiceToDelete = Anfix\ReceivedInvoice::firstOrFail([],$companyId);
	print_result('Factura recibida a eliminar',$receivedInvoiceToDelete);
	$result = $receivedInvoiceToDelete-> delete();
	print_result('Número de facturas recibidas eliminadas',$result);*/

//19) Creación de factura recurrente
	/*$customerToUse = Anfix\Customer::firstOrFail([],$companyId);

    print_result('Código de Cliente al que hacer una factura',$customerToUse->CustomerName);

	$recurringInvoice = Anfix\RecurringInvoice::create([
				'RecurringInvoiceCustomerId' => $customerToUse->CustomerId,
				'RecurringInvoiceCustomerIdentificationNumber' => $customerToUse->CustomerIdentificationNumber,
				'RecurringInvoiceCustomerIdentificationTypeId' => $customerToUse->CustomerIdentificationTypeId,
				'RecurringInvoiceCustomerName'=> $customerToUse->CustomerName,
				'RecurringInvoiceCustomerTaxTypeId' => $customerToUse->CustomerTaxTypeId,
				'RecurringInvoiceCustomerVATTypeId' => $customerToUse->CustomerIVATypeId,
				'RecurringInvoiceSerialNum' => 'F2019',
				'RecurringInvoiceStateId' => '1',
				'RecurringInvoiceEndDate' => '04/05/2019',
				'RecurringInvoiceFrequencyQuantity' => 1,
				'RecurringInvoiceFrequencyTypeId' => "1",
				'RecurringInvoiceStartDate' => '04/05/2019',
				'Action' => 'ADD',
				'RecurringInvoiceLine' => array(['Action' => 'ADD', 'RecurringInvoiceLineItemRef' => 'MTO',
											'RecurringInvoiceLineItemDescription' => 'Mantenimiento trimestral',
											'RecurringInvoiceLineQuantity' => 1,
											'RecurringInvoiceLinePrice' => 100,
											'RecurringInvoiceLineVAT' => 21,
											'RecurringInvoiceLineES' => 5.2]),
				],$companyId);

	print_result('Factura recurrente creada',$recurringInvoice->RecurringInvoiceId);*/

//20) Modificación de factura recurrente
	/*$recurringInvoiceToUpdate = Anfix\RecurringInvoice::where(['RecurringInvoiceId' => $recurringInvoice->RecurringInvoiceId],$companyId)->get();
	print_result('Factura recurrente a actualizar',$recurringInvoiceToUpdate[$recurringInvoice->RecurringInvoiceId]);

	$recurringInvoiceToUpdate[$recurringInvoice->RecurringInvoiceId]->RecurringInvoiceEndDate = '04/06/2019';
    $recurringInvoiceToUpdate[$recurringInvoice->RecurringInvoiceId]->save(); //Actualizo la factura recurrente
	print_result('Actualización de una factura recurrente',$recurringInvoiceToUpdate[$recurringInvoice->RecurringInvoiceId]->getArray());*/

//21) Eliminación de factura recurrente
	/*$recurringInvoiceToDelete = Anfix\RecurringInvoice::firstOrFail([],$companyId);
	print_result('Factura recurrente a eliminar',$recurringInvoiceToDelete);
	$result = $recurringInvoiceToDelete-> delete();
	print_result('Número de facturas recurrentes eliminadas',$result);*/

//22) Creación de un proveedor
	/*$supplier = Anfix\Supplier::create([
				'CustomerAccountingAccountNumber' => 4000006,
				'SupplierFiscalName'=> 'Mariano',
				'SupplierIdentificationTypeId' => '1',
				'SupplierIdentificationNumber' => '11111111H',
				'SupplierFixedDiscount' => 25.0,
				'SupplierTaxTypeId' => '1',
				'SupplierIncludeEquivalentCharge' => true,
				'SupplierComments' => 'Este proveedor cierra en Agosto',
				'Action' => 'ADD',
				'Address' => ['Action' => 'ADD', 'AddressText' => 'Plaza España, 13, 3',  'AddressCountryId'=> '1'],
				'Contact' => array(['Action' => 'ADD', 'ContactPersonName' => 'Alberto']),
				'BankAccount' => ['Action' => 'ADD', 'BankAccountIBAN' => 'ES4801822370420000000011'],
				'SupplierIVATypeId' => '1',
				],$companyId);

	print_result('Proveedor creado',$supplier->SupplierId);*/

//23) Modificación de un proveedor
	/*$supplierToUpdate = Anfix\Supplier::where(['SupplierId' => $supplier->SupplierId],$companyId)->get();

	$supplierToUpdate[$supplier->SupplierId]->CustomerName = 'Miguel';
	$supplierToUpdate[$supplier->SupplierId]->CustomerFixedDiscount = 50;
    $supplierToUpdate[$supplier->SupplierId]->save(); //Actualizo el proveedor

	print_result('Actualización de un proveedor',$supplierToUpdate[$supplier->SupplierId]->getArray());*/

//24) Eliminación de un proveedor
	/*$supplierToDelete = Anfix\Supplier::firstOrFail([],$companyId);
	print_result('Proveedor a eliminar',$supplierToDelete);
	$result = $supplierToDelete-> delete();
	print_result('Número de proveedores eliminados',$result);*/

//25) Creación de un tipo impositivo
	/*$vat = Anfix\Vat::create([
				'VatClassId' => '1',
				'VatEsValue'=> 5.2,
				'VatInitDate' => '01/05/2019',
				'VatTypeId' => '1',
				'VatValue' => 22.5,
				'Action' => 'ADD'
				],$companyId);

	print_result('Tipo impositivo creado',$vat->VatId);*/

//26) Modificación de un tipo impositivo
	/*$vatToUpdate = Anfix\Vat::find($vat->VatId,$companyId);

	$vatToUpdate->VatEndDate = '01/05/2019';
    $vatToUpdate->save(); //Actualizo el tipo impositivo

	print_result('Actualización de un tipo impositivo',$vatToUpdate->VatId);*/

//277) Eliminación de un tipo impositivo
	/*$vatToDelete = Anfix\Vat::find($vat->VatId,$companyId);
	print_result('Tipo impositivo a eliminar',$vatToDelete);

	$result = $vatToDelete-> delete();
	print_result('Número de tipos impositivos eliminados',$result);*/

// Módulo de Contabilidad

//28) Creación de un asiento contable
//Cambiar el valor de InvoiceOrder a uno libre, las fechas y el InvoiceCustomerSupplierId
	/*$payrollEntryReference = Anfix\CompanyAccountingEntryReference::create([
		"AccountingEntryTypeId" => "1",
		"PredefinedAccountingEntryId" => "b",
		"AccountingEntryDate" => "27/03/2019",
		"FlagCapitalAssets" => false,
		"AccountingEntryPredefinedEntryId" => "b",
		"Action" => "ADD",
		"AccountingPeriodYear" => 2019,
		"CompanyAccountingEntryNote" => array([
				"AccountingEntryNoteTypeId" => "1",
				"AccountingEntryConcept" => "Sueldos y Salarios Brutos",
				"CompanyAccountingAccountNumber" => 6400000,
				"AccountingEntryTypeId" => "1",
				"PredefinedAccountingEntryId" => "b",
				"AccountingEntryAmountDebit" => 1750,
				"Action" => "ADD",
				"AccountingEntryIsDebitAmount" => true,
				"AccountingEntryAmount" => 1750,
				"AccountingEntryNoteAmountExpression" => "?"
		], [
				"AccountingEntryNoteTypeId" => "7",
				"AccountingEntryConcept" => "I.R.P.F.",
				"CompanyAccountingAccountNumber" => 4751000,
				"AccountingEntryTypeId" => "1",
				"AccountingEntryAmountCredit" => 200,
				"IdTemporal" => 1,
				"Action" => "ADD",
				"AccountingEntryIsDebitAmount" => false,
				"AccountingEntryAmount" => 200,
				"AccountingEntryNoteAmountExpression" => "?"
		], [
				"AccountingEntryNoteTypeId" => "6",
				"AccountingEntryConcept" => "S.S. Total TC1",
				"CompanyAccountingAccountNumber" => 4760000,
				"AccountingEntryTypeId" => "1",
				"AccountingEntryAmountCredit" => 200,
				"IdTemporal" => 2,
				"Action" => "ADD",
				"AccountingEntryIsDebitAmount" => false,
				"AccountingEntryAmount" => 200,
				"AccountingEntryNoteAmountExpression" => "?"
		], [
				"AccountingEntryNoteTypeId" => "6",
				"AccountingEntryConcept" => "S.S. Empresa (Incl. Coti. Titu.)",
				"CompanyAccountingAccountNumber" => 6420000,
				"AccountingEntryTypeId" => "1",
				"AccountingEntryAmountDebit" => 100,
				"IdTemporal" => 3,
				"Action" => "ADD",
				"AccountingEntryIsDebitAmount" => true,
				"AccountingEntryAmount" => 100,
				"AccountingEntryNoteAmountExpression" => "?"
		], [
				"AccountingEntryNoteTypeId" => "6",
				"AccountingEntryConcept" => "L\u00edquido a pagar",
				"CompanyAccountingAccountNumber" => 4650000,
				"AccountingEntryTypeId" => "1",
				"AccountingEntryAmountCredit" => 1450,
				"IdTemporal" => 4,
				"Action" => "ADD",
				"AccountingEntryIsDebitAmount" => false,
				"AccountingEntryAmount" => 1450,
				"AccountingEntryNoteAmountExpression" => "?"
		]),
		], $companyId);
	print_result('Asiento de nómina creado',$payrollEntryReference->AccountingEntryId);*/

//29) Actualización de un asiento contable
     // Si creamos un asiento y lo actualizamos posteriormente
    /*$payrollEntryReference->AccountingEntryDate = '11/05/2019';
    $payrollEntryReference->AccountingPeriodYear = 2019;
    $payrollEntryReference->save(); //Actualizo el asiento contable

    print_result('Asiento de nómina actualizado',$payrollEntryReference->AccountingEntryId);*/

    //recuperando uno en concreto
    /*$payrollEntryReference = Anfix\CompanyAccountingEntryReference::where([],$companyId)->get([],1,1,[],'','search',['AccountingPeriodYear' => 2019]);
    $entryReferenceToUpdate = $payrollEntryReference[key($payrollEntryReference)];

    $entryReferenceToUpdate->AccountingEntryDate = '12/05/2019';
    $entryReferenceToUpdate->AccountingPeriodYear = 2019;
    $entryReferenceToUpdate->save(); //Actualizo el asiento contable

    print_result('Asiento de nómina actualizado',$entryReferenceToUpdate->AccountingEntryId);*/

//30) Eliminación de un asiento contable
    /*$entryReferenceToDelete = $entryReferenceToUpdate;
    $result = $entryReferenceToDelete->delete(["AccountingPeriodYear" => 2019]);
    print_result('Número de asientos eliminados',$result);*/

//31) Borrado de ejercicio contable
    /*$result = $accountingPeriodYear = Anfix\CompanyAccountingPeriod::purge(2013, $companyId);
    print_result('Número de ejercicios eliminados',$result);*/

//32) Desactivación de ejercicio contable
    /*$accountingPeriodYear = Anfix\CompanyAccountingPeriod::where(['AccountingPeriodYear' => 2021], $companyId)->get();
    print_result('Ejercicio a eliminar',$accountingPeriodYear[2021]);

    $result = $accountingPeriodYear[2021]->delete();
    print_result('Número de ejercicios desactivados',$result);*/

//33) Actualización de ejercicio contable
    /*$accountingPeriodYear = Anfix\CompanyAccountingPeriod::where(['AccountingPeriodYear' => 2012], $companyId)->get();
    print_result('Ejercicio a actualizar',$accountingPeriodYear[2012]);

    $accountingPeriodYear[2012]->CompanyAccountingPeriodInitDate = '02/01/2012 00:00:00';
    $result = $accountingPeriodYear[2012]->save();
    print_result('Número de ejercicios actualizados',$result);*/

//34) Actualización de una empresa
	/*$companyToUpdate = Anfix\Company::firstOrFail([],$companyId);

	$companyToUpdate->CorporateName = 'Empresa 2';
	$result = $companyToUpdate->save();

	print_result('Empresa actualizada', $result);*/

//35) Creación de cuenta contable
	/*$accountingAccount = Anfix\CompanyAccountingAccount::create([
				'AccountingPeriodYear' => 2019,
				'CompanyAccountingAccount' => array((object)['Action' => 'ADD',
														'CompanyAccountingAccountDescription' => 'Proveedores de inmovilizado a largo plazo',
														'CompanyAccountingAccountNumber' => 1730005
														])
				],$companyId);

	print_result('Cuenta contable creada',$accountingAccount);*/

//36) Modificación de cuenta contable

    /*$accountingAccount = Anfix\CompanyAccountingAccount::select(2019, 4300000, $companyId);

    $accountingAccount->AccountingPeriodYear = 2019;
    $accountingAccount->CompanyAccountingAccountNumber = 4300000;
    $accountingAccount->CompanyAccountingAccountInitBalance = 200;
    $result = $accountingAccount->save();
    print_result('Número de cuentas contables actualizadas',$result);*/

//37) Eliminación de cuenta contable
	/*$accountingAccountToDelete = Anfix\CompanyAccountingAccount::select(2019, 1730003, $companyId);
	print_result('Cuenta a eliminar',$accountingAccountToDelete);

	$result = $accountingAccountToDelete->delete(["AccountingPeriodYear" => 2019]);

    print_result('Número de cuentas contables eliminadas',$result);*/

//38) Creación de retención
	/*$deductionValue = Anfix\DeductionValue::create([
					'Action' => 'ADD',
					'DeductionValueTypeId' => '1',
					'DeductionValueInitDate' => '10/05/2019',
					'DeductionValueValue' => 0,
					'DeductionValueName' => 'Personalizada'
						],$companyId);

	    print_result('Impuesto creado',$deductionValue->DeductionValueId);*/
//39) Modificación de retención
	    /*$deductionValue->DeductionValueValue= 19.5;
	    $result = $deductionValue->save();
	    print_result("Retención actualizada", $result);*/

//40) Eliminación de retención
	    /*$result = $deductionValue->delete();
	    print_result("Retención eliminado", $result);*/

//41) Creación de predefinido
	/*$predefinedAccountingEntry = Anfix\PredefinedAccountingEntry::create([
				'AccountingPeriodYear' => 2019,
				'Action' => 'ADD',
				'PredefinedAccountingEntryCode' => '202',
				'PredefinedAccountingEntryDescription' => 'Predefinido personalizado',
				'PredefinedAccountingEntryTypeId' => '1',
				'PredefinedAccountingEntryNote' => array(['Action' => 'ADD',
										'PredefinedAccountingEntryNoteAccountNumber' => '4300?',
										'PredefinedAccountingEntryNoteAccountNumberDescription' => '4300?',
										'PredefinedAccountingEntryNoteConcept' => 'Pago a Proveedor $',
										'PredefinedAccountingEntryNoteDocumentDescription' => 'Factura -'
					])
					],$companyId);

    print_result('Predefindo creado',$predefinedAccountingEntry->PredefinedAccountingEntryId);*/

//42) Modificación de predefinido
    /*$predefinedAccountingEntry = Anfix\PredefinedAccountingEntry::where(['EntryTypeToPredefinedEntryEntryTypeId' => '2'],$companyId)->getByEntryType(2019,true, [], [], []);
    print_result('Predefinido a actualizar',$predefinedAccountingEntry);

    $predefinedToCreate = $predefinedAccountingEntry[key($predefinedAccountingEntry)];

    $predefinedToCreate->PredefinedAccountingEntryDescription = 'Asiento predefinido personalizado';
    $predefinedToCreate->AccountingPeriodYear=2019;
    $predefinedToCreate->PredefinedAccountingEntryCode='990';
    $result = $predefinedToCreate->save();

    print_result('Número de asientos predefinidos actualizados',$result);*/

//43) Eliminación de predefinido
    /*$predefinedAccountingEntry = Anfix\PredefinedAccountingEntry::where([],$companyId)->get([],1,1,['PredefinedAccountingEntryCode'],['ASC'],'search',['AccountingPeriodYear' => 2019, 'IncludeStructuralPredefinedAccountingEntries' => true]);
    print_result('Predefinido a eliminar',$predefinedAccountingEntry);

    $predefinedToDelete = $predefinedAccountingEntry[key($predefinedAccountingEntry)];

    $result = $predefinedToDelete->delete(["AccountingPeriodYear" => 2019]);

    print_result('Número de asientos predefinidos eliminados',$result);*/


//44) Creación de tipo impositivo
	/*$vat = Anfix\Vat::create([
				'VatClassId' => '1',
				'Action' => 'ADD',
				'VatEsValue' => 5.2,
				'VatInitDate' => '10/05/2019',
				'VatTypeId' => '1',
				'VatValue' => 28
					],$companyId);

    print_result('Impuesto creado',$vat->VatId);*/
//45) Modificación de tipo impositivo
    /*$vat -> VatValue= 29.5;
    $result = $vat->save();
    print_result("Impuesto actualizado", $result);*/

//46) Eliminación de tipo impositivo
    /*$result = $vat->delete();
    print_result("Impuesto eliminado", $result);*/

//47) Eliminación de plantilla de Balance/PyG
	/*$templates = Anfix\Template::where([],$companyId)->get([],1,1,[],'','search',['AccountingPeriodYear' => 2019]);

	$templateToDelete = $templates[key($templates)];
	print_result('Plantilla a eliminar', $templateToDelete);

	$result = $templateToDelete->delete();
	print_result('Plantilla eliminada', $result);*/
