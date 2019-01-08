<?php
declare(strict_types=1);

use Polls\App;
use Polls\Models\Poll;
use Polls\Services\PollsCRUD;
use Polls\Services\UsersCRUD;
use Polls\Services\VotesCRUD;
use PHPUnit\Framework\TestCase;

final class VotesCRUDTest extends TestCase
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

        $usersService = new UsersCRUD($app->pdo());
        $user = $usersService->create();
        $this->assertTrue($user->getId() > 0 && $user->validate());

        $pollsService = new PollsCRUD($app->pdo());
        $poll = $pollsService->create($this->dataPollGood);
        $this->assertTrue($poll->getId() > 0 && $poll->validate());

        $votesService = new VotesCRUD($app->pdo());
        $voteData = ['name' => 'Jack', 'answerId' => $poll->getAnswers()[0]->getId()];
        $vote = $votesService->create($user, $poll, $voteData);
        $this->assertTrue($vote->getId() > 0 && $vote->validate());

        $voteData = ['name' => '', 'answerId' => $poll->getAnswers()[0]->getId()];
        $vote = $votesService->create($user, $poll, $voteData);
        $this->assertFalse($vote->getId() > 0 && $vote->validate());

        $voteData = ['name' => 'Jack', 'answerId' => $poll->getAnswers()[0]->getId()];
        $vote = $votesService->create($user, new Poll(), $voteData);
        $this->assertFalse($vote->getId() > 0 && $vote->validate());
    }

    public function testRead(): void
    {
        $app = new App(App::AUTOTESTS_MODE);

        $usersService = new UsersCRUD($app->pdo());
        $user = $usersService->create();
        $this->assertTrue($user->getId() > 0 && $user->validate());

        $pollsService = new PollsCRUD($app->pdo());
        $poll = $pollsService->create($this->dataPollGood);
        $this->assertTrue($poll->getId() > 0 && $poll->validate());

        $votesService = new VotesCRUD($app->pdo());
        $voteData = ['name' => 'Jack', 'answerId' => $poll->getAnswers()[0]->getId()];
        $vote = $votesService->create($user, $poll, $voteData);
        $this->assertTrue($vote->getId() > 0 && $vote->validate());

        $voteRead = $votesService->read($vote->getId());
        $this->assertEquals($vote, $voteRead);
    }

    public function testReadMultiple(): void
    {
        $app = new App(App::AUTOTESTS_MODE);

        $usersService = new UsersCRUD($app->pdo());
        $user = $usersService->create();
        $this->assertTrue($user->getId() > 0 && $user->validate());

        $pollsService = new PollsCRUD($app->pdo());
        $poll = $pollsService->create($this->dataPollGood);
        $this->assertTrue($poll->getId() > 0 && $poll->validate());

        $votesService = new VotesCRUD($app->pdo());
        foreach ($poll->getAnswers() as $answer) {
            $user = $usersService->create();
            $voteData = ['name' => 'Jack' . $answer->getId(), 'answerId' => $answer->getId()];
            $vote = $votesService->create($user, $poll, $voteData);
            $this->assertTrue($vote->getId() > 0 && $vote->validate());
        }

        $answersPartial = $votesService->readMultiple($poll->getAnswersIds());
        $this->assertTrue(count($answersPartial) === 3);

        $answersPartial = $votesService->readMultiple($poll->getAnswersIds(), $user->getId());
        $this->assertTrue(count($answersPartial) === 1);
    }

}