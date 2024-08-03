<?php
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'uploadedFiles' => 0,
    'errors' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['archivos']['name'][0])) {
    $root = $_SERVER["DOCUMENT_ROOT"];
    $targetDir = $root . '/Imagenes/egresosCaja/';
    
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
            $timestamp = strval(round(microtime(true) * 1000));  
            $newName = $_FILES['archivos']['name'][$i] . "_" . $i . "_" . $timestamp . '.jpg';
            $targetFile = $targetDir . basename($newName);
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Permitir solo ciertos tipos de archivos
            $allowedTypes = array('pdf', 'jpg', 'png');
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['archivos']['tmp_name'][$i], $targetFile)) {
                    // Obtener las dimensiones originales de la imagen
                    list($originalWidth, $originalHeight) = getimagesize($targetFile);
                    
                    if ($originalWidth > 800 || $originalHeight > 600) {
                        $newWidth = 960;
                        $newHeight = 1360;

                        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                        $sourceImage = imagecreatefromjpeg($targetFile);

                        imagecopyresized(
                            $resizedImage,
                            $sourceImage,
                            0, 0, 0, 0,
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
                } else {
                    $response['errors'][] = "Error al mover el archivo $i.";
                }
            } else {
                $response['errors'][] = "Tipo de archivo no permitido para el archivo $i.";
            }
        } else {
            $tamaño = round($_FILES['archivos']['size'][$i] / (1024 * 1024), 2);
            $response['errors'][] = "El archivo $i excede el tamaño máximo permitido (6 MB). Tamaño del archivo: $tamaño MB.";
        }
    }

    $response['success'] = $uploadedFiles > 0;
    $response['message'] = $uploadedFiles > 0 ? "$uploadedFiles archivos cargados correctamente." : "No se pudo subir ningún archivo.";
    $response['uploadedFiles'] = $uploadedFiles;

} else {
    $response['message'] = "Error: No se seleccionaron archivos o se produjo un error en la carga.";
}

echo json_encode($response);
?>