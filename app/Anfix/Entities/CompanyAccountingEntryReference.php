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

class CompanyAccountingEntryReference extends BaseModel
{
    protected $applicationId = '3';
    protected $apiUrlSufix = 'company/accountingentry/';
    protected $primaryKey = 'AccountingEntryId';
    protected $update = true;
    protected $create = true;
    protected $delete = true;

    /**
     * Completar asiento contable a partir de asiento predefinido.
	 * @param array $params AccountingPeriodYear, AccountingEntryPredefinedEntryId y CompanyAccountingAccountNumber obligatorios
     * @param string $companyId Identificador de la empresa
     * @return Object
     */
    public static function completepredefined(array $params, $companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send($params,$companyId,'completepredefined');

        return $result->outputData->{$obj->Model};
    }

    /**
     * Traslado de asientos contables entre cuentas.
     * @param array $params AccountingPeriodYear, DestinationCompanyAccountingAccountNumber, SourceCompanyAccountingAccountNumber y CompanyAccountingEntryNote obligatorios
     * @param string $companyId Identificador de la empresa
     * @return true
     */
    public static function transfer(array $params, $companyId){
        self::_send($params,$companyId,'transfer');

        return true;
    }

    /**
     * Selección de datos de asientos
     * @param array $params AccountingPeriodYear y AccountingEntryId obligatorios
     * @param string $companyId Identificador de la empresa
     * @return Object
     */
    public static function select(array $params, $companyId){
        $obj = new static([],false,$companyId);

        $result = self::_send($params,$companyId,'select');

        return $result->outputData->{$obj->Model};
    }

    /**
     * Selección de datos de asientos predefinidos.
     * @param int $accountingPeriodYear Año
     * @param string $predefinedEntryId Identificador del asiento predefinido a seleccionar.
     * @param string $companyId Identificador de la empresa
     * @return Object
     */
    public static function selectPredefined($accountingPeriodYear,$predefinedEntryId,$companyId){
        //accountingentry
        $obj = new static([],false,$companyId);
        $result = self::_send(['AccountingPeriodYear' => $accountingPeriodYear, 'AccountingEntryPredefinedEntryId' => $predefinedEntryId],$companyId,'selectpredefined');
        return $result->outputData;
    }
}
