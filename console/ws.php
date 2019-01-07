<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Polls\App;
use Polls\LiveResults;

$app = new App();
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new LiveResults()
        )
    ),
    $app->getConfigVar(['websocket', 'port'])
);

$server->run();