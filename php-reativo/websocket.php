<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\WsServer;
use Ratchet\WebSocket\MessageComponentInterface;

require_once 'vendor/autoload.php';

$chatComponent = new class implements MessageComponentInterface{
    private array $connections;

    public function __construct()
    {
        $this->connections = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "Nova conexão aceita".PHP_EOL;
        $this->connections[] = $conn;
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo "Conexão encerrada".PHP_EOL;
        // $this->connections->remove($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo 'Erro : ' .$e->getTraceAsString();
    }

    public function onMessage(ConnectionInterface $from, MessageInterface $msg)
    {
        foreach ($this->connections as $connection) {
            if($connection !== $from){
                $connection->send((string) $msg);
            }
        }
    }
};

$server = IoServer::factory(
                new HttpServer(
                    new WsServer($chatComponent)
                ),
                8002
            );


$server->run();