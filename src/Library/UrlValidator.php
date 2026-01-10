<?php

namespace Codediesel\Library;


/**
 * Class UrlValidator
 * @package Codediesel\Library
 */
class UrlValidator
{
    /**
     * Validate a given URL.
     *
     * @param string $url The URL to validate.
     * @return bool True if the URL is valid, false otherwise.
     */
    private static function isValidUrl(string $url): bool
    {
        // Check if the URL is valid using filter_var
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Check if a given URL is reachable.
     *
     * @param string $url The URL to check.
     * @return bool True if the URL is reachable, false otherwise.
     */
    public static function isReachable(string $url): bool
    {

        if(!static::isValidUrl($url))
            return false;

        // Get the headers of the URL
        $headers = @get_headers($url, 1);   
        // Check if the status code is 200
        @preg_match('/\d{3}/', $headers[0] , $numbers);

        $number = $numbers[0] ?? 0;

        if (empty($number)) 
            return false;
        
        if ($number >= 200 && $number < 400) {
            return true; // URL is reachable
        } else {
            return false; // URL is not reachable
        }
        
    }
}