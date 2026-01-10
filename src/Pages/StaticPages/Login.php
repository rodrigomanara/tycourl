<?php

namespace Codediesel\Pages\StaticPages;

use Codediesel\Pages\Page;

class Login extends Page
{

    const URL = '/login';
    /**
     * @return void
     */
    public function init(): void
    {
        $this->render('login.twig', [
            'title' => 'login',
        ]);
    }
}