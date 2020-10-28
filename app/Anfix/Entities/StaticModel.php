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
*  Date: 9/2/16 18:21
*  @author Lucid Networks <info@lucidnetworks.es>
*  @copyright  2006-2015 Lucid Networks
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

namespace App\Anfix\Entities;

use App\Anfix\Exceptions\AnfixException;

/**
 * Class BaseModel
 * Esta clase es la clase base desde la que deben heredar todos los modelos de anfix
 * su comportamiento en muy similar al de Eloquent, salvo en la intrucción where que no admite llamadas recursivas
 * debe rellenar en cada modelo los datos obligatorios para poder establecer una conexión con anfix
 * @package Lucid\Anfix
 */
class StaticModel{

    // Al tratarse de variables estáticas estas deben declararse en cada modelo con su valor o  = null para evitar que se mantengan entre diferentes modelos

    /** @var  @var string Obligatorio, Identificador de la App Anfix, este identificador asocia la Url base por defecto conforme a config/anfix.php */
    protected static $applicationId;
    /**  @var string Opcional, Nombre de la entidad en Anfix, por defecto será el nombre de la clase */
    protected static $Model;
    /**  @var string Opcional, Nombre de la clave primaria en Anfix, por defecto {$Model}Id */
    protected static $primaryKey;
    /**  @var string Opcional, Url de la API a la que conectar, por defecto se obtiene de config/anfix en función del applicationId */
    protected static $apiBaseUrl;
    /**  @var string Opcional, Sufijo que se añade a la url de la API, por defecto nombre de la entidad, si se indica apiBaseUrl no se tendrá en cuenta este parámetro */
    protected static $apiUrlSufix;

    /**
     * Construye la configuración estática
     */
    protected static function constructStatic(){

        if(empty(static::$applicationId))
            throw new AnfixException('Debe indicar un applicationId en el modelo para poder utilizar la API');

        if(empty(static::$Model)) {
            $class = explode('\\', get_called_class());
            static::$Model = end($class);
        }

        if(empty(static::$apiUrlSufix))
            static::$apiUrlSufix = strtolower(static::$Model).'/';

        if(empty(static::$apiBaseUrl))
            static::$apiBaseUrl = Anfix::getEnv()['config']['applicationIdUrl'][static::$applicationId].static::$apiUrlSufix;

        if(empty(static::$primaryKey))
            static::$primaryKey = static::$Model.'Id';
    }

    /**
     * Envia una solicitud estándar
     * @param array $params
     * @param null $companyId
     * @param $path
     * @param string $baseUrl = null Indica una url base diferente a la del modelo para casos especiales
     * @return mixed
     */
    protected static function _send(array $params, $companyId = null, $path, $baseUrl = null){
        static::constructStatic();

        if(empty($baseUrl))
            $baseUrl = static::$apiBaseUrl;

        return Anfix::sendRequest(static::$apiBaseUrl.$path,[
            'applicationId' =>  static::$applicationId,
            'companyId' => $companyId,
            'inputBusinessData' => [
                static::$Model => !empty($params) ? $params : new \stdClass()
            ]
        ]);
    }


   /**
	* Descarga un fichero
	* @param array $params Parámetros para la descarga
	* @param string $path Ruta donde se guardará el fichero descargado
	* @param string $url Url punto acceso, por defecto {static::$apiBaseUrl}/download
	* @return true
	*/
	protected static function _download(array $params, $path, $url = null){
		static::constructStatic();

		if(empty($url))
			$url = static::$apiBaseUrl.'download';

        return Anfix::getFile($url,$params,$path);
    }


    /**
    * Subida de un fichero (Necesita php 5.2.2 o posterior)
    * @param string $path Ruta del fichero a enviar
    * @param string $url Url punto acceso, por defecto {static::$apiBaseUrl}/upload
    * @return Object
    * @throws AnfixException
	*/
	protected static function _upload($path, $url = null){
		static::constructStatic();

		if(empty($url))
			$url = static::$apiBaseUrl.'upload';

        $path = realpath ($path);

        if(!file_exists($path))
            throw new AnfixException("El path {$path} no existe");

        $mime = mime_content_type($path);

        $cfile = curl_file_create($path,$mime,basename($path));

        return Anfix::sendRequest($url,['upload' => $cfile], [], [], 'multipart/form-data',[
            'Content-Disposition: form-data;name="upload";filename="'.basename($path).'"',
            'Content-Type:'.$mime
        ]);
    }

}
