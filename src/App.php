<?php

namespace Polls;

use Polls\Controllers\APIController;
use Polls\Controllers\SiteController;

class App
{
    public $config = [];
    private $pdo = null;
    private $autotests = false;

    const AUTOTESTS_MODE = true;
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

    public function __construct(bool $autotests = false)
    {
        $this->autotests = $autotests;
        $this->loadConfig();
    }

    public function run()
    {
        $chunks = $this->getPathChunks();
        $method = $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $controller = null;
        $action = '';
        $parameters = [];
        $pageTitle = '';
        $siteRootURL = $this->config['siteRootURL'];

        if (count($chunks) === 0) {
            $controller = new SiteController($this->pdo(), $this->config);
            $action = 'index';
            $pageTitle = 'Create new poll';
        }

        if ($chunks[0] === 'api') {
            $payload = ($method === 'POST')
                ? json_decode(file_get_contents('php://input'), true)
                : array_slice($chunks, 2);
            $controller = new APIController($this->pdo());
            $action = isset(self::API_MAP[$method][$chunks[1]]) ? self::API_MAP[$method][$chunks[1]] : false;
            $parameters = [$payload];
        } else if ($chunks[0] === 'poll' && $chunks[1]) {
            $controller = new SiteController($this->pdo(), $this->config);
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
            echo $controller->view()->render('layout', compact('content', 'pageTitle', 'siteRootURL'));
        }
        return true;
    }

    public function pdo()
    {
        if (!$this->pdo instanceof \PDO) {
            $dbConfig = $this->config[$this->autotests ? 'db_tests' : 'db'];
            $host = 'mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['database'];
            $user = $dbConfig['user'];
            $pass = $dbConfig['password'];
            $this->pdo = new \PDO($host, $user, $pass);
        }
        return $this->pdo;
    }

    private function loadConfig()
    {
        $env = 'production';
        if (file_exists(__DIR__ . '/../config/config.local.php')) {
            $env = 'local';
        } else if (isset($_SERVER['APP_ENV']) && in_array($_SERVER['APP_ENV'], ['staging', 'local'])) {
            $env = $_SERVER['APP_ENV'];
        }
        require(__DIR__ . '/../config/config.' . $env . '.php');
    }

    private function getPathChunks() : array
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $chunks = $uri ? explode('/', $uri) : [];
        return $chunks;
    }
}
