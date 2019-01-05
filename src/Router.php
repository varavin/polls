<?php

namespace Polls;

class Router
{
    private $chunks = [];

    private $apiMap = [
        'GET' => [
            ''
        ],
        'POST' => [
            'poll' => 'createPoll'
        ]
    ];

    public function __construct()
    {
        $this->loadChunks();
    }

    public function getChunks()
    {
        return $this->chunks;
    }

    public function getAPIMap()
    {
        return $this->apiMap;
    }

    public function show404()
    {
        header('HTTP/1.0 404 Not Found');
        exit;
    }

    private function loadChunks()
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $chunks = $uri ? explode('/', $uri) : [];
        $this->chunks = $chunks;
    }
}