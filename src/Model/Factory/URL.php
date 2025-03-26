<?php

namespace Codediesel\Model\Factory;

use Codediesel\Library\DatabaseFactory;
use Codediesel\Library\HashURL;

class URL
{

    use DatabaseFactory;

    const table_name = 'urls';

    public function fetchUrl(string $hash)
    {
        return $this->getInstance()->retrieve(static::table_name , [
           'hash' => $hash
        ]);
    }


    /**
     * @param string $url
     * @return array
     */
    public function createUrl(string $url): array
    {
        return $this->getInstance()->create(static::table_name , [
                    'url' => $url,
                    'hash' => HashURL::generateHash($url , 10)
            ]
        );
    }


}