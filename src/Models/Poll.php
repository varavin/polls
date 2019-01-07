<?php

namespace Polls\Models;

use Polls\Services\AnswersCRUD;
use Polls\Services\VotesCRUD;

/**
 * Class Poll
 * @package Polls\Models
 * @property integer $id
 * @property string $uid
 * @property string $question
 * @property Answer[] $answers
 */
class Poll extends Model
{
    private $id = 0;
    private $uid = '';
    private $question = '';
    private $answers = [];

    public function getQuestion()
    {
        return $this->question;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    public function setAnswers(array $answers)
    {
        $this->answers = $answers;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function fill(array $data)
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }
        if (array_key_exists('uid', $data)) {
            $this->uid = $data['uid'];
        }
        if (array_key_exists('question', $data)) {
            $this->question = $data['question'];
        }
        if (array_key_exists('answers', $data) && is_array($data['answers'])) {
            foreach ($data['answers'] as $answerData) {
                $answer = new Answer();
                if ($answer->fill($answerData)) {
                    $this->answers[] = $answer;
                }
            }
        }
        return true;
    }

    public function validate()
    {
        return strlen($this->uid) === 32 && intval($this->id) >= 0;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'question' => $this->question,
            'answers' => $this->answers
        ];
    }

    public function getResults() : array
    {
        $answersIds = array_keys($this->getAnswers());
        $votesService = new VotesCRUD($this->pdo());
        $results = $votesService->readMultiple($answersIds);
        return $results;
    }
}