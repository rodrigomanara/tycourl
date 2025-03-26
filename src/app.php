<?php

require_once 'vendor/autoload.php';
use Symfony\Component\Dotenv\Dotenv;

const __ROOT__ = __DIR__;

define("__PAGE__" , sprintf("%s/Pages" , __ROOT__));
define("__KEYS__" , sprintf("%s/keys" , __ROOT__));


$dotenv = new Dotenv();
$dotenv->load(__ROOT__.'/.env');