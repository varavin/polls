<?php

namespace Polls\Services;

class CRUD
{
    private $pdo = null;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * return \PDO
     */
    public function pdo()
    {
        return $this->pdo;
    }

}