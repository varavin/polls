<?php

namespace Polls\Models;

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
    public $id = 0;
    public $uid = '';
    public $question = '';
    public $answers = [];

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

    public function getAnswersIds()
    {
        $result = [];
        foreach ($this->answers as $answer) {
            $result[] = intval($answer->getId());
        }
        return $result;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function fillable(): array
    {
        return ['id', 'uid', 'question'];
    }

    public function fill(array $props): bool
    {
        parent::fill($props);
        if (array_key_exists('answers', $props) && is_array($props['answers'])) {
            foreach ($props['answers'] as $answer) {
                if ($answer instanceof Answer) {
                    $this->answers[] = $answer;
                }
            }
        }
        return true;
    }

    public function validate(): bool
    {
        return strlen($this->uid) === 32 && intval($this->id) >= 0 && count($this->answers) > 0;
    }

    public function jsonSerialize(): array
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
        $votesService = new VotesCRUD($this->pdo());
        $results = $votesService->readMultiple($this->getAnswersIds());
        return $results;
    }
}