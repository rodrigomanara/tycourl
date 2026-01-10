<?php

session_start();
require_once '../src/app.php';


use Codediesel\Library\DataWithHeaderFormatting as DataFormatting;

$route = new \Codediesel\Controller\Route();
$main = new \Codediesel\Controller\RestApi($route);
try {
    $main->init();
}catch (\Exception | Throwable $e){
    $dataFormatting = new DataFormatting();
    $dataFormatting->error([
        'error' => 'Contact Site Administrator',
        'message' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile(),
    ]);
    exit(1);
}
