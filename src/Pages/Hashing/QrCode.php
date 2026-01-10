<?php

namespace Codediesel\Pages\Hashing;

use Codediesel\Pages\Page;

class QrCode extends Page
{

    const URL = '/qr-code';

    /**
     * @return void
     */
    public function init(): void
    {
        $this->render('qr_code.twig', [
            'title' => 'QR Code',
        ]);
    }
}