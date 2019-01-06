<?php

namespace Polls\Models;

use Polls\Interfaces\ModelInterface;

class Vote implements ModelInterface
{
    private $id = null;
    private $userId = null;
    private $answerId = null;
    private $answer = null;

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getAnswerId()
    {
        return $this->answerId;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function fill(array $data = [])
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }
        if (array_key_exists('userId', $data)) {
            $this->userId = $data['userId'];
        }
        if (array_key_exists('answerId', $data)) {
            $this->answerId = $data['answerId'];
        }
        if (array_key_exists('answer', $data)) {
            $this->answer = $data['answer'];
        }
        return true;
    }

    public function validate()
    {
        return $this->userId && $this->answerId && $this->answer !== null;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'answerId' => $this->answerId,
            'answer' => $this->answer,
        ];
    }
}