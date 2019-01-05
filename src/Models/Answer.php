<?php

namespace Polls\Models;

use Polls\Interfaces\ModelInterface;

class Answer implements ModelInterface
{
    private $id = 0;
    private $pollID = 0;
    private $text = '';

    public function getPollID()
    {
        return $this->pollID;
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
            $this->pollID = $data['pollID'];
        }
        return true;
    }

    public function validate() {
        return $this->text !== '' && intval($this->pollID) > 0;
    }
}