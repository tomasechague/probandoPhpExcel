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
    CURLOPT_URL => 'https://es.wikipedia.org/wiki/N%C3%BAmeros_telef%C3%B3nicos_en_Argentina',
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
        ->setCellValue('B1', 'Provincia')
        ->setCellValue('C1', 'Localidad');
/* * ******************************* */


//print_r($response);
$doc = new DOMDocument();
@$doc->loadHTML($response);
$options = $doc->getElementsByTagName('table');
//Establezco i en 1 para recorrer los objetos DOM
$i = 1;
//Establezco a J en 2 para empezar a escribir debajo de las cabeceras
$j = 2;
foreach ($options as $option) {
    //Como son 3 tablas, obtengo la tercera que se que esta todo completo
    if ($i == 3) {
        //obtengo las filas de la tabla que necesito
        $trs = $option->getElementsByTagName('tr');
        //Recorro las filas
        foreach ($trs as $tr) {
            //obtengo las columnas de una fila
            $tds = $tr->getElementsByTagName('td');
            //Si la columna tiene algo entonces la trabajo, sino no
            if (isset($tds->item(0)->nodeValue)) {
                $codigoArea = $tds->item(0)->nodeValue; //codigo de area
                $provincia = $tds->item(1)->nodeValue; //provincia
                //Separo las localidades en un array ya que estan escritas con ','
                $localidades = explode(',', $tds->item(2)->nodeValue); //localidades
                //Recorro todas las localidades que obtuve para ese codigo de area
                foreach ($localidades as $localidad) {
                    /* ESCRIBO EL EXCEL */
                    $objPHPExcel->setActiveSheetIndex(0);
                    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $codigoArea);
                    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $j, $provincia);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $j, $localidad);
                    /***************** */
                    $j++;
                }
            }
        }
    }
    $i++;
}

$objPHPExcel->getActiveSheet()->setTitle('Hoja Nº1');
/* DESCARGO EL EXCEL */
//echo date('H:i:s') . " Write to Excel2007 format\n";
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//$objWriter->save('codAreaLocalidades.xlsx');
/* * ******************************************* */
/* Lo redirijo al navegador */
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="probandoLocalidadesWiki.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
/****************************/