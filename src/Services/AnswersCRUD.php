<?php

namespace Polls\Services;

use Polls\Models\Answer;

class AnswersCRUD extends CRUD
{
    public function create(array $data) : Answer
    {
        $answer = new Answer();
        if (!$answer->fill($data) && !$answer->validate()) {
            $this->setStatus(false, 'Error while creating answer.');
            return new Answer();
        }

        $sql = 'INSERT INTO answers (pollId, text) VALUES (:pollId, :text)';
        $params = [
            ':pollId' => $answer->getPollId(),
            ':text' => $answer->getText()
        ];
        if (!$this->pdo()->prepare($sql)->execute($params)) {
            $this->setStatus(false, 'Error while creating answer.');
            return new Answer();
        }
        $id = $this->pdo()->lastInsertId();
        $answer->setId($id);
        return $answer;
    }

    public function read(int $id) : Answer
    {
        $sql = 'SELECT * FROM answers WHERE id = ' . intval($id);
        $row = $this->pdo()->query($sql)->fetch(\PDO::FETCH_ASSOC);
        $answer = new Answer();
        if ($row && $answer->fill($row) && $answer->validate()) {
            return $answer;
        } else {
            $this->setStatus(false, 'Answer not found or not valid.');
            return new Answer();
        }
    }

    public function getByPollId(int $pollId) : array
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
}