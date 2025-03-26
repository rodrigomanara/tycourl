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
        $files = glob(__DIR__ . '/../Pages/*/views');
        $files1 = glob(__DIR__ . '/../Pages/Views');

        $combine = array_merge($files, $files1);

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

}