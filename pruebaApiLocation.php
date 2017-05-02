<?php

$direccion = 'AYACUCHO 3473';
$localidad = 'LANUS ESTE';
$pais = 'Argentina';
$direccion .= ' ' . $localidad . ' ' . $pais;

$direccion = str_replace('calle', '', $direccion);
$direccion = str_replace('CALLE', '', $direccion);
$direccion = str_replace(' ', '%20', $direccion);
//echo $direccion . '<br/>';


/*
 * $obj->address_components[0]->long_name=>numero de calle
 * $obj->address_components[1]->long_name=>nombre de calle
 * $obj->address_components[2]->long_name=>partido
 * $obj->address_components[3]->long_name=>localidad
 * $obj->address_components[4]->long_name=>provincia
 * $obj->address_components[5]->long_name=>pais
 * $obj->address_components[6]->long_name=>codigo postal
 * $obj->results[n]->formatted_address=> la calle completa
 *  */



$json = @file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=Calle%20' . $direccion);
$obj = json_decode($json);
//print_r($obj->results[0]->address_components[0]->long_name);
//die();
if (isset($obj->results[0])) {
    
        print_r($obj->results);
} else {
    echo "no se encontraron resultados";
}

//foreach($obj as $result){
//echo ($result[0]->formatted_address);
//}

function stdArrayAsArray($objectStd) {
    foreach ($sa as $key => $value) {
        $stdArray[$key] = (array) $value;
    }
    /*     * * show the results ** */
    print_r($stdArray);
}
