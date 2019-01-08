<?php

namespace Polls\Models;

/**
 * Class Vote
 * @package Polls\Models
 * @property integer $id
 * @property integer $userId
 * @property integer $answerId
 * @property string $answer
 * @property string $visitorName
 */
class Vote extends Model
{
    public $id = null;
    public $userId = null;
    public $answerId = null;
    public $visitorName = null;

    public function setId(int $id)
    {
        $this->id = $id;
    }

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

    public function getVisitorName()
    {
        return $this->visitorName;
    }

    public function fillable(): array
    {
        return ['id', 'userId', 'answerId', 'visitorName'];
    }

    public function validate(): bool
    {
        return $this->userId && $this->answerId && $this->visitorName;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'answerId' => $this->answerId,
            'visitorName' => $this->visitorName,
        ];
    }
}