<?php

namespace Polls\Models;


class Visitor
{
    private $uid = '';
    private $name = '';

    public function fill(array $data = [])
    {
        if (array_key_exists('UID', $data)) {
            $this->uid = $data['UID'];
        }
        if (array_key_exists('Name', $data)) {
            $this->name = $data['Name'];
        }
    }

    public function validate()
    {
        return strlen($this->uid) === 13 && $this->name !== '';
    }
}