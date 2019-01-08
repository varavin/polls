<?php

namespace Polls\Models;

use Polls\Services\PollsCRUD;
use Polls\Services\VotesCRUD;

/**
 * Class User
 * @package Polls\Models
 * @property integer $id
 * @property string $uid
 */
class User extends Model
{
    public $id = 0;
    public $uid = '';

    public function getId()
    {
        return $this->id;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function fillable(): array
    {
        return ['id', 'uid'];
    }

    public function validate(): bool
    {
        return strlen($this->uid) === 32 && intval($this->id) >= 0;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
        ];
    }

    public function hasVoted(Poll $poll) : bool
    {
        $votesService = new VotesCRUD($this->pdo());
        $votes = $votesService->readMultiple($poll->getAnswersIds(), $this->getId());
        return count($votes) > 0;
    }
}