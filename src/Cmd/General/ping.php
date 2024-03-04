<?php
require_once '../vendor/autoload.php';

use Armaulan\DmsTelegram\Class\Telegram;

$tlgrm = new Telegram('BOT_RTPDM');
$tlgrm->sendMessage($sender, $messageSecond);