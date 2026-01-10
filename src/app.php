<?php

require_once __DIR__ . '/vendor/autoload.php';

const __ROOT__ = __DIR__;

use Symfony\Component\Dotenv\Dotenv;

define('ERROR_FILE', __ROOT__ . DIRECTORY_SEPARATOR . 'errors.log');
define("__PAGE__" , sprintf("%s/Pages" , __ROOT__));
define("__KEYS__" , sprintf("%s/keys" , __ROOT__));

$dotenv = new Dotenv();
$dotenv->load(__ROOT__.'/.env');