<?php

namespace Codediesel\Pages;

use Codediesel\Controller\Route;
use Codediesel\Library\Views;

abstract class Page
{
    protected Views $views;
    protected Route $route;

    public function __construct()
    {
        $this->views = new Views();
        $this->route = new Route();
    }

    abstract public function init() : void;
}