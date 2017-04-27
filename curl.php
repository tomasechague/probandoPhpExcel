<?php


$fp = fopen('cookie.txt', 'w');
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://localhost/pruebaExcel/tabla.php',
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
//print_r($response);

$doc = new DOMDocument();
@$doc->loadHTML($response);

$options = $doc->getElementsByTagName('tbody');
$options = $doc->getElementsByTagName('tr');

//var_dump($options);
foreach($options as $option){
    var_dump($option);
    $valores = $option->nodeValue;
    //Obtengo codigo de area
    $codigoArea = explode(" ", $valores);
    $codigoArea = $codigoArea[0];
    $codigoArea = preg_replace("/[^0-9]/", "",$codigoArea);
    //Obtengo localidades para ese codigo de area
    $localidades = explode(',', $valores);
    $localidades = preg_replace("/[^A-Za-záéíóúÁÉÍÓÚ]/", " ",$localidades);
    //var_dump($localidades);
    $conjuntos[]=array(
        'Codigo_de_Area' => $codigoArea,
        'Localidad'=> $localidades,
    );    
    
    //print_r($option);
}
//var_dump($conjuntos);



//Exportacion a Excel

//require_once __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel.php';
// 
// $objPHPExcel = new PHPExcel();
// 
////Establecemos las cabeceras para un archivo xls
//header('Content-type: application/vnd.ms-excel');
//		header("Content-Disposition: attachment; filename=excelenphp.xls");
//		header("Pragma: no-cache");
//		header("Expires: 0");

                
                
