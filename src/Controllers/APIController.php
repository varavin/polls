<?php

namespace Polls\Controllers;

use Polls\Services\AnswersCRUD;
use Polls\Services\PollsCRUD;
use Polls\Services\UsersCRUD;
use Polls\Services\VotesCRUD;

class APIController extends Controller
{
    public function createPoll()
    {
        $pollsService = new PollsCRUD($this->app->pdo());
        $poll = $pollsService->create($this->app->payload());
        return $this->app->renderView('json', [
            'data' => $poll,
            'success' => $pollsService->getSuccess(),
            'message' => $pollsService->getMessage()
        ]);
    }

    public function createUser()
    {
        $usersService = new UsersCRUD($this->app->pdo());
        $user = $usersService->create();
        return $this->app->renderView('json', [
            'data' => $user,
            'success' => $usersService->getSuccess(),
            'message' => $usersService->getMessage()
        ]);
    }

    public function readUser()
    {
        $payload = $this->app->payload();
        $uid = isset($payload[0]) ? $payload[0] : ''; // element [0] is the user's UID from the request URI: /api/user/47c92b728a49f8d2460c6aa9ecaf1123
        $usersService = new UsersCRUD($this->app->pdo());
        $user = $usersService->read($uid);
        return $this->app->renderView('json', [
            'data' => $user,
            'success' => $usersService->getSuccess(),
            'message' => $usersService->getMessage()
        ]);
    }

    public function createVote()
    {
        $payload = $this->app->payload();

        $uid = isset($payload['userUid']) ? $payload['userUid'] : '';
        $usersService = new UsersCRUD($this->app->pdo());
        $user = $usersService->read($uid);
        if (!$user->getId()) {
            return $this->app->renderView('json', [
                'data' => $user,
                'success' => $usersService->getSuccess(),
                'message' => $usersService->getMessage()
            ]);
        };

        $answerId = isset($payload['answerId']) ? $payload['answerId'] : null;
        $answersService = new AnswersCRUD($this->app->pdo());
        $answer = $answersService->read($answerId);
        if (!$answer->getId()) {
            return $this->app->renderView('json', [
                'success' => $answersService->getSuccess(),
                'message' => $answersService->getMessage()
            ]);
        }

        $votesService = new VotesCRUD($this->app->pdo());
        $votesService->create($user, $answer, $payload);
        return $this->app->renderView('json', [
            'data' => [],
            'success' => $votesService->getSuccess(),
            'message' => $votesService->getMessage()
        ]);
    }
}