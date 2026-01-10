<?php

namespace Codediesel\Pages\Hashing;

use Codediesel\Pages\Page;

class Hashing extends Page
{

    const URL = '/create-new-url';

    /**
     * @return void
     */
    public function init(): void
    {
        $this->render('create.twig', [
            'title' => 'Home',
        ]);
    }
}