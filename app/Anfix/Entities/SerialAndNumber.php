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

class SerialAndNumber extends StaticModel
{
	/** @var  @var string Obligatorio, Identificador de la App Anfix, este identificador asocia la Url base por defecto conforme a config/anfix.php */
	protected static $applicationId = 'e';
	/**  @var string Opcional, Nombre de la entidad en Anfix, por defecto será el nombre de la clase */
	protected static $Model = null;
	/**  @var string Opcional, Nombre de la clave primaria en Anfix, por defecto {$Model}Id */
	protected static $primaryKey = null;
	/**  @var string Opcional, Sufijo que se añade a la url de la API, por defecto nombre de la entidad, si se indica apiBaseUrl no se tendrá en cuenta este parámetro */
	protected static $apiUrlSufix = 'serialandnumber/';

	protected static function constructStatic(){
		parent::constructStatic();
		static::$apiBaseUrl = str_replace('cm','',static::$apiBaseUrl);
	}

	/**
	 * Devuelve el siguiente número disponible para una serie
	 * @param array $params Debe contener [DocumentDate => 'DATE', DocumentTypeId => 'ID', SerialNum => 'SERIAL'] obligatoriamente
	 * @param string $companyId Id de empresa
	 */
	public static function getnextserialandnumber(array $params, $companyId){

		$result = self::_send($params,$companyId,'getnextserialandnumber');

        if(empty($result->outputData->{self::$Model}))
            return false;

        return $result->outputData->{self::$Model}->Number;
	}

}
