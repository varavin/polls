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
            $this->setStatus(false, 'Poll data not valid.');
            return new Poll();
        }
        $sql = 'INSERT INTO polls (uid, question) VALUES (:uid, :question)';
        $params = [
            ':uid' => $poll->getUid(),
            ':question' => $poll->getQuestion()
        ];
        if (!$this->pdo()->prepare($sql)->execute($params)) {
            $this->setStatus(false, 'Error while creating poll.');
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
            $this->setStatus(false, 'Poll not found.');
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