<?php

namespace Codediesel\Library;

use Codediesel\Controller\Route;
use PharIo\GnuPG\Exception;

class Pages
{
    private array $pages;
    private array $request;

    /**
     * @param Route $route
     */
    public function __construct(private readonly Route $route)
    {
        $this->request = $this->route->request();
    }

    /**
     * Initialize the Pages
     *
     * This method initializes the Pages by finding the current page
     * and invoking its init method. It handles any exceptions that may occur.
     *
     * @return void
     * @throws Exception
     */
    public function init(): void
    {
        foreach ($this->getPages() as $uri => $class) {
            if ($this->runPage($uri, $class))
                return;
        }

        //ensure run the check as well
        throw new Exception("Page Not Found");
    }

    /**
     * @param string $uri
     * @param string $class
     * @return void
     * @throws Exception
     */
    private function runPage(string $uri, string $class): bool
    {

        //check if uri is patten
        if (str_contains($uri, '|')) {
            $uris = explode('|', $uri);
            foreach ($uris as $string) {
                if ($this->route->isMatch($string) && class_exists($class)) {
                    $execute = new $class($this->request);
                    $execute->init();
                    return true;
                }
            }
        }

        if ($this->route->isMatch($uri)) {
            $execute = new $class($this->request);
            $execute->init();
            return true;
        }
        try {
            //replace
            if (!(str_contains($uri, '{id}') or str_contains($uri, '{hashed}'))) return false;
            $page = $this->route->get('id') ?? $this->route->get('page');
            if (!$page) return false;

            $url = preg_replace('/{hashed}|{id}/', $page, $uri);
            if (!$this->route->isMatch($url)) return false;

            $execute = new $class($this->request);
            $execute->init();

        } catch (\Throwable $e) {
            return false;
        }

        return false;
    }

    /**
     * Find the current page
     *
     * This method finds the current page based on the request data.
     * If the 'page' parameter is not set, it defaults to the 'home' page.
     *
     * @return mixed The class name of the current page
     */
    private function find(): mixed
    {
        return '';
    }

    /**
     * Search for a page in the list of pages
     *
     * This method searches for a page in the list of available pages
     * using a case-insensitive regular expression match.
     *
     * @param string $page The name of the page to search for
     * @return false|mixed The class name of the matched page or false if not found
     */
    private function grep(string $page): mixed
    {
        $array = (preg_grep("/$page/i", $this->pages));
        return current($array);
    }

    /**
     * @param array $page
     * @return void
     */
    public function setPage(string $url, string $class): self
    {
        $this->pages[$url] = $class;
        return $this;
    }

    /**
     * @return array
     */
    public function getPages(): array
    {
        return $this->pages;
    }
}