<?php

error_reporting(E_ALL);

/* Uso Excel para exportar lo obtenido */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'Writer' . DIRECTORY_SEPARATOR . 'Excel2007.php';
$objPHPExcel = new PHPExcel();
/* * ***** */

/*Conexion a la base de datos*/
/****************************/

/*OBTENCION DE LOS DATOS*/
$conn = conectar();

$statement = $conn->prepare('Select * from UIF_Alertas_Personas');
$statement->execute();

$result = $statement->fetchAll();

//var_dump($result);
/***********************/

/* SETEO LAS PROPIEDADES DEL EXCEL */
$objPHPExcel->
        getProperties()
        ->setCreator("Tomas Echague")
        ->setLastModifiedBy("Tomas Echague")
        ->setTitle("Prueba para Roberto")
        ->setDescription("Trato de establecer la apariencia de los cuadros que presenta Roberto");

/* * ******************************* */

/* ESTABLEZCO LAS CABECERAS QUE VA A UTILIZAR EL EXCEL */
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Codigo Persona')
        ->setCellValue('B1', 'Fecha')
        ->setCellValue('C1', 'Comentario');
/* * ******************************* */



/*ESTABLEZCO EL UN ESTILO PREDETERMINADO PARA LAS CABECERAS*/
$styleArray = array(
    'font' => array(
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => 'FFA0A0A0',
        ),
        'endcolor' => array(
            'argb' => 'FFA0A0A0',
        ),
    ),
);

$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);
/************************************/

//Para empezar debajo de las cabeceras
$j=2;
foreach($result as $value){
      /* ESCRIBO EL EXCEL */
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $value['codigo_per']);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $value['fecha']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $value['comentario']);
        /*         * *************** */
        $j++;
}


$styleArray2 = array(
  'borders' => array(
    'outline' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

//Obtengo la hoja activa
$objWorksheet = $objPHPExcel->getActiveSheet();
//Obtengo el mayor numero de fila en la que se hace referencia
$highestRow = $objWorksheet->getHighestRow();

//Obtengo el mayor numero de columna en la que se hace referencia
$highestColumn = $objWorksheet->getHighestColumn();

$objPHPExcel->getActiveSheet ()->getStyle('A1:'.$highestColumn.$highestRow)->applyFromArray($styleArray2);

$objPHPExcel->getActiveSheet()->setTitle('Hoja Nº1');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

/* DESCARGO EL EXCEL */
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//$objWriter->save('alertasPersonas.xlsx');
/**********************************************/

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="newFile.xlsx"');
header('Cache-Control: max-age=0');

@$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
@$objWriter->save('php://output'); 

?>