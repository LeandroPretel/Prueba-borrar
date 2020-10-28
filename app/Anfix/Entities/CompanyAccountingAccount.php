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

class CompanyAccountingAccount extends BaseModel
{
    protected $applicationId = '3';
    protected $apiUrlSufix = 'company/accountingplan/accountingaccount/';
	protected $primaryKey = 'CompanyAccountingAccountNumber';
    protected $update = true;
    protected $create = true;
    protected $delete = true;

   /**
	* Impresión de cuentas del plan contable de la empresa.
	* Su utilización es similar a ->get, siempre después de ::where()
	* @param array $params AccountingPeriodYear obligatorio
	* @param array $fields = [] Campos a devolver
	* @param int $maxRows = null Máximo de filas a mostrar, si no se indica se devolverán 50
	* @param null $minRowNumber Primera entrada a devolver como resultado del conjunto total de entradas devueltas por la operación
	* @param array $order Ordenación 	CompanyAccountingAccountDescription, CompanyAccountingAccountNumber
	* @param string $orderTypes ”ASC” o ”DESC”
	* @return array
	*/
	public function accountPrint(array $params, array $fields = [], $maxRows = null, $minRowNumber = null, $order = ['CompanyAccountingAccountDescription'], $orderTypes = 'ASC'){
		return $this->get($fields, $maxRows, $minRowNumber, $order, $orderTypes, 'print', $params);
	}

	/**
     * Selección de datos de subcuentas contables.
	 * @param int $accountingPeriodYear Año
	 * @param int $companyAccountingAccountNumber Número de cuenta a seleccionar.
     * @param string $companyId Identificador de la empresa
     * @return Object
     */
    public static function select($accountingPeriodYear, $companyAccountingAccountNumber, $companyId){
        $obj = new static([],false,$companyId);
		$params = ['AccountingPeriodYear' => $accountingPeriodYear, 'CompanyAccountingAccountNumber' => $companyAccountingAccountNumber];
        $result = self::_send($params,$companyId,'select');

		if(empty($result->outputData->{$obj->Model}))
			return null;

        return new static(get_object_vars($result->outputData->{$obj->Model}),true,$companyId);
    }

	/**
	 * Crea el objeto en la API
	 * @param array $params
	 * @param string $companyId Identificador de la compañia, sólo necesario en algunas entidades
	 * @param string $path = 'create' Path de la función en anfix
	 * @return CompanyAccountingAccount|CompanyAccountingAccountManager
     * @throws AnfixResponseException
     * @throws AnfixException
	 */
	public static function create(array $params, $companyId = null, $path = 'create'){
		return CompanyAccountingAccountManager::create($params, $companyId, $path);
    }
}
