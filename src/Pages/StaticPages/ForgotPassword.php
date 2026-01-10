<?php

namespace Codediesel\Pages\StaticPages;

use Codediesel\Pages\Page;

class ForgotPassword extends Page
{

    const URL = '/forgot-password';
    /**
     * @return void
     */
    public function init(): void
    {
        $this->render('forgot-password.twig', [
            'title' => 'Forgot Password',
        ]);
    }
}