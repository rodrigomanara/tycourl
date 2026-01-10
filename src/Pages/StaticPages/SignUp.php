<?php

namespace Codediesel\Pages\StaticPages;

use Codediesel\Pages\Page;

class SignUp extends Page
{

    const URL = '/sign-up';

    /**
     * @return void
     */
    public function init(): void
    {
        $this->render('sign_up.twig', [
            'title' => 'Sign Up',
        ]);
    }
}