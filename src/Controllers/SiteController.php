<?php

namespace Polls\Controllers;

use Polls\Services\PollsCRUD;

class SiteController extends Controller
{
    public function index()
    {
        return $this->app->renderView('index');
    }

    public function poll(string $uid)
    {
        $pollsService = new PollsCRUD($this->app->pdo());
        $poll = $pollsService->read(0, $uid);
        $results = $pollsService->getResults($poll->getId());
        return $this->app->renderView('poll', compact('poll', 'results'));
    }
}