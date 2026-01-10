<?php

namespace Codediesel\Pages\Error;
use AllowDynamicProperties;
use Codediesel\Pages\Page;

#[AllowDynamicProperties]
class Error extends Page
{


    public function init(): void
    {
        $this->render('error.twig', [
            'error' => $this->message,
        ]);
    }

    public function setMessage(string $getMessage)
    {
        $this->message = $getMessage;
    }
}