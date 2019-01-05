<?php

namespace Polls\Controllers;

class Controller
{
    private $payload = [];
    private $pdo = null;

    public function __construct(\PDO $pdo, array $payload = [])
    {
        $this->payload = $payload;
        $this->pdo = $pdo;
    }

    /**
     * @return \PDO
     */
    public function pdo()
    {
        return $this->pdo;
    }

    public function payload()
    {
        return $this->payload;
    }
}