<?php

namespace Codediesel\Pages\Dashboard;

use Codediesel\Pages\Page;

class Dashboard extends Page
{

    const URL = '/dashboard';
    public function init(): void
    {
        //request data
        $this->render('dashboard.twig', [
            'title' => 'Dashboard'
        ]);
    }
}