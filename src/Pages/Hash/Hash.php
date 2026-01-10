<?php

namespace Codediesel\Pages\Hash;

use Codediesel\Model\Factory\URL;
use Codediesel\Pages\Page;
use DateTime;

class Hash extends Page
{
    const URL = '{hashed}';

    /**
     * @throws \Exception
     */
    public function init(): void
    {
        $route = $this->route->request();
        $url = $route['page'] ?? null;
        if(!$url)
            Throw new \Exception("Url not set");

        $data = (new URL())->fetchUrl($url);
        if (current($data) === false) {
            Throw new \Exception("Url not set");
        }
        //Analytics
        $analytics = new \Codediesel\Model\Factory\Analitics();
        $fetch = $analytics->isToday($data['hash']);
        if (empty($fetch)) {
             // Create a new record if it doesn't exist
            $analytics->create($data);
        }else{
            $analytics->update(reset($fetch));
        }
        exit(
            header("location:" . $data['url'])
        );
    }
}