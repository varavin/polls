<?php

namespace Polls\Services;

/**
 * Class CRUD
 * @package Polls\Services
 * @property \PDO $pdo
 * @property boolean $success
 * @property string $message
 */
class CRUD
{
    private $pdo = null;
    private $success = true;
    private $message = '';

    /**
     * CRUD constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

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