<?php

namespace Polls\Models;

/**
 * Class Answer
 * @package Polls\Models
 * property integer $id
 * property integer $pollId
 * property string $text
 */
class Answer extends Model
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

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function fill(array $data) {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }
        if (array_key_exists('text', $data)) {
            $this->text = $data['text'];
        }
        if (array_key_exists('pollId', $data)) {
            $this->pollId = $data['pollId'];
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