<?php

set_time_limit(1200);

/* Uso Excel para exportar lo obtenido */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'Reader' . DIRECTORY_SEPARATOR . 'Excel2007.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'Writer' . DIRECTORY_SEPARATOR . 'Excel2007.php';


$excelReader = new PHPExcel_Reader_Excel2007();
$objPHPExcel = $excelReader->load('Excels/probandoApiLocation.xlsx');
/* * ***** */

//Obtengo la hoja activa
$objWorksheet = $objPHPExcel->getActiveSheet();
//Obtengo el mayor numero de fila en la que se hace referencia
$highestRow = $objWorksheet->getHighestRow();

//Obtengo el mayor numero de columna en la que se hace referencia
$highestColumn = $objWorksheet->getHighestColumn();


//Seteo los encabezados de la informacion a agregar
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Calle_numero');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Calle_nombre');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Partido');
$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Localidad');
$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Provincia');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Pais');
$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Codigo_postal');
$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Direccion_completa');


for ($i = 2; $i <= $highestRow + 1; $i++) {
    //Obtengo los valores de la columna domicilio y localidad del Excel
    $domicilio = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getValue();
    $localidad = ($objPHPExcel->getActiveSheet()->getCell('I' . $i)->getValue()) ? $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getValue() : '';
    $pais = 'Argentina';

    //Proceso domicilio, localidad y pais para que quede una URL coherente
    $stringUrl = $domicilio . ' ' . $localidad . ' ' . $pais;
    $stringUrl = str_replace(' ', '%20', $stringUrl);

    $json = @file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=Calle%20' . $stringUrl);
    if ($obj = @json_decode($json)) {
        if (isset($obj->results[0])) {
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, isset($obj->results[0]->address_components[0]->long_name) ? $obj->results[0]->address_components[0]->long_name : '');
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, isset($obj->results[0]->address_components[1]->long_name) ? $obj->results[0]->address_components[1]->long_name : '');
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, isset($obj->results[0]->address_components[2]->long_name) ? $obj->results[0]->address_components[2]->long_name : '');
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, isset($obj->results[0]->address_components[3]->long_name) ? $obj->results[0]->address_components[3]->long_name : '');
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, isset($obj->results[0]->address_components[4]->long_name) ? $obj->results[0]->address_components[4]->long_name : '');
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, isset($obj->results[0]->address_components[5]->long_name) ? $obj->results[0]->address_components[5]->long_name : '');
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, isset($obj->results[0]->address_components[6]->long_name) ? $obj->results[0]->address_components[6]->long_name : '');
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, isset($obj->results[0]->formatted_address) ? $obj->results[0]->formatted_address : '');
        }
    }


    //Vacio las variables
    $domicilio = '';
    $localidad = '';
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="CajaPoliciaProcesado.xlsx"');
header('Cache-Control: max-age=0');

@$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
@$objWriter->save('php://output');
