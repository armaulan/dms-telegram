<?php
require_once '../vendor/autoload.php';
use Armaulan\DmsTelegram\Class\Database;
use Armaulan\DmsTelegram\Class\Telegram;

$db = new Database();

try {
    $db->getConnection("AGENT");
} catch (PDOException $e) {
    echo "Connection DB Issue";
    exit();
}

#$data = $db->getAgentData("select ul.username from user_login ul order by ul.user_id limit 2");
#var_dump($data);

$tlgrm = new Telegram('BOT_RTPDM');
$tlgrm->sendMessage('-1001638586770', 'Rtpdm-test');
