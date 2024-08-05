<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'uploadedFiles' => 0,
    'errors' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['archivos']['name'][0])) {
    $root = $_SERVER["DOCUMENT_ROOT"];
    $targetDir = $root.'/Imagenes/egresosCaja/';
    
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $maxFiles = 3;
    $totalFiles = count($_FILES['archivos']['name']);
    $uploadedFiles = 0;
    $maxFileSize = 6 * 1024 * 1024; // 6 MB en bytes

    for ($i = 0; $i < $totalFiles; $i++) {
        if ($_FILES['archivos']['size'][$i] <= $maxFileSize) {
            $originalName = $_FILES['archivos']['name'][$i];
            $fileInfo = pathinfo($originalName);
            $newName = $fileInfo['filename'] . '.jpg'; // Aseguramos que la extensión sea .jpg
            $targetFile = $targetDir . $newName;
            $imageFileType = strtolower($fileInfo['extension']);

            $allowedTypes = array('jpg', 'jpeg', 'png');
            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['archivos']['tmp_name'][$i], $targetFile)) {
                    // Convertir a JPEG si es necesario
                    $image = null;
                    switch($imageFileType) {
                        case 'jpg':
                        case 'jpeg':
                            $image = imagecreatefromjpeg($targetFile);
                            break;
                        case 'png':
                            $image = imagecreatefrompng($targetFile);
                            break;
                    }

                    if ($image) {
                        list($originalWidth, $originalHeight) = getimagesize($targetFile);
                        $newWidth = 960;
                        $newHeight = 1360;

                        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

                        imagejpeg($resizedImage, $targetFile, 90);
                        imagedestroy($image);
                        imagedestroy($resizedImage);

                        $uploadedFiles++;
                    } else {
                        $response['errors'][] = "No se pudo procesar la imagen: " . $newName;
                    }
                } else {
                    $response['errors'][] = "No se pudo mover el archivo: " . $newName;
                }
            } else {
                $response['errors'][] = "Tipo de archivo no permitido: " . $imageFileType;
            }
        } else {
            $size = round($_FILES['archivos']['size'][$i] / (1024 * 1024), 2);
            $response['errors'][] = "El archivo excede el tamaño máximo permitido (6 MB). Tamaño del archivo: " . $size . " MB.";
        }
    }

    $response['success'] = $uploadedFiles > 0;
    $response['message'] = "$uploadedFiles imagen cargada correctamente.";
    $response['uploadedFiles'] = $uploadedFiles;
} else {
    $response['message'] = "Error: No se seleccionaron archivos o se produjo un error en la carga.";
}

echo json_encode($response);
?>