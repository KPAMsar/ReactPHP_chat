<?php 

require 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$loop ->addTimer(2,function(){
    echo "After Timer\n";

});
echo "Before TImmer\n";

$loop ->run();

?>

