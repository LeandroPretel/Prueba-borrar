<?php

include_once '../Anfix.php';
\App\Anfix\Anfix::$debug = true;

//Función auxiliar para imprimir contenido en la pantalla
function print_result($title,$value,$die = false){
	echo "<div style='color: #6d6d6d; background-color: #eee; font-family: Arial; padding: 5px 0 5px 10px; margin: 5px 0 15px;'>
	<b style='color:#007acc;'>$title</b><pre>".print_r($value,true).'</pre></div>';

	if($die)
		die();
}

//Devuelve el compoanyId de la primera empresa disponible
function firstCompanyId(){
	static $companyId;
	if(empty($companyId))
		$companyId = App\Anfix\Company::firstOrFail([])->CompanyId;
	return $companyId;
}
