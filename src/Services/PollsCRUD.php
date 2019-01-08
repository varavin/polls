<?php

namespace Polls\Services;

use Polls\Models\Poll;
use Polls\Models\Answer;

class PollsCRUD extends CRUD
{
    public function create(array $data): Poll
    {
        $blankPoll = new Poll($this->pdo());
        $uid = md5(uniqid());

        // initialization and validation
        $answers = [];
        if (isset($data['answers']) && is_array($data['answers'])) {
            foreach ($data['answers'] as $value) {
                $answers[] = new Answer($this->pdo(), $value);
            }
        }
        $poll = new Poll($this->pdo(), array_merge(
            $data,
            compact('uid'),
            compact('answers')
        ));
        if (!$poll->validate()) {
            $this->setStatus(false, 'Poll data not valid');
            return $blankPoll;
        }

        // saving poll
        $sql = 'INSERT INTO polls (uid, question) VALUES (:uid, :question)';
        $params = [
            ':uid' => $poll->getUid(),
            ':question' => $poll->getQuestion()
        ];
        if (!$this->pdo()->prepare($sql)->execute($params)) {
            $this->setStatus(false, 'Error while creating poll');
            return $blankPoll;
        };
        $id = $this->pdo()->lastInsertId();
        $poll->setId($id);

        // saving answers
        foreach ($poll->getAnswers() as $answer) {
            $this->createAnswer([
                'pollId' => $poll->getId(),
                'text' => $answer->getText()
            ]);
        }

        return $this->read($poll->getId());
    }

    public function read(int $id, string $uid = ''): Poll
    {
        $blankPoll = new Poll($this->pdo());
        $sql = 'SELECT * FROM polls WHERE ' . ($id ? ' id = :id ' : ' uid = :uid ');
        $params = $id ? [':id' => $id] : [':uid' => $uid];
        $query = $this->pdo()->prepare($sql);
        $query->execute($params);
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            $this->setStatus(false, 'Poll not found');
            return $blankPoll;
        }
        $row['answers'] = $this->getPollAnswers($row['id']);

        $poll = new Poll($this->pdo(), $row);
        if ($poll->validate()) {
            return $poll;
        } else {
            $this->setStatus(false, 'Wrong poll data');
            return $blankPoll;
        }
    }

    public function getByAnswerId($answerId): Poll
    {
        $sql = 'SELECT pollId FROM answers WHERE id = ' . intval($answerId);
        $pollId = $this->pdo()->query($sql)->fetch(\PDO::FETCH_COLUMN);
        return $this->read($pollId);
    }

    private function getPollAnswers(int $pollId): array
    {
        if (!$pollId) {
            return [];
        }
        $result = [];
        $sql = 'SELECT * FROM answers WHERE pollId = ' . intval($pollId) . ' ORDER BY id';
        $data = $this->pdo()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $row) {
            $answer = new Answer();
            if ($row && $answer->fill($row) && $answer->validate()) {
                $result[$answer->getId()] = $answer;
            }
        }
        return $result;
    }

    private function createAnswer(array $data): Answer
    {
        $blankAnswer = new Answer($this->pdo());
        $answer = new Answer($this->pdo(), $data);
        if (!$answer->validate()) {
            $this->setStatus(false, 'Wrong answer data');
            return $blankAnswer;
        }

        $sql = 'INSERT INTO answers (pollId, text) VALUES (:pollId, :text)';
        $params = [
            ':pollId' => $answer->getPollId(),
            ':text' => $answer->getText()
        ];
        $result = $this->pdo()->prepare($sql)->execute($params);
        if (!$result) {
            $this->setStatus(false, 'Error while creating answer');
            return $blankAnswer;
        }
        $id = $this->pdo()->lastInsertId();
        $answer->setId($id);
        return $answer;
    }

}