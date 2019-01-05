<?php

namespace Polls\Services;

use Polls\Models\Poll;

class PollsCRUD extends CRUD
{
    public function create(array $data) : Poll
    {
        $poll = new Poll();
        $dataNew = $data;
        $dataNew['uid'] = uniqid();
        if (!$poll->fill($dataNew) || !$poll->validate()) {
            return new Poll();
        }
        $sql = 'INSERT INTO polls (uid, authorName, question) VALUES (:uid, :authorName, :question)';
        $params = [
            ':uid' => $poll->getUID(),
            ':authorName' => $poll->getAuthorName(),
            ':question' => $poll->getQuestion()
        ];
        if (!$this->pdo()->prepare($sql)->execute($params)) {
            return new Poll();
        };
        $id = $this->pdo()->lastInsertId();
        $poll->setID($id);
        $poll = $this->saveAnswers($poll);
        return $poll;
    }

    private function saveAnswers(Poll $poll) : Poll
    {
        $resultPoll = $poll;
        $updatedAnswers = [];
        foreach ($poll->getAnswers() as $answer) {
            $answersService = new AnswersCRUD($this->pdo());
            $newAnswer = $answersService->create(['pollID' => $poll->getID(), 'text' => $answer->getText()]);
            if ($newAnswer->getID()) {
                $updatedAnswers[] = $newAnswer;
            }
        }
        $resultPoll->setAnswers($updatedAnswers);
        return $resultPoll;
    }
}