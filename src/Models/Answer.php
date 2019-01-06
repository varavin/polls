<?php

namespace Polls\Models;

use Polls\Interfaces\ModelInterface;

class Answer implements ModelInterface
{
    private $id = 0;
    private $pollId = 0;
    private $text = '';

    public function getPollID()
    {
        return $this->pollId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getID()
    {
        return $this->id;
    }

    public function setID(int $id)
    {
        $this->id = $id;
    }

    public function fill(array $data) {
        if (array_key_exists('text', $data)) {
            $this->text = $data['text'];
        }
        if (array_key_exists('pollID', $data)) {
            $this->pollId = $data['pollID'];
        }
        return true;
    }

    public function validate() {
        return $this->text !== '' && intval($this->pollId) > 0;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'pollId' => $this->pollId,
            'text' => $this->text
        ];
    }
}