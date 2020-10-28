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

class MyDocuments extends BaseModel
{
    protected $applicationId = '5';
	protected $primaryKey = 'EntryId';
    protected $update = false;
    protected $create = false;
    protected $delete = false;

   /**
	* Descarga un fichero
	* @param string $entryIds Identificador de los documentos a descargar, separados por +
    * @params string $path Ruta donde se guardará el/los fichero/s descargado/s
	* @return true
	*/
	public static function download($entryIds, $path){
        return self::_download(['entryIds' => $entryIds],$path);
	}

   /**
	* Creación de un nuevo directorio en el servicio My Documents.
	* @param array $params HumanReadableName, HumanReadablePath, OwnerId, OwnerTypeId obligatorios
	* @return string EntryId
	*/
	public static function createdirectory(array $params){
		$obj = new static();
		$result = parent::_send($params,null,'createdirectory');
		return $result->outputData->{$obj->Model}->EntryId;
	}

   /**
	* Creación en el servicio My Documents de un nuevo documento asociado a un archivo previamente transferido mediante Media::upload
	* @param array $params HumanReadableName, HumanReadablePath, OwnerId, OwnerTypeId obligatorios
	* @return Object
	*/
	public static function createfile(array $params){
		$obj = new static();
		$result = parent::_send($params,null,'createfile');
		return $result->outputData->{$obj->Model};
	}

   /**
	* Envía uno o varios documentos por correo electrónico a uno o más destinatarios.
	* @param array $params EntryIds, Email, EmailSubject, EmailText obligatorios
	* @param string $companyId
	* @return Object
	*/
	public static function send(array $params, $companyId){
		$obj = new static();
		$result = parent::_send($params,$companyId,'send');
		return $result->outputData->{$obj->Model};
	}

}
