<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['archivos']['name'][0])) {
    $root = $_SERVER["DOCUMENT_ROOT"];
    $targetDir = $root.'/Imagenes/egresosCaja/';
    
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $maxFiles = 3;
    $totalFiles = count($_FILES['archivos']['name']);
    $uploadedFiles = 0;
    $maxFileSize = 6 * 1024 * 1024; // 6 MB

    for ($i = 0; $i < $totalFiles; $i++) {
        if ($_FILES['archivos']['size'][$i] <= $maxFileSize) {
            $timestamp = strval(round(microtime(true) * 1000));  
            $originalName = $_FILES['archivos']['name'][$i];
            $newName = pathinfo($originalName, PATHINFO_FILENAME) . "_" . $i . "_" . $timestamp . '.jpg';
            $targetFile = $targetDir . basename($newName);
            
            $imageFileType = exif_imagetype($_FILES['archivos']['tmp_name'][$i]);
            $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
            
            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['archivos']['tmp_name'][$i], $targetFile)) {
                    // Redimensionar y convertir a JPEG si es necesario
                    list($originalWidth, $originalHeight) = getimagesize($targetFile);
                    
                    if ($originalWidth > 800 || $originalHeight > 600) {
                        $newWidth = 960;
                        $newHeight = 1360;

                        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                        
                        switch ($imageFileType) {
                            case IMAGETYPE_JPEG:
                                $sourceImage = imagecreatefromjpeg($targetFile);
                                break;
                            case IMAGETYPE_PNG:
                                $sourceImage = imagecreatefrompng($targetFile);
                                break;
                            case IMAGETYPE_GIF:
                                $sourceImage = imagecreatefromgif($targetFile);
                                break;
                        }

                        imagecopyresampled(
                            $resizedImage,
                            $sourceImage,
                            0, 0, 0, 0,
                            $newWidth,
                            $newHeight,
                            $originalWidth,
                            $originalHeight
                        );

                        imagejpeg($resizedImage, $targetFile, 90);
                        imagedestroy($sourceImage);
                        imagedestroy($resizedImage);
                    }

                    $uploadedFiles++;
                } else {
                    echo "Error: No se pudo mover el archivo $originalName.<br>";
                }
            } else {
                echo "Error: El archivo $originalName no es una imagen válida.<br>";
            }
        } else {
            $tamaño = round($_FILES['archivos']['size'][$i] / (1024 * 1024), 2);
            echo "Error: El archivo {$_FILES['archivos']['name'][$i]} excede el tamaño máximo permitido (6 MB). Tamaño del archivo: $tamaño MB.<br>";
        }
    }

    echo "Se subieron $uploadedFiles archivos correctamente.";
} else {
    echo "Error: No se seleccionaron archivos o se produjo un error en la carga.";
}
?>