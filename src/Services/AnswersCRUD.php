<?php

namespace Polls\Services;

use Polls\Models\Answer;

class AnswersCRUD extends CRUD
{
    public function create(array $data) : Answer
    {
        $answer = new Answer();
        if (!$answer->fill($data) && !$answer->validate($data)) {
            return new Answer();
        }
        $sql = 'INSERT INTO answers (pollID, text) VALUES (:pollID, :text)';
        $params = [
            ':pollID' => $answer->getPollID(),
            ':text' => $answer->getText()
        ];
        if (!$this->pdo()->prepare($sql)->execute($params)) {
            return new Answer();
        }
        $id = $this->pdo()->lastInsertId();
        $answer->setID($id);
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
            return new Answer();
        }
    }
}