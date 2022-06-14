<?php

require  'vendor/autoload.php';

use React\Socket\ConnectionInterface;
class ConnectionsPool 
{
    /** @var SplObjectStorage  */
    private $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }
    private function setConnectionData(ConnectionInterface $connection, $data)
    {
        $this->connections->offsetSet($connection, $data);
    }

    private function getConnectionData(ConnectionInterface $connection)
    {
        return $this->connections->offsetGet($connection);
    }

    public function add(ConnectionInterface $connection)
    {
       
        $connection->write("Enter your name: ");
        $this->initEvents($connection);
        $this->setConnectionData($connection, []);
    }

    /**
     * @param ConnectionInterface $connection
     */
    private function initEvents(ConnectionInterface $connection)
    {
        // On receiving the data we loop through other connections
        // from the pool and write this data to them
        $connection->on('data', function ($data) use ($connection) {
            $this->sendAll($data, $connection);
        });

        // When connection closes detach it from the pool
        $connection->on('close', function() use ($connection){
            $this->connections->detach($connection);
            $this->sendAll("A user leaves the chat\n", $connection);
        });
    }

    /**
     * Send data to all connections from the pool except
     * the specified one.
     *
     * @param mixed $data
     * @param ConnectionInterface $except
     */
    private function sendAll($data, ConnectionInterface $except) {
        foreach ($this->connections as $conn) {
            if ($conn != $except) $conn->write($data);
        }
    }
}

$loop = React\EventLoop\Factory::create();
$pool = new ConnectionsPool();
 $socket = new React\Socket\Server('127.0.0.1:8080', $loop);
// $socket->on('connection', function(ConnectionInterface  $connection){
//     $connection->write('Hi');
//     $connection->on('data', function($data) use ($connection){
//         $connection->write(strtoupper($data));
//     });
// });
$socket->on('connection', function(ConnectionInterface $connection) use ($pool){
    $pool->add($connection);
});
echo "Listening on {$socket->getAddress()}\n";

$loop->run();
