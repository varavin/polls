<?php

namespace Polls\Services;

use Polls\Models\User;
use Polls\Models\Vote;

class VotesCRUD extends CRUD
{
    public function create(User $user, array $data) : Vote
    {
        if (!isset($data['answerId'])) {
            $this->setStatus(false, 'Answer ID is missing.');
            return new Vote();
        }
        if (!isset($data['name'])) {
            $this->setStatus(false, 'Visitor name is missing.');
            return new Vote();
        }

        // retrieving answer data to validate the answer ID
        $answersService = new AnswersCRUD($this->pdo());
        $answer = $answersService->read($data['answerId']);
        if (!$answer->getId()) {
            $this->setStatus(false, 'Wrong answer ID.');
            return new Vote();
        }

        // validating that user has not voted for that poll before
        if ($user->hasVoted($answer->getPollID(), $this->pdo())) {
            $this->setStatus(false, 'This user has already voted');
            return new Vote();
        }

        // finally saving the votes data
        $voteData = ['userId' => $user->getId(), 'answerId' => $answer->getId(), 'visitorName' => $data['name']];
        $vote = new Vote();
        if ($vote->fill($voteData) && $vote->validate()) {
            $vote = $this->saveVote($user, $vote);
        }

        return $vote;
    }

    /**
     * @param int $id
     * @return Vote
     */
    public function read(int $id) : Vote
    {
        $sql = 'SELECT * FROM votes WHERE id = ' . intval($id);
        $row = $this->pdo()->query($sql)->fetch(\PDO::FETCH_ASSOC);
        $vote = new Vote();
        if ($row && $vote->fill($row) && $vote->validate()) {
            return $vote;
        } else {
            return new Vote();
        }
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
            $vote = new Vote();
            if ($vote->fill($row) && $vote->validate()) {
                $result[] = $vote;
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