<?php

namespace Polls\Services;

use Polls\Models\Poll;

class PollsCRUD extends CRUD
{
    public function create(array $data) : Poll
    {
        $poll = new Poll();
        $dataNew = $data;
        $dataNew['uid'] = md5(uniqid());
        if (!$poll->fill($dataNew) || !$poll->validate()) {
            return new Poll();
        }
        $sql = 'INSERT INTO polls (uid, authorName, question) VALUES (:uid, :authorName, :question)';
        $params = [
            ':uid' => $poll->getUid(),
            ':authorName' => $poll->getAuthorName(),
            ':question' => $poll->getQuestion()
        ];
        if (!$this->pdo()->prepare($sql)->execute($params)) {
            return new Poll();
        };
        $id = $this->pdo()->lastInsertId();
        $poll->setId($id);
        $poll = $this->saveAnswers($poll);
        return $poll;
    }

    public function read(int $id) : Poll
    {
        $sql = 'SELECT * FROM polls WHERE id = ' . intval($id);
        $row = $this->pdo()->query($sql)->fetch(\PDO::FETCH_ASSOC);
        $poll = new Poll();
        if ( ! ($row && $poll->fill($row) && $poll->validate())) {
            return new Poll();
        }
        $answersService = new AnswersCRUD($this->pdo());
        $answers = $answersService->getByPollId($poll->getId());
        $poll->setAnswers($answers);
        return $poll;
    }

    private function saveAnswers(Poll $poll) : Poll
    {
        $resultPoll = $poll;
        $updatedAnswers = [];
        foreach ($poll->getAnswers() as $answer) {
            $answersService = new AnswersCRUD($this->pdo());
            $newAnswer = $answersService->create(['pollID' => $poll->getId(), 'text' => $answer->getText()]);
            if ($newAnswer->getId()) {
                $updatedAnswers[] = $newAnswer;
            }
        }
        $resultPoll->setAnswers($updatedAnswers);
        return $resultPoll;
    }
}