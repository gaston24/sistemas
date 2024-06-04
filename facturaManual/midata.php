<?php
// config.php

// Obtén los valores de usuario y contraseña desde el archivo
$config = [
    'username' => 'eduardob',
    'password' => 'Edu@rdo85',
];

// Convierte los valores a formato JSON
$json_response = json_encode($config);

// Devuelve la respuesta al cliente
echo $json_response;
