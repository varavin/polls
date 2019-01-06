<?php

namespace Polls\Services;

use Polls\Models\User;
use Polls\Models\Vote;

class VotesCRUD extends CRUD
{
    public function createMultiple(User $user, array $data) : bool
    {
        if (!isset($data['votes']) && !is_array($data['votes'])) {
            return $this->setStatus(false, 'No "votes" section in the payload.');
        }

        // retrieving first answer data to determine the poll ID
        $answersService = new AnswersCRUD($this->pdo());
        $firstAnswer = $answersService->read($data['votes'][0]['answerId']);
        if (!$firstAnswer->getID()) {
            return $this->setStatus(false, 'Cannot determine the poll ID.');
        }

        // validating that all answers belong to the same poll
        $sql = 'SELECT id FROM answers WHERE pollId = ' . intval($firstAnswer->getPollID());
        $pollAnswersIds = $this->pdo()->query($sql)->fetchAll(\PDO::FETCH_COLUMN);
        foreach ($data['votes'] as $voteData) {
            if (!isset($voteData['answerId']) || !in_array($voteData['answerId'], $pollAnswersIds)) {
                return $this->setStatus(false, 'All answers must belong to the same poll.');
            }
        }

        // validating that all answers are present in the payload
        $givenAnswersIds = [];
        foreach ($data['votes'] as $voteData) {
            $givenAnswersIds[] = strval($voteData['answerId']);
        }
        if (!empty(array_diff($pollAnswersIds, $givenAnswersIds))) {
            return $this->setStatus(false, 'All the poll\'s answers must present in the payload.');
        }

        // validating that user has not voted for any of the answers before
        $sql = 'SELECT id FROM votes WHERE userId = :userId AND answerId IN (:answersIds)';
        $query = $this->pdo()->prepare($sql);
        $query->execute([':userId' => $user->getId(), ':answersIds' => implode(',', $pollAnswersIds)]);

        if ($query->rowCount()){
            return $this->setStatus(false, 'This user has already voted.');
        }

        // finally saving the votes data
        foreach ($data['votes'] as $voteData) {
            $voteDataFull = $voteData;
            $voteDataFull['userId'] = $user->getId();
            $vote = new Vote();
            if ($vote->fill($voteDataFull) && $vote->validate()) {
                $sql = 'INSERT INTO votes (userId, answerId, answer) VALUES (:userId, :answerId, :answer)';
                $this->pdo()->prepare($sql)->execute([
                    ':userId' => $user->getId(),
                    ':answerId' => $vote->getAnswerId(),
                    ':answer' => $vote->getAnswer()
                ]);
            }
        }

        return true;
    }
}