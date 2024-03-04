<?php
require_once '../vendor/autoload.php';
use Armaulan\DmsTelegram\Class\Database;
use Armaulan\DmsTelegram\Class\Telegram;

$tlgrm = new Telegram('BOT_RTPDM');

$db = new Database();
try {
    $db->getConnection("AGENT");
} catch (PDOException $e) {
    echo "Connection DB Issue";
    exit();
}   

$sql ="
    select 
    wul.log_datetime as web_log
    , (select aul.login_datetime  from android_user_log aul order by aul.android_user_id desc limit 1) apk_log
    , (select pk.created from penerimaan_kasir pk order by pk.penerimaan_kasir_id desc limit 1) pk_log
    , (select as2.created from agent_selling as2 order by as2.selling_id desc limit 1) selling_log
    , (select created from agent_order_data order by order_id desc limit 1) chatbot_log
    from web_user_log wul 
    order by web_user_log_id desc
    limit 1";

$data = $db->getAgentData($sql);
$msg = "";

if(!empty($data)){ 
    foreach ($data as $row) {
        $msg .= "android_log : " . $row['apk_log'] . "\n" .
        "pk_log : " . $row['pk_log'] . "\n" .
        "chatbot_log : " . $row['chatbot_log'] . "\n" .
        "selling_log : " . $row['selling_log'] 
        . "\n \n";
    }

    $tlgrm->sendMessage($sender, "Last Transaction Agent");
    $tlgrm->sendMessage($sender, $msg);
    echo "OK !";
    exit();
}
