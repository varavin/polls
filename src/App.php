<?php

namespace Polls;

use Polls\Controllers\APIController;
use Polls\Controllers\SiteController;

class App
{
    private $config = [];
    private $pdo = null;
    private $payload = [];
    private $jsComponents = [];

    const VIEWS_DIR = __DIR__ . '/../views/';
    const JS_COMPONENTS_DIR = __DIR__ .'/../public/js/components/';
    const API_MAP = [
        'GET' => [
            'user' => 'readUser'
        ],
        'POST' => [
            'poll' => 'createPoll',
            'user' => 'createUser',
            'vote' => 'createVote'
        ]
    ];

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
        $chunks = $this->getPathChunks();
        $method = $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $controller = null;
        $action = '';
        $parameters = [];
        $pageTitle = '';
        $siteRootURL = $this->getConfigVar(['siteRootURL']);

        if (count($chunks) === 0) {
            $controller = new SiteController($this);
            $action = 'index';
            $pageTitle = 'Create new poll';
        }

        if ($chunks[0] === 'api') {
            $this->payload = ($method === 'POST')
                ? json_decode(file_get_contents('php://input'), true)
                : $this->payload = array_slice($chunks, 2);
            $controller = new APIController($this);
            $action = isset(self::API_MAP[$method][$chunks[1]]) ? self::API_MAP[$method][$chunks[1]] : false;
        } else if ($chunks[0] === 'poll') {
            $controller = new SiteController($this);
            $action = 'poll';
            $parameters = [$chunks[1]];
            $pageTitle = 'Poll voting and results';
        }

        if (!$action || !method_exists($controller, $action)) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $content = call_user_func_array([$controller, $action], $parameters);

        if ($controller instanceof APIController) {
            header('Content-Type: application/json');
            echo $content;
        } else {
            echo $this->renderView('layout', compact('content', 'pageTitle', 'siteRootURL'));
        }
        return true;
    }

    public function pdo()
    {
        return $this->pdo;
    }

    public function payload()
    {
        return $this->payload;
    }

    public function addJsComponent($name)
    {
        if (!in_array($name, $this->jsComponents) && is_file(self::JS_COMPONENTS_DIR . $name . '.js')) {
            $this->jsComponents[] = $name;
        }
    }

    public function getJsComponents()
    {
        return $this->jsComponents;
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

    private function getConfigVar($var = null)
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

    private function getPathChunks() : array
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $chunks = $uri ? explode('/', $uri) : [];
        return $chunks;
    }
}
