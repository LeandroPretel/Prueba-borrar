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

/**
 * Métodos especiales de la entidad
 */

include 'example_utils.php';

$companyId = firstCompanyId(); //Obtención del id de la primera empresa disponible (función únicamente válida para ejemplos)


$data = \App\Anfix\Media::upload(__DIR__.'/../Download/invoice.pdf');
print_result('Subida de un fichero',$data);


$data = \App\Anfix\Media::uploadAndCreateMedia(__DIR__.'/../Download/invoice.pdf',[
    'HumanReadableName' =>  'Prueba Subida fichero.pdf',
    'OwnerTypeId' =>  '2',
    'OwnerId' =>  $companyId,
    'HumanReadablePath' =>  '/'
]);
print_result('Subida de un fichero y creación del enlace MyDocuments',$data);
