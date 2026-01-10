<?php

namespace Codediesel\Pages\Hashing;

use Codediesel\Pages\Page;

class Links extends Page
{

    const URL = '/links';

    /**
     * @return void
     */
    public function init(): void
    {
        $this->render('links.twig', [
            'title' => 'Links without QR Code',
        ]);
    }
}