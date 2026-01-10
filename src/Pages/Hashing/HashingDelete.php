<?php

namespace Codediesel\Pages\Hashing;

use Codediesel\Pages\Page;

class HashingDelete extends Page
{
    const URL = '/hashing/delete/{id}';

    /**
     * @return void
     */
    public function init(): void
    {
        $url = $this->route->get('ref');
        $this->render('delete.twig', [
            'title' => sprintf('Delete Hashing %s', $this->route->get('id')),
            'id' => $this->route->get('id'),
            'url_origin' => $url ,
            'hash' => $this->route->get('hash'),
        ]);
    }
}