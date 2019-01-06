<?php

namespace Polls;

class Router
{
    private $chunks = [];

    private $apiMap = [
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

    private function loadChunks()
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $chunks = $uri ? explode('/', $uri) : [];
        $this->chunks = $chunks;
    }
}