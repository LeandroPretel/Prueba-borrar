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

class AccountingAccount extends BaseModel
{
    protected $applicationId = '3';
    protected $update = false;
    protected $create = false;
    protected $delete = false;

    /**
     * Devuelve un array de objetos desde la API
     * @param array $fields = [] Campos a devolver
     * @param int $maxRows = null Máximo de filas a mostrar, si no se indica se devolverán 50
     * @param null $minRowNumber Primera entrada a devolver como resultado del conjunto total de entradas devueltas por la operación
     * @param array $order Lista con los campos por los que se quiere ordenar los resultados
     * @param string $orderTypes ”ASC” o ”DESC”
     * @param string $path = 'search' Path de la función en anfix
     * @param array $params = [] HierarchyFlag y otros parámetros especiales a añadir en la solicitud
     * @param string $apiUrl = null Indica una url base diferente a la del modelo para casos especiales
     * @return array
     */
    public function get(array $fields = [], $maxRows = null, $minRowNumber = null, array $order = [], $orderTypes = 'ASC', $path = 'searchByAccPlanId', array $params = [], $apiUrl = null){
        return parent::get($fields, $maxRows, $minRowNumber, $order, $orderTypes, $path, $params,$apiUrl);
    }


}
