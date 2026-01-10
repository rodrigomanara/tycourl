<?php

namespace Codediesel\Pages\StaticPages;

use Codediesel\Pages\Page;

class Logout extends Page
{

    const URL = '/logout';
    public function init(): void
    {
        $this->render('logout.twig', [
            'title' => 'Logout',
        ]);
    }
}