<?php

$direccion = 'PERU 960 18 A';
$localidad = 'EL TALAR';
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
    print_r($obj->results[0]);
    die();
}
if (isset($obj->results[0]->address_components)) {
        foreach($obj->results[0]->address_components as $component){
            $string = $component->types [0];
            switch ($string){
                case 'street_number': 
                    echo 'Numero de calle: ';
                    echo isset($component->long_name)?$component->long_name:'';
                    echo '<br/>';
                    break;
                case 'route': 
                    echo 'Nombre de calle: ';
                    echo isset($component->long_name)?$component->long_name:'';
                    echo '<br/>';
                    break;
                case 'locality': 
                    echo 'Localidad: ';
                    echo isset($component->long_name)?$component->long_name:'';
                    echo '<br/>';
                    break;
                case 'administrative_area_level_2': 
                    echo 'Partido: ';
                    echo isset($component->long_name)?$component->long_name:'';
                    echo '<br/>';
                    break;
                case 'administrative_area_level_1': 
                    echo 'Provincia: ';
                    echo isset($component->long_name)?$component->long_name:'';
                    echo '<br/>';
                    break;
                case 'country': 
                    echo 'Pais: ';
                    echo isset($component->long_name)?$component->long_name:'';
                    echo '<br/>';
                    break;
            }
        }
        //print_r($obj->results[0]->address_components);
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
