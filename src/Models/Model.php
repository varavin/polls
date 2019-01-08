<?php

namespace Polls\Models;

use phpDocumentor\Reflection\Types\This;
use Polls\Interfaces\ModelInterface;

/**
 * Class Model
 * @package Polls\Models
 * @property \PDO $pdo;
 */
class Model implements ModelInterface
{
    /**
     * Model constructor.
     * @param \PDO|null $pdo
     * @param array $props
     */
    public function __construct(\PDO $pdo = null, array $props = [])
    {
        $this->pdo = $pdo;
        $this->fill($props);
    }

    public function pdo(): \PDO
    {
        return $this->pdo;
    }

    public function fillable(): array
    {
        return [];
    }

    public function fillableInteger(): array
    {
        return ['id', 'pollId', 'answerId', 'userId'];
    }

    public function fill(array $props): bool
    {
        $fillable = $this->fillable();
        foreach ($props as $key => $value) {
            if (property_exists($this, $key) && in_array($key, $fillable)) {
                $this->$key = in_array($key, $this->fillableInteger())
                    ? intval($value)
                    : $value;
            }
        }
        return true;
    }

    public function validate(): bool
    {

    }

    public function jsonSerialize(): array
    {

    }
}