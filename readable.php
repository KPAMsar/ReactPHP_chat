<?php

require 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$readable = new React\Stream\ReadableResourceStream(STDIN, $loop);

$readable->on('data', function ($chunk){
    echo $chunk . PHP_EOL;

});

$loop->run();


