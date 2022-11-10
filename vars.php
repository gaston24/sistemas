<?php

require_once('classEnv.php');

use DevCoder\DotEnv;

(new DotEnv(__DIR__ . '/.env'))->load();

$HOST_CENTRAL = getenv('HOST_CENTRAL');
$HOST_LOCALES = getenv('HOST_LOCALES');
$DATABASE_CENTRAL = getenv('DATABASE_CENTRAL');
$DATABASE_LOCALES = getenv('DATABASE_LOCALES');
$USER = getenv('USER');
$PASS = getenv('PASS');
$PASS_LOCALES = getenv('PASS_LOCALES');
$CHARACTER = getenv('CHARACTER');

