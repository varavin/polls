<?php

namespace Polls\Models;

/**
 * Class Poll
 * @package Polls\Models
 * @property integer $id
 * @property string $uid
 * @property string $authorName
 * @property string $question
 * @property Answer[] $answers
 */
class Poll extends Model
{
    private $id = 0;
    private $uid = '';
    private $authorName = '';
    private $question = '';
    private $answers = [];

    public function getAuthorName()
    {
        return $this->authorName;
    }

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
        if (array_key_exists('uid', $data)) {
            $this->uid = $data['uid'];
        }
        if (array_key_exists('authorName', $data)) {
            $this->authorName = $data['authorName'];
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
        return strlen($this->uid) === 32 && $this->authorName !== '' && intval($this->id) >= 0;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'authorName' => $this->authorName,
            'question' => $this->question,
            'answers' => $this->answers
        ];
    }
}