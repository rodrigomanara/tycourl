<?php

namespace Codediesel\Pages\Register;

use Codediesel\Pages\Page;

class Register extends Page
{

    public function init(): void
    {

        $this->views->render(
            'register.html.twig', [
                'title' => "Login"
            ]
        );
    }
}