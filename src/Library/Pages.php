<?php

namespace Codediesel\Library;

use Codediesel\Controller\Route;

class Pages
{
    public Route $route;
    private array $pages;
    private array $request;

    /**
     * Constructor
     *
     * Initializes the Pages class with the given Route instance.
     * It also sets up the request data and the list of available pages.
     *
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
        $this->request = $this->route->request();
        $this->pages = [
            \Codediesel\Pages\Hash\Hash::class,
            \Codediesel\Pages\Home\Home::class,
            \Codediesel\Pages\Login\Login::class,
            \Codediesel\Pages\Register\Register::class,
            //            \Codediesel\Pages\Dash::class,
            \Codediesel\Pages\Error\Error::class,
        ];
    }

    /**
     * Initialize the Pages
     *
     * This method initializes the Pages by finding the current page
     * and invoking its init method. It handles any exceptions that may occur.
     *
     * @return void
     */
    public function init(): void
    {
        try {
            $current = $this->find();
            $class = new $current();
            $class?->init();
        } catch (\Throwable|\Exception $e) {
            dd($e->getMessage(), $e->getLine());
        }
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
        if (isset($this->request['page'])) {
            $current = $this->grep($this->request['page']);
            if ($current === false) {
                $current = $this->grep('hash');
            }
            return $current;
        } else {
            return $this->grep('home');
        }
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
}