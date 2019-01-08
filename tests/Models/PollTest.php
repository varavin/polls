<?php
declare(strict_types=1);

use Polls\Models\Poll;
use Polls\Models\Answer;
use PHPUnit\Framework\TestCase;

final class PollTest extends TestCase
{
    private $dataAnswersGood =  [
        ['id' => '1', 'text' => 'Answer 1 text', 'pollId' => '111'],
        ['id' => '2', 'text' => 'Answer 2 text', 'pollId' => '111'],
        ['id' => '3', 'text' => 'Answer 3 text', 'pollId' => '111'],
    ];

    private $dataAnswersBad =  [
        ['id' => '4', 'text' => 'Answer 4 text', 'pollId' => ''],
        ['id' => '5', 'text' => '', 'pollId' => '111'],
        ['id' => '-5', 'text' => '', 'pollId' => '111']
    ];

    public function testFill(): void
    {
        $answers = [];
        foreach (array_merge($this->dataAnswersGood, $this->dataAnswersBad) as $row) {
            $answers[] = new Answer(null, $row);
        }
        $data = [
            'uid' => 'f17bceaa02d816c1d4ea14e5b36c2202',
            'question' => 'Test question',
            'answers' => $answers
        ];
        $poll = new Poll(null, $data);
        $this->assertEquals($poll->getUid(), $data['uid']);
        $this->assertEquals($poll->getQuestion(), $data['question']);
        foreach ($poll->getAnswers() as $answer) {
            $this->assertInstanceOf('\Polls\Models\Answer', $answer);
        }
        $this->assertEquals($poll->getAnswers(), $data['answers']);
    }

    public function testValidate(): void
    {
        $answers = [];
        foreach ($this->dataAnswersGood as $row) {
            $answers[] = new Answer(null, $row);
        }
        $dataGood = [
            ['uid' => 'f17bceaa02d816c1d4ea14e5b36c2202', 'question' => 'Test question', 'answers' => $answers],
        ];
        foreach ($dataGood as $row) {
            $poll = new Poll(null, $row);
            $this->assertTrue($poll->validate());
        }

        $dataBad = [
            ['uid' => 'zzz', 'question' => 'Test question', 'answers' => $answers],
            ['uid' => 'f17bceaa02d816c1d4ea14e5b36c2202', 'question' => 'Test question', 'answers' => []],
            ['uid' => 'f17bceaa02d816c1d4ea14e5b36c2202', 'question' => '', 'answers' => $answers]
        ];
        foreach ($dataBad as $row) {
            $poll = new Poll(null, $row);
            $this->assertFalse($poll->validate());
        }
    }

    public function testJsonSerialize(): void
    {
        $answers = [];
        foreach ($this->dataAnswersGood as $row) {
            $answers[] = new Answer(null, $row);
        }
        $data = [
            'id' => 0,
            'uid' => 'f17bceaa02d816c1d4ea14e5b36c2202',
            'question' => 'Test question',
        ];
        $dataWithAnswersObjects = array_merge($data, ['answers' => $answers]);
        $dataWithAnswersArrays = array_merge($data, ['answers' => $this->dataAnswersGood]);
        $poll = new Poll(null, $dataWithAnswersObjects);
        $this->assertEquals($poll->jsonSerialize(), $dataWithAnswersArrays);
    }


}