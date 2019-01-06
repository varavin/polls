<?php

namespace Polls\Models;

use Polls\Interfaces\ModelInterface;

class User implements ModelInterface
{
    private $id = 0;
    private $uid = '';

    public function getId()
    {
        return $this->id;
    }

    public function fill(array $data = [])
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }
        if (array_key_exists('uid', $data)) {
            $this->uid = $data['uid'];
        }
        return true;
    }

    public function validate()
    {
        return strlen($this->uid) === 32 && intval($this->id) >= 0;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
        ];
    }
}