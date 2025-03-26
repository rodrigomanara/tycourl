<?php

namespace Codediesel\Pages\Hash;

use Codediesel\Model\Factory\URL;
use Codediesel\Pages\Page;

class Hash extends Page
{

    public function init(): void
    {
        $route = $this->route->request();
        $url = $route['page'] ?? null;
        $data = (new URL())->fetchUrl($url);

        if (current($data) === false) {
            header("HTTP/1.1 404 Not Found"); // Set the 404 status
            header("location:404.html");
            exit();
        }
        header("location:" . $data['url']);
    }
}