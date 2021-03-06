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

class Journal extends StaticModel
{
	/** @var  @var string Obligatorio, Identificador de la App Anfix, este identificador asocia la Url base por defecto conforme a config/anfix.php */
	protected static $applicationId = '3';
	/**  @var string Opcional, Nombre de la entidad en Anfix, por defecto será el nombre de la clase */
	protected static $Model = null;
	/**  @var string Opcional, Nombre de la clave primaria en Anfix, por defecto {$Model}Id */
	protected static $primaryKey = null;
	/**  @var string Opcional, Url de la API a la que conectar, por defecto se obtiene de config/anfix en función del applicationId */
	protected static $apiBaseUrl = null;
	/**  @var string Opcional, Sufijo que se añade a la url de la API, por defecto nombre de la entidad, si se indica apiBaseUrl no se tendrá en cuenta este parámetro */
	protected static $apiUrlSufix = 'report/';

   /**
	* Generación del libro Diario.
	* @param array $params Parámetros
	* @param string $companyId Identificador de la empresa
	* @return Object
	*/
	public static function report(array $params,$companyId){
        $result = self::_send($params,$companyId,'journal');
        return $result->outputData->{self::$Model};
	}

}
