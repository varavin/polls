<?php

namespace Polls\Models;

use Polls\Interfaces\ModelInterface;

/**
 * Class Model
 * @package Polls\Models
 * @property \PDO $pdo;
 */
class Model implements ModelInterface
{
    public function __construct(\PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function pdo()
    {
        return $this->pdo;
    }

    public function fill(array $data)
    {

    }

    public function validate()
    {

    }

    public function jsonSerialize()
    {

    }
}