<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Socket\ConnectionInterface;

require 'vendor/autoload.php';
require 'connectionPool.php';
$loop = React\EventLoop\Factory::create();

$pool = new connectionsPool();
$server = new React\Socket\Server('127.0.0.1:8080',$loop);
$server->on('connection',function(ConnectionInterface $connection) use ($pool){
   $pool->add($connection);
    echo $connection->getRemoteAddress() . PHP_EOL;

});

$loop->run();