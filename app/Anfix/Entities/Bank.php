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

class Bank extends BaseModel
{
    protected $applicationId = 'e';
    protected $Model = 'Banks';
    protected $apiUrlSufix = '/bank/';
    protected $update = false;
	protected $create = false;
	protected $delete = false;

    /**
     * Obtiene el listado de productos bancarios de la empresa.
     * @param string $companyId
     * @return Object
     */
	public static function getBalanceBanks($params,$companyId){
		$obj = new static([],false,$companyId);
        $result = self::_send($params,$companyId,'getbalancebanks',[],[],$urlBase);
        foreach($result->outputData->{$obj->Model}->{$obj->Model} as $params){
            $return[] = $params;
        }
        return $return;
	}
}
