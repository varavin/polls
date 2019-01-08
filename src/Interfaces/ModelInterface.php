<?php

namespace Polls\Interfaces;

interface ModelInterface extends \JsonSerializable
{
    public function fillable(): array;

    public function fill(array $props): bool;

    public function validate(): bool;

    public function pdo(): \PDO;
}