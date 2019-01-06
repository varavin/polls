<?php

namespace Polls\Services;

class CRUD
{
    private $pdo = null;
    private $success = true;
    private $message = '';

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

    public function setStatus(bool $status, string $message) : bool
    {
        $this->success = $status;
        $this->message = $message;
        return $status;
    }

    public function getSuccess() : bool
    {
        return $this->success;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

}