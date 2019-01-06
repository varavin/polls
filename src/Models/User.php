<?php

namespace Polls\Models;

use Polls\Services\AnswersCRUD;
use Polls\Services\VotesCRUD;

/**
 * Class User
 * @package Polls\Models
 * @property integer $id
 * @property string $uid
 */
class User extends Model
{
    private $id = 0;
    private $uid = '';

    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
    }

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

    public function hasVoted(int $pollId) : bool
    {
        $answersService = new AnswersCRUD($this->pdo());
        $answersIds = array_keys($answersService->getByPollId($pollId));
        $votesService = new VotesCRUD($this->pdo());
        $votes = $votesService->readMultiple($answersIds, $this->getId());
        return count($votes) > 0;
    }
}