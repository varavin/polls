<?php

namespace Polls\Models;
use Polls\Interfaces\ModelInterface;

class Poll implements ModelInterface
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

    public function getUID()
    {
        return $this->uid;
    }

    public function getID()
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

    public function setID(int $id)
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