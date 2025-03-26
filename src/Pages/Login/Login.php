<?php

namespace Codediesel\Pages\Login;

use Codediesel\Pages\Page;

class Login extends Page
{

    public function init(): void
    {
        $this->views->render(
            'login.html.twig', [
                'title' => "Login"
            ]
        );
    }
}