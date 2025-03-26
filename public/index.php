<?php

require_once '../src/app.php';

$route = new \Codediesel\Controller\Route();
$main = new \Codediesel\Controller\Main($route);
try {
    $main->init();
} catch (\Twig\Error\LoaderError|\Twig\Error\RuntimeError|\Twig\Error\SyntaxError $e) {
    dump($e->getMessage());
}