<?php

use React\Socket\ConnectionInterface;

class connectionsPool{
    protected $connections;

    public function _construct(){
        $this->connections = new  SplObjectStorage();
    }
 public function add(ConnectionInterface $connection){
     $connection->write('Hellooo\n');
     $this->connections->attach($connection);

     $connection->on('data', function($data) use ($connection){
         foreach($this->connections as $conn){
             if($conn != $connection){
                 $conn->write($data);
             }
         }
     });

     $connection->on('close',function() use ($connection){
         $this->connections->detach($connection);
     });
 } 

}