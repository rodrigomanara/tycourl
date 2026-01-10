<?php

namespace Codediesel\Library;

use JetBrains\PhpStorm\NoReturn;
use Twig\Environment;
use \Twig\Loader\FilesystemLoader;

class Views
{

    public Environment $twig;

    public function __construct()
    {

        $files1 = glob(__DIR__ . '/../Pages/Templates');//templates
        $files = glob(__DIR__ . '/../Pages/*/views');
        $files2 = glob(__DIR__ . '/../Pages/*/*/views');

        $combine = array_merge($files, $files1 , $files2);
        $loader = new FilesystemLoader($combine);
        $twig = new Environment($loader);
        $this->twig = $twig;
    }

    /**
     * @param string $path
     * @param array $args
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(string $path, array $args):void
    {
        print($this->twig->render($path, $args));
        exit;
    }

    public function parsed(string $path, array $args):string
    {
        return $this->twig->render($path, $args);
    }

}