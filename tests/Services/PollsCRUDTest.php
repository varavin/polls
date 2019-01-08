<?php
declare(strict_types=1);

use Polls\App;
use Polls\Models\Poll;
use Polls\Services\PollsCRUD;
use PHPUnit\Framework\TestCase;

final class PollsCRUDTest extends TestCase
{
    private $dataPollGood = [
        'question' => 'Test question',
        'answers' => [
            ['text' => 'Answer 1 text'],
            ['text' => 'Answer 2 text'],
            ['text' => 'Answer 3 text'],
        ]
    ];

    public function testCreate(): void
    {
        $app = new App(App::AUTOTESTS_MODE);
        $pollsService = new PollsCRUD($app->pdo());
        $poll = $pollsService->create($this->dataPollGood);
        $this->validatePoll($poll, $this->dataPollGood);
    }

    public function testRead(): void
    {
        $app = new App(App::AUTOTESTS_MODE);
        $pollsService = new PollsCRUD($app->pdo());
        $pollCreated = $pollsService->create($this->dataPollGood);
        $poll = $pollsService->read($pollCreated->getId());
        $this->validatePoll($poll, $this->dataPollGood);
    }

    public function testGetByAnswerId(): void
    {
        $app = new App(App::AUTOTESTS_MODE);
        $pollsService = new PollsCRUD($app->pdo());
        $poll = $pollsService->create($this->dataPollGood);
        $this->validatePoll($poll, $this->dataPollGood);
        $answers = $poll->getAnswers();
        $pollByAnswerId = $pollsService->getByAnswerId($answers[0]->getId());
        $this->assertEquals($poll, $pollByAnswerId);
    }

    private function validatePoll(Poll $poll, array $data): void
    {
        $this->assertTrue($poll->getId() > 0);
        $this->assertEquals($poll->getQuestion(), $data['question']);
        foreach ($poll->getAnswers() as $answer) {
            $this->assertInstanceOf('\Polls\Models\Answer', $answer);
            $this->assertEquals($answer->getPollId(), $poll->getId());
            $this->assertTrue($answer->getId() > 0);
        }
    }
}