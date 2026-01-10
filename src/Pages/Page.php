<?php

namespace Codediesel\Pages;

use Codediesel\Controller\Route;
use Codediesel\Library\Views;
use Couchbase\View;

abstract class Page
{

    const URL = '';
    protected Views $views;
    protected Route $route;

    public function __construct()
    {
        $this->views = new Views();
        $this->route = new Route();
    }

    abstract public function init() : void;


    protected function render(string $path , array $params = []) : void {

        $params = array_merge($params, [
            'TicoUrl' => 'TicoUrl'
        ]);
        $this->views->render($path, $params);
    }
}