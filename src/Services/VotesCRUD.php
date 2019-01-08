<?php

namespace Polls\Services;

use Polls\Models\User;
use Polls\Models\Vote;
use Polls\Models\Poll;

class VotesCRUD extends CRUD
{
    /**
     * @param User $user
     * @param Poll $poll
     * @param array $data
     * @return Vote
     */
    public function create(User $user, Poll $poll, array $data) : Vote
    {
        $blankVote = new Vote($this->pdo());
        if (!isset($data['name'])) {
            $this->setStatus(false, 'Visitor name is missing');
            return $blankVote;
        }

        if (!isset($data['answerId']) || !in_array($data['answerId'], $poll->getAnswersIds())) {
            $this->setStatus(false, 'Wrong answer ID');
            return $blankVote;
        }

        if ($user->hasVoted($poll)) {
            $this->setStatus(false, 'This user has already voted');
            return $blankVote;
        }

        // finally saving the votes data
        $voteData = [
            'userId' => $user->getId(),
            'answerId' => $data['answerId'],
            'visitorName' => $data['name']
        ];
        $vote = new Vote($this->pdo(), $voteData);
        if ($vote->validate()) {
            $vote = $this->saveVote($user, $vote);
        } else {
            $this->setStatus(false, 'Wrong vote data');
            return $blankVote;
        }

        return $vote;
    }

    /**
     * @param int $id
     * @return Vote
     */
    public function read(int $id) : Vote
    {
        $blankVote = new Vote($this->pdo());
        $sql = 'SELECT * FROM votes WHERE id = ' . intval($id);
        $row = $this->pdo()->query($sql)->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            $this->setStatus(false, 'Vote not found');
            return $blankVote;
        }
        $vote = new Vote($this->pdo(), $row);
        if (!$vote->validate()) {
            $this->setStatus(false, 'Wrong vote data');
            return $blankVote;
        }
        return $vote;
    }

    /**
     * @param array $ids
     * @param int|null $userId
     * @return Vote[]
     */
    public function readMultiple(array $ids, int $userId = null) : array
    {
        $placeholders = array_fill(0, count($ids), '?');
        $sql = 'SELECT * FROM votes WHERE answerId IN (' . implode(',', $placeholders) . ') ';
        if ($userId) {
            $sql .= ' AND userId = ?';
        }
        $query = $this->pdo()->prepare($sql);
        for ($i = 1; $i <= count($ids); $i++) {
            $query->bindParam($i, $ids[$i - 1]);
        }
        if ($userId) {
            $query->bindParam(count($ids) + 1, $userId);
        }
        $query->execute();
        $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $vote = new Vote($this->pdo(), $row);
            if ($vote->validate()) {
                $result[] = $vote;
            } else {
                $this->setStatus(false, 'Wrong vote data');
                return [];
            }
        }
        return $result;
    }

    private function saveVote(User $user, Vote $vote) : Vote
    {
        $sql = 'INSERT INTO votes (userId, answerId, visitorName) VALUES (:userId, :answerId, :visitorName)';
        $this->pdo()->prepare($sql)->execute([
            ':userId' => $user->getId(),
            ':answerId' => $vote->getAnswerId(),
            ':visitorName' => $vote->getVisitorName()
        ]);
        $id = $this->pdo()->lastInsertId();
        return $this->read($id);
    }
}