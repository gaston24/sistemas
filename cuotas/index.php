<?php
require 'MODEL/conexion.php';
require 'controller/InfoLocales.php';

$conexion=new Conexion();
$conexion->infoSucursales();