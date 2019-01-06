<?php

namespace Polls\Controllers;

use Polls\App;

/**
 * Class Controller
 * @package Polls\Controllers
 * @property App $app
 */
class Controller
{
    public $app = null;

    public function __construct(App $app)
    {
        $this->app = $app;
    }
}