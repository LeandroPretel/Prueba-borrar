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

class Customer extends BaseModel
{
    protected $applicationId = 'e';
    protected $apiUrlSufix = 'sale/customer/';
    protected $update = true;
    protected $create = true;
    protected $delete = true;

    public function get(array $fields = [], $maxRows = null, $minRowNumber = null, array $order = [], $orderTypes = 'ASC', $path = 'searchwithpendinginvoices', array $params = [], $apiUrl = null){
        return parent::get($fields, $maxRows, $minRowNumber, $order, $orderTypes, $path, $params,$apiUrl);
    }

}