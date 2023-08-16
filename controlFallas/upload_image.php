<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['archivos']['name'][0])) {

    // Directorio donde se guardarán los archivos subidos
    $targetDir = 'assets/uploads/';

    // Crear el directorio si no existe
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $maxFiles = 3;
    $totalFiles = count($_FILES['archivos']['name']);

    $uploadedFiles = 0;
  
    for ($i = 0; $i < $totalFiles; $i++) {

        // Obtener el timestamp actual en milisegundos
        $timestamp = strval(round(microtime(true) * 1000));  

        $newName =  $_FILES['archivos']['name'][$i]."_".$i."_".$timestamp.'.jpg';

        $targetFile = $targetDir . basename($newName);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Permitir solo ciertos tipos de archivos
        $allowedTypes = array('pdf', 'jpg', 'png');
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['archivos']['tmp_name'][$i], $targetFile)) {
                
                 // Obtener las dimensiones originales de la imagen
                 list($originalWidth, $originalHeight) = getimagesize($targetFile);
                
                 if ($originalWidth > 800 || $originalHeight > 600) {
                     $newWidth = 800;
                     $newHeight = 600;
 
                     $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                     $sourceImage = imagecreatefromjpeg($targetFile); // Cambia a la función adecuada según el tipo de imagen
 
                     imagecopyresized(
                         $resizedImage,
                         $sourceImage,
                         0,
                         0,
                         0,
                         0,
                         $newWidth,
                         $newHeight,
                         $originalWidth,
                         $originalHeight
                     );
 
                     imagejpeg($resizedImage, $targetFile);
 
                     imagedestroy($sourceImage);
                     imagedestroy($resizedImage);
                 }
                $uploadedFiles++;
            }
        }
    }

    echo "Se subieron $uploadedFiles archivos correctamente.";
} else {
    echo "Error: No se seleccionaron archivos o se produjo un error en la carga.";
}
?>