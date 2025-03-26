<?php

require_once '../src/app.php';


$route = new \Codediesel\Controller\Route();
$main = new \Codediesel\Controller\RestApi($route);
try {
    $main->init();
} catch (\Twig\Error\LoaderError $e) {
    dump($e->getMessage());
} catch (\Twig\Error\RuntimeError $e) {
    dump($e->getMessage());
} catch (\Twig\Error\SyntaxError $e) {
    dump($e->getMessage());
}
