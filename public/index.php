<?php

require_once '../src/app.php';

$route = new \Codediesel\Controller\Route();
$main = new \Codediesel\Controller\Main($route);

try {
    $list = include '../src/Config/PageScanner.php';
    foreach ($list as $page) {
        $string = sprintf("\\%s", $page);
        $main->setPage($page::URL , $string);
    }
    $main->init();
} catch (\Twig\Error\LoaderError|\Twig\Error\RuntimeError|\Twig\Error\SyntaxError|\Exception|\Throwable $e) {
    $main->setPage('' , \Codediesel\Pages\Hash\Hash::class);
    $main->error($e);
}