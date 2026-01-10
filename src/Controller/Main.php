<?php

namespace Codediesel\Controller;
use Codediesel\Library\Pages;
use Codediesel\Pages\Error\Error;

class Main extends Pages
{
    public function error(\Twig\Error\SyntaxError
                          |\Throwable
                          |\Exception
                          |\Twig\Error\RuntimeError
                          |\Twig\Error\LoaderError $e): void
    {


        $main = new Error();
        $main->setMessage($e->getMessage());
        $main->init();
    }


}