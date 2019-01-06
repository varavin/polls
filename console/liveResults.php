<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Polls\LiveResults;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new LiveResults()
        )
    ),
    8888
);

$server->run();