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
    public $id = 0;
    public $pollId = 0;
    public $text = '';

    public function getPollId()
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

    public function fillable(): array
    {
        return ['id', 'uid', 'text', 'pollId'];
    }

    public function validate(): bool
    {
        return $this->text !== '' && intval($this->pollId) > 0;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'pollId' => $this->pollId,
            'text' => $this->text
        ];
    }
}