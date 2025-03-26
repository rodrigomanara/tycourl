<?php

namespace Codediesel\Library;

abstract class HashURL
{
    /**
     * @param string $url
     * @param int $length
     * @return string
     */
    public static function generateHash(string $url , int $length = 6): string
    {
        $hash = sha1($url); // Generate SHA-1 hash
        return substr(base_convert($hash, 16, 36), 0, $length);
    }
}