<?php

namespace Polls\Interfaces;

interface ModelInterface
{
    public function fill(array $data);
    public function validate();
}