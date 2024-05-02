<?php

function logger($message){
    $timestamp = date('Y-m-d h:i:s');
    file_put_contents('logs.log',"[$timestamp]: $message\n",FILE_APPEND);
}

?>