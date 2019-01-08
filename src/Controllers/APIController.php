<?php

namespace Polls\Controllers;

use Polls\Services\AnswersCRUD;
use Polls\Services\PollsCRUD;
use Polls\Services\UsersCRUD;
use Polls\Services\VotesCRUD;

class APIController extends Controller
{
    public function createPoll(array $payload = [])
    {
        $pollsService = new PollsCRUD($this->pdo());
        $poll = $pollsService->create($payload);
        return $this->view()->render('json', [
            'data' => $poll,
            'success' => $pollsService->getSuccess(),
            'message' => $pollsService->getMessage()
        ]);
    }

    public function createUser()
    {
        $usersService = new UsersCRUD($this->pdo());
        $user = $usersService->create();
        return $this->view()->render('json', [
            'data' => $user,
            'success' => $usersService->getSuccess(),
            'message' => $usersService->getMessage()
        ]);
    }

    public function readUser(array $payload)
    {
        $uid = isset($payload[0]) ? $payload[0] : ''; // element [0] is the user's UID from the request URI: /api/user/47c92b728a49f8d2460c6aa9ecaf1123
        $usersService = new UsersCRUD($this->pdo());
        $user = $usersService->read($uid);
        return $this->view()->render('json', [
            'data' => $user,
            'success' => $usersService->getSuccess(),
            'message' => $usersService->getMessage()
        ]);
    }

    public function createVote(array $payload)
    {
        $uid = isset($payload['userUid']) ? $payload['userUid'] : '';
        $usersService = new UsersCRUD($this->pdo());
        $user = $usersService->read($uid);
        if (!$user->getId()) {
            return $this->view()->render('json', [
                'data' => [],
                'success' => $usersService->getSuccess(),
                'message' => $usersService->getMessage()
            ]);
        };

        $answerId = isset($payload['answerId']) ? $payload['answerId'] : null;
        $pollsService = new PollsCRUD($this->pdo());
        $poll = $pollsService->getByAnswerId($answerId);
        if (!$poll->getId()) {
            return $this->view()->render('json', [
                'data' => [],
                'success' => $pollsService->getSuccess(),
                'message' => $pollsService->getMessage()
            ]);
        };

        $votesService = new VotesCRUD($this->pdo());
        $vote = $votesService->create($user, $poll, $payload);
        if (!$vote->getId()) {
            return $this->view()->render('json', [
                'data' => [],
                'success' => $votesService->getSuccess(),
                'message' => $votesService->getMessage()
            ]);
        }

        return $this->view()->render('json', [
            'data' => $poll->getResults(),
            'success' => $votesService->getSuccess(),
            'message' => $votesService->getMessage()
        ]);
    }
}