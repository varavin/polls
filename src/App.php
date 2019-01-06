<?php

namespace Polls;

use Polls\Controllers\APIController;
use Polls\Controllers\Controller;
use Polls\Controllers\SiteController;

class App
{
    private $config = [];
    private $pdo = null;
    private $payload = [];

    const VIEWS_DIR = __DIR__ . '/../views/';

    public function __construct()
    {
        $this->loadConfig();
        $host = 'mysql:host=' . $this->getConfigVar(['db', 'host']) . ';dbname=' . $this->getConfigVar(['db', 'database']);
        $user = $this->getConfigVar(['db', 'user']);
        $pass = $this->getConfigVar(['db', 'password']);
        $this->pdo = new \PDO($host, $user, $pass);
    }

    public function run()
    {
        $router = new Router();
        $chunks = $router->getChunks();
        $method = $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $controller = new Controller($this);
        $action = '';

        if (count($chunks) === 0) {
            $controller = new SiteController($this);
            $action = 'index';
        }

        if ($chunks[0] === 'api') {
            if ($method === 'POST') {
                $this->payload = json_decode(file_get_contents('php://input'), true);
            } else {
                $this->payload = array_slice($chunks, 2);
            }
            $APIMap = $router->getAPIMap();
            $controller = new APIController($this);
            $action = isset($APIMap[$method][$chunks[1]]) ? $APIMap[$method][$chunks[1]] : false;
        }

        if (!$action || !method_exists($controller, $action)) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $content = $controller->$action();

        if ($controller instanceof APIController) {
            header('Content-Type: application/json');
            echo $content;
            return true;
        }

        echo $this->renderView('layout', compact('content'));
    }

    private function loadConfig()
    {
        $env = 'production';
        if (file_exists(__DIR__ . '/../config/config.local.php')) {
            $env = 'local';
        } else if (isset($_SERVER['APP_ENV']) && in_array($_SERVER['APP_ENV'], ['staging', 'local'])) {
            $env = $_SERVER['APP_ENV'];
        }
        require_once(__DIR__ . '/../config/config.' . $env . '.php');
    }

    public function getConfigVar($var = null)
    {
        $path = is_array($var) ? $var : [$var];
        $arr = $this->config;
        foreach ($path as $key) {
            if (array_key_exists($key, $arr)) {
                $arr = $arr[$key];
            } else {
                $arr = false;
                break;
            }
        }
        return $arr;
    }

    public function pdo()
    {
        return $this->pdo;
    }

    public function payload()
    {
        return $this->payload;
    }

    public function renderView($view, $variables = array())
    {
        $viewFile = self::VIEWS_DIR . $view . '.php';
        $output = null;
        if(file_exists($viewFile)){
            extract($variables);
            ob_start();
            require_once($viewFile);
            $output = ob_get_clean();
        }
        return $output;
    }

}
