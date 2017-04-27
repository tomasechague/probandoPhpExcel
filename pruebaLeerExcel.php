<?php

/* Uso Excel para exportar lo obtenido */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'Reader' . DIRECTORY_SEPARATOR . 'Excel2007.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'Writer' . DIRECTORY_SEPARATOR . 'Excel2007.php';


$excelReader = new PHPExcel_Reader_Excel2007();
$objPHPExcel = $excelReader->load('Excels/probandoApiLocation.xlsx');
/* * ***** */