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

class CustomerBudget extends BaseModel
{
    protected $applicationId = 'e';
    protected $apiUrlSufix = 'sale/customerbudget/';
    protected $update = true;
    protected $create = true;
    protected $delete = true;

    /**
     * Duplicación de un presupuesto.
     * @param $CustomerBudgetIds array con los ids de presupuestos a duplicar
     * @param $companyId
     * @return Object
     */
    public static function duplicate($CustomerBudgetIds,$companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send(['CustomerBudgetIds' => $CustomerBudgetIds],$companyId,'duplicate');
        return $result->outputData->{$obj->Model};
    }

    /**
     * Generación de documentos a partir de un presupuesto.
     * @param $CustomerBudgetIds array con los ids de presupuestos
     * @param int $CustomerBudgetOutputType Tipo de documento que se quiere obtener, 3 para factura
     * @param $companyId
     * @return Object
     */
    public static function generatedocuments($CustomerBudgetIds,$CustomerBudgetOutputType = 3,$companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send(['CustomerBudgetId' => $CustomerBudgetIds, 'CustomerBudgetOutputType' => $CustomerBudgetOutputType],$companyId,'generatedocuments');
        return $result->outputData->{$obj->Model};
    }

    /**
     * Vista previa de presupuesto.
     * @param $params array con los datos para el presupuesto, CustomerBudgetSerialNum, CustomerBudgetTemplateId y CustomerBudgetTemplateLanguage obligatorios
     * @param $companyId
     * @return Object
     */
    public static function preview(array $params,$companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send($params,$companyId,'preview');
        return $result->outputData->{$obj->Model};
    }
}
