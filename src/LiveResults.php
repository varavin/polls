<?php
namespace Polls;
use Polls\Services\PollsCRUD;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Polls\App;

class LiveResults implements MessageComponentInterface
{
    protected $clients;
    private $subscriptions;
    private $users;
    private $app;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->subscriptions = [];
        $this->users = [];

        $this->app = new App();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        $data = json_decode($msg);
        switch ($data->command) {
            case "subscribe":
                $this->subscriptions[$conn->resourceId] = $data->channel;
                break;
            case "message":
                if (isset($this->subscriptions[$conn->resourceId])) {
                    $target = $this->subscriptions[$conn->resourceId];
                    foreach ($this->subscriptions as $id=>$channel) {
                        if ($channel == $target && $id != $conn->resourceId) {
                            $pollsService = new PollsCRUD($this->app->pdo());
                            $poll = $pollsService->read(0, $channel);
                            $results = $pollsService->getResults($poll->getId());
                            $this->users[$id]->send(json_encode($results));
                        }
                    }
                }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        unset($this->users[$conn->resourceId]);
        unset($this->subscriptions[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
?>