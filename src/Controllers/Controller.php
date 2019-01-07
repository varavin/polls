<?php

namespace Polls\Controllers;

use Polls\View;

/**
 * Class Controller
 * @package Polls\Controllers
 * @property View $view
 * @property \PDO $pdo
 * @property array $appConfig
 */
class Controller
{
    private $pdo = null;
    private $view = null;
    public $appConfig = [];

    public function __construct(\PDO $pdo, array $config = [])
    {
        $this->view = new View();
        $this->pdo = $pdo;
        $this->appConfig = $config;
    }

    public function pdo()
    {
        return $this->pdo;
    }

    public function view()
    {
        return $this->view;
    }
}