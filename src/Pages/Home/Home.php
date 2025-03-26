<?php

namespace Codediesel\Pages\Home;

use Codediesel\Pages\Page;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Home extends Page
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function init(): void
    {
        $this->views->render(
            'home.html.twig', [
                'title' => "Home"
            ]
        );
    }
}