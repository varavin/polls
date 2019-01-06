<?php

namespace Polls\Controllers;

use Polls\Services\PollsCRUD;
use Polls\Services\UsersCRUD;

class APIController extends Controller
{
    public function createPoll()
    {
        $pollsService = new PollsCRUD($this->app->pdo());
        $poll = $pollsService->create($this->app->payload());
        return $this->app->renderView('json', ['object' => $poll]);
    }

    public function createUser()
    {
        $usersService = new UsersCRUD($this->app->pdo());
        $user = $usersService->create();
        return $this->app->renderView('json', ['object' => $user]);
    }

    public function readUser()
    {
        $usersService = new UsersCRUD($this->app->pdo());
        $user = $usersService->read($this->app->payload());
        return $this->app->renderView('json', ['object' => $user]);
    }
}