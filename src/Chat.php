<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    public $connect;
    protected  $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }


    public function onOpen(ConnectionInterface $conn) {

        $this->clients->attach($conn);
        echo "New conntection! ({$conn->resourceId})";
        print_r($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo $msg;
        $msgObject = json_decode($msg);
        foreach ($this->clients as $client) {
              $client->send($msgObject->fname.'Hello from server');

        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
