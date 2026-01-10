<?php

namespace Codediesel\Pages\StaticPages;

use Codediesel\Pages\Page;

class Home extends Page
{

    const URL = '/';

    /**
     * @return void
     */
    public function init(): void
    {
        $this->render('home.twig', [
            'title' => 'Home',
        ]);
    }
}