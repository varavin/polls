<?php

namespace Polls\Interfaces;

interface ModelInterface extends \JsonSerializable
{
    public function fill(array $data);
    public function validate();
}