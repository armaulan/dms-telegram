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
    $delay = $value['sec_b_master'];
    
    if(intval($delay) > -20 ){
        $status = true;
        $txt .= $ip. " | Sec_behind : " .$delay. "\n" ;
    }
    
}

if($status) {
    $tlgrm->sendMessage($sender, "[Monitoring Server] - Delay Synch Status");
    $tlgrm->sendMessage($sender, $txt);
}