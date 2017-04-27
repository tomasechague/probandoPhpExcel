<?php
 header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . DIRECTORY_SEPARATOR . 'PHPExcel-1.8' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel.php';


/* Carga del archivo */
$inputfilename = 'dat.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputfilename);
/* Fin carga del archivo */

/* Obtengo la hoja activa */
$sheetData = $objPHPExcel->getActiveSheet();

/* Obtengo el mayor numero de filas que hay en el archivo */
$highestRow = $sheetData->getHighestRow();

/* Recorro la columna J que seria la de los telefonos */
for ($row = 2; $row <= $highestRow; ++$row) {
    $telCell = $sheetData->getCell('J' . $row)->getFormattedValue();
    $cpCell = $sheetData->getCell('K' . $row)->getValue();

    //Me fijo si esta separado por '-'
    $telCell = (string) $telCell;
    $hayGuion = strrpos('-', $telCell);

    //No encontre la separacion entre el codigo de area y el telefono
    //Quiere decir que solo me mando el telefono
    if ($hayGuion === FALSE) {
        $telefono = $telCell;
        $codigoDeArea = '';
        //El codigo de area y el telefono estan separados por '-'    
    } else {
        $separacion = explode('-', $telCell);
        $codigoDeArea = $separacion[0];
        $telefono = $separacion[1];
    }
    echo '-----------------------------<br/>';
    echo  'Telefono: ' . $telefono . '<br/> Codigo postal: ' . $cpCell . '<br/>';
    echo '-----------------------------<br/>';
}




//Si no tiene telefono se puede probar ir a buscarlo con el documento a CCM_Sistemas
?>

