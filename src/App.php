<?php

namespace Polls;

use Polls\Controllers\APIController;

class App
{
    private $config = [];
    private $pdo = null;

    public function __construct()
    {
        $this->loadConfig();
        $this->pdo = new \PDO(
            'mysql:host=' . $this->getConfigVar(['db', 'host']) . ';dbname=' . $this->getConfigVar(['db', 'database']),
            $this->getConfigVar(['db', 'user']),
            $this->getConfigVar(['db', 'password'])
        );
    }

    public function run()
    {
        $router = new Router();
        $chunks = $router->getChunks();
        $method = $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : 'GET';
        if ($chunks[0] === 'api') {
            $APIMap = $router->getAPIMap();
            $payload = [];
            if ($method === 'POST') {
                $payload = json_decode(file_get_contents('php://input'), true);
            }
            $controller = new APIController($this->pdo(), $payload);
            $action = isset($APIMap[$method][$chunks[1]]) ? $APIMap[$method][$chunks[1]] : false;
            if ($action && method_exists($controller, $action)) {
                $controller->$action();
            }
        }
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
}
