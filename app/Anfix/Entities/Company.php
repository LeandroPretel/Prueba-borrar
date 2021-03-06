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

class Company extends BaseModel
{
    protected $applicationId = 1;
    protected $update = true;

    /**
     * Busca una empresa filtrando por su usuario
     * @param string $userId Identificador del usuario
	 * @param array $fields = [] Campos a devolver
     * @throws AnfixException
     * @throws AnfixResponseException
     * @return mixed|null
     */
    public static function findByUser($userId,$fields = []){
        $data =  self::where([
            'UserId' => (string)$userId,
        ])->get($fields, null, null, [], 'ASC', 'searchbyuser');

        if(empty($data))
            return null;

        return reset($data);
    }
}
