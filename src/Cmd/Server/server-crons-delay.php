<?php
require_once '../vendor/autoload.php';
use Armaulan\DmsTelegram\Class\Telegram;

$tlgrm = new Telegram('BOT_RTPDM');

$jsonData = file_get_contents("https://dms-monitoring.pitjarus.co/check_slave_json");
$data = json_decode($jsonData, true);

$status = false;
$txt = "";

foreach ($data as $value) {
    
    $time =  date("Y-m-d h:i:s");
    $ip = $value['ip'];
    $delay = intval($value['sec_b_master']);
    
     
    if($delay >= 300 || $delay > 0){
        $status = true;
        $txt .= $ip. " | Delay : " .$delay. "\n" ;
    }
    
}

if($status) {
    # -1001638586770 Chatbot2
    # -1001991684578 Server
    $tlgrm->sendMessage('-1001991684578', "[Monitoring Server] - Delay Synch Status");
    $data = $tlgrm->sendMessage('-1001991684578', $txt);
}