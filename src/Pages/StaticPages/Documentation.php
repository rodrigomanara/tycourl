<?php

namespace Codediesel\Pages\StaticPages;

use Codediesel\Pages\Page;

class Documentation extends Page
{

    const URL = '/api-documentation';

    /**
     * @return void
     */
    public function init(): void
    {

      dd($this->route->getUserSessionID());

        $this->render('documentation.twig', [
            'title' => 'Api Documentation',
            'isLogged' => $this->route->getUserSessionID() !== null,
        ]);
    }
}