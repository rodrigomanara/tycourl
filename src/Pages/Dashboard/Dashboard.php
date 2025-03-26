<?php

namespace Codediesel\Pages\Dashboard;

use Codediesel\Pages\Page;

class Dashboard extends Page
{

    /**
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function init(): void
    {
        $this->views->render(
            'dashboard.html.twig', [
                'title' => "Home"
            ]
        );
    }
}