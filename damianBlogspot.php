<?php

error_reporting(E_ALL);
/* Uso Excel para exportar lo obtenido */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'Writer' . DIRECTORY_SEPARATOR . 'Excel2007.php';
$objPHPExcel = new PHPExcel();
/* * ***** */

/* OBTENCION DE DATOS USANDO CURL */
$fp = fopen('cookie.txt', 'w');
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://damian-serviciotecnico.blogspot.com.ar/2011/12/codigos-de-area-interurbanos-de.html',
    //Solucion a problema de certificado SSL
    CURLOPT_SSL_VERIFYPEER => 0,
        )
);

ob_start();
if (!$response = curl_exec($curl)) {
    die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
}
ob_end_clean();
curl_close($curl);
unset($curl);
fclose($fp);
/* TERMINA EL USO DE CURL */

/* SETEO LAS PROPIEDADES DEL EXCEL */
$objPHPExcel->
        getProperties()
        ->setCreator("Tomas Echague")
        ->setLastModifiedBy("Tomas Echague")
        ->setTitle("Codigos de area Argentina")
        ->setDescription("Muestra el codigo de area, localidad y provincia");

/* * ******************************* */

/* ESTABLEZCO LAS CABECERAS QUE VA A UTILIZAR EL EXCEL */
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Codigo Area')
        ->setCellValue('B1', 'Localidad')
        ->setCellValue('C1', 'Provincia');
/* * ******************************* */


/* OBTENGO LOS RESULTADOS Y ESCRIBO EL EXCEL */
$doc = new DOMDocument();
@$doc->loadHTML($response);

$options = $doc->getElementsByTagName('tbody');
$options = $doc->getElementsByTagName('tr');


$i = 0;
//Establezco a J en 2 para empezar a escribir debajo de las cabeceras
$j = 2;
foreach ($options as $option) {
    $i++;
    //evito las dos primeras filas que son las de descripcion de la tabla
    if ($i >= 3) {


        $col = $option->getElementsByTagName('td');

        //En la segunda columna estan los codigos de area
        $codigoArea = isset($col[1]->textContent) ? $col[1]->textContent : '';
        //En la tercer columna estan las localidades
        $localidades = isset($col[2]->textContent) ? $col[2]->textContent : '';
        //En la cuarta columna estan las provincias
        $provincias = isset($col[3]->textContent) ? $col[3]->textContent : '';

        $total[] = array(
            'Codigo de Area' => $codigoArea,
            'Localidad' => $localidades,
            'Provincia' => $provincias,
        );


        /* ESCRIBO EL EXCEL */
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $codigoArea);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $localidades);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $provincias);
        /*         * *************** */
        $j++;
    }
}

var_dump($total);


$objPHPExcel->getActiveSheet()->setTitle('Hoja Nº1');
/* DESCARGO EL EXCEL */
//echo date('H:i:s') . " Write to Excel2007 format\n";
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//$objWriter->save('codAreaLocalidades.xlsx');
/**********************************************/
/*Lo redirijo al navegador */
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="buenas.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
/****************************/