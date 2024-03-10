<?php

# ../app/webhook/telegram-01.php
# echo $argv[1];

if($_SERVER['REQUEST_METHOD'] != "POST"){
    header("HTTP/1.0 404 Not Found");
    echo "Method is not allowed !";
    exit();
}

$data = json_decode(file_get_contents("php://input"));
$sender = $data->message->chat->id;
$message = $data->message->text;
$messageArray = explode(" ", $message);
$messageFirst = strtolower($messageArray[0]);
$messageSecond;

if (isset($messageArray[1])) {
    $messageSecond = strtolower($messageArray[1]);
}
# echo "$messageFirst -> $messageSecond";

$list = array(
    '1234' => '../src/Cmd/Agent/agent-chatbot-getorder.php',
    'rtpdm-ping' => '../src/Cmd/General/ping.php',
    'rtpdm-ping-server' => '../src/Cmd/Server/server-ping.php',
    'rtpdm-delay' => '../src/Cmd/Server/Server-delay.php',
    'rtpdm-agent-last' => '../src/Cmd/Agent/agent-last-trans.php',
    'rtpdm-get-query' => '../src/Cmd/Server/server-slowquery-catch.php',
    'rtpdm-cpu' => '../src/Cmd/Server/server-usage.php'
);

if(isset($list[$messageFirst])){
    include $list[$messageFirst];
    
} else {
    echo "Command is not found !";
    exit();

}