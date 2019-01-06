<?php

namespace Polls\Services;

use Polls\Models\User;
use Polls\Models\Vote;
use Polls\Models\Poll;

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
        if ($this->userHasVoted($user, $answer->getPollID())) {
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

    private function userHasVoted(User $user, int $pollId) : bool
    {
        $answersService = new AnswersCRUD($this->pdo());
        $answersIds = array_keys($answersService->getByPollId($pollId));
        $placeholders = array_fill(0, count($answersIds), '?');
        $sql = 'SELECT * FROM votes WHERE userId = ? AND answerId IN (' . implode(',', $placeholders) . ')';
        $query = $this->pdo()->prepare($sql);
        $query->bindParam(1, $user->getId());
        for ($i = 1; $i <= count($answersIds); $i++) {
            $query->bindParam($i + 1, $answersIds[$i - 1]);
        }
        $query->execute();
        return $query->rowCount() > 0;
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