<?php

namespace Polls\Controllers;

use Polls\Services\PollsCRUD;

class APIController extends Controller
{
    public function createPoll()
    {
        $pollsService = new PollsCRUD($this->pdo());
        $poll = $pollsService->create($this->payload());
        var_dump($poll);
    }
}