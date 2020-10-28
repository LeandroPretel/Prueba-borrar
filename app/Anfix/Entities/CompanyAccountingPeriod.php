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

class CompanyAccountingPeriod extends BaseModel
{
    protected $applicationId = '3';
    protected $apiUrlSufix = 'company/accountingperiod/';
    protected $primaryKey = 'AccountingPeriodYear';
    protected $update = true;
    protected $create = false;
    protected $delete = true;

    /**
     * Bloquear/Desbloquear ejercicio contable.
     * @param array $params AccountingPeriodYear y CompanyAccountingPeriodLocked obligatorios
     * @param string $companyId Identificador de la empresa
     * @return int Número de ejercicios afectados por la operación.
     */
    public static function lockunlock(array $params, $companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send($params,$companyId,'lockunlock');

        return $result->outputData->{$obj->Model}->rowcount;
    }

    /**
     * Actualizar saldos de ejercicio contable.
     * @param int $accountingPeriodYear Año del ejercicio contable cuyos saldos se quieren actualizar
     * @param string $companyId Identificador de la empresa
     * @return int Número de ejercicios afectados por la operación.
     */
    public static function regeneratebalance($accountingPeriodYear, $companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send(['AccountingPeriodYear' => $accountingPeriodYear],$companyId,'regeneratebalance');

        return $result->outputData->{$obj->Model}->rowcount;
    }

    /**
     * Cierre del ejercicio contable.
     * @param array $params Según documentación anfix: AccountingPeriodYear y AccountingPeriodYear obligatorios
     * @param string $companyId Identificador de la empresa
     * @return int Número de ejercicios afectados
     */
    public static function close(array $params, $companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send($params,$companyId,'close');

        return $result->outputData->{$obj->Model}->rowcount;
    }

    /**
     * Creación de nuevo ejercicio contable.
     * @param array $params AccountingPeriodYear, CompanyAccountingPeriodEndDate, CompanyAccountingPlanId obligatorios
     * @param string $companyId Identificador de la empresa
     * @return string Fecha de creación del ejercicio contable.
     */
    public static function createwithplan(array $params, $companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send($params,$companyId,'createwithplan');

        return $result->outputData->{$obj->Model}->CompanyAccountingPeriodCreationDate;
    }
}
