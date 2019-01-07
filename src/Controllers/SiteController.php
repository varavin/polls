<?php

namespace Polls\Controllers;

use Polls\Services\PollsCRUD;

class SiteController extends Controller
{
    public function index()
    {
        $apiURL = $this->appConfig['siteRootURL'] . '/api/';
        return $this->view()->render('index', compact('apiURL'));
    }

    public function poll(string $uid)
    {
        $pollsService = new PollsCRUD($this->pdo());
        $poll = $pollsService->read(0, $uid);
        if (!$poll->getId()) {
            $this->show404();
        }
        $results = $poll->getResults();
        $websocketString = 'ws://' . $this->appConfig['websocket']['host'] . ':' . $this->appConfig['websocket']['port'];
        $apiURL = $this->appConfig['siteRootURL'] . '/api/';
        return $this->view()->render('poll', compact('poll', 'results', 'websocketString', 'apiURL'));
    }
}