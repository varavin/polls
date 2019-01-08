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

    public function __construct(\PDO $pdo, array $appConfig = [])
    {
        $this->view = new View();
        $this->pdo = $pdo;
        $this->appConfig = $appConfig;
    }

    public function pdo()
    {
        return $this->pdo;
    }

    public function view()
    {
        return $this->view;
    }

    public function show404()
    {
        header('HTTP/1.0 404 Not Found');
        exit;
    }
}