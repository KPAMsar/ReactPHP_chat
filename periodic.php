<?php

require 'vendor/autoload.php';
$counter = 0;

 $loop = \React\EventLoop\Factory::create();

 $timer =$loop->addPeriodicTimer(1, function() use (&$counter,&$loop, &$timer){
     $counter++;
     if($counter === 5){
         $loop->cancelTimer($timer);
     }
    echo "Periodic\n";
});

$loop->run();
?>