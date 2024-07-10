<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['archivos']['name'][0])) {
    // Directorio donde se guardarán los archivos subidos
    $root = $_SERVER["DOCUMENT_ROOT"];
    $targetDir = 'assets/uploads/';


    // Crear el directorio si no existe
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $maxFiles = 3;
    $totalFiles = count($_FILES['archivos']['name']);
    $uploadedFiles = 0;

    $maxFileSize = 6 * 1024 * 1024; // 6 MB en bytes


    for ($i = 0; $i < $totalFiles; $i++) {
        // Verificar el tamaño del archivo
        if ($_FILES['archivos']['size'][$i] <= $maxFileSize) {
            // Obtener el timestamp actual en milisegundos
            $timestamp = strval(round(microtime(true) * 1000));
            $newName = $_FILES['archivos']['name'][$i] . "_" . $i . "_" . $timestamp.'.jpg';
            $targetFile = $targetDir . $newName;

            // Permitir solo ciertos tipos de archivos
            $allowedTypes = array('pdf', 'jpg', 'png');
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
   
            if (in_array($fileType, $allowedTypes)) {
             
                if (move_uploaded_file($_FILES['archivos']['tmp_name'][$i], $targetFile)) {
                    // var_dump($_FILES['archivos']['tmp_name'][$i], $targetFile);
                    $exif = exif_read_data($targetFile);
                    if (!empty($exif['Orientation'])) {
                        // Aplicar la rotación según la orientación EXIF sin cambiar dimensiones
                        $sourceImage = imagecreatefromjpeg($targetFile);
                        switch ($exif['Orientation']) {
                            case 3:
                                $sourceImage = imagerotate($sourceImage, 180, 0);
                                break;
                            case 6:
                                $sourceImage = imagerotate($sourceImage, -90, 0);
                                break;
                            case 8:
                                $sourceImage = imagerotate($sourceImage, 90, 0);
                                break;
                        }
                
                        // Guardar la imagen rotada en un archivo temporal
                        $rotatedFile = $targetFile . '_rotated.jpg';
                        imagejpeg($sourceImage, $rotatedFile);
                        imagedestroy($sourceImage);
                
                        // Sobrescribir el archivo original con la imagen rotada
                        rename($rotatedFile, $targetFile);
                    }
                    // Obtener las dimensiones originales de la imagen
                    list($originalWidth, $originalHeight) = getimagesize($targetFile);

                    // Redimensionar solo si es una imagen JPEG
                    if ($fileType === 'jpg') {
                        if ($originalWidth > 800 || $originalHeight > 600) {
                            $newWidth = 960;
                            $newHeight = 1360;

                            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                            $sourceImage = imagecreatefromjpeg($targetFile);

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
                    }

                    $uploadedFiles++;
                }
            } else {
                echo "Error: El archivo no es de un tipo permitido (pdf, jpg, png).";
            }
        } else {
            echo "Error: El archivo excede el tamaño máximo permitido (4 MB).";
        }
    }

    echo "Se subieron $uploadedFiles archivos correctamente.";
} else {
    echo "Error: No se seleccionaron archivos o se produjo un error en la carga.";
}
?>
