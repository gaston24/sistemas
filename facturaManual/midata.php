<?php
// config.php

// Obtén los valores de usuario y contraseña desde el archivo
$config = [
    'username' => 'eduardob',
    'password' => 'extra,123',
    'urlPathTest' => 'http://127.0.0.1:8000',
    'urlPath' => 'http://app.xl.com.ar:8000',
];

// Convierte los valores a formato JSON
$json_response = json_encode($config);

// Devuelve la respuesta al cliente
echo $json_response;
