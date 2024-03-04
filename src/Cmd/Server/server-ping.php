<?php
require_once '../vendor/autoload.php';

use Armaulan\DmsTelegram\Class\Telegram;

$tlgrm = new Telegram('BOT_RTPDM');

function ping($host, $port, $timeout) { 
  $tB = microtime(true); 
  $fP = fSockOpen($host, $port, $errno, $errstr, $timeout); 
  if (!$fP) { return 0; } 
  $tA = microtime(true); 
  return (int)round((($tA - $tB) * 1000), 0); 
}

$data = [];
$text = "";

array_push($data, [ "103.103.192.192", ping("103.103.192.192", 3306, 3) ]);
array_push($data, [ "103.103.192.190", ping("103.103.192.190", 3306, 3) ]);
array_push($data, [ "34.101.94.130", ping("34.101.94.130", 3306, 3) ]);
array_push($data, [ "dms-agent.pitjarus.co", ping("dms-agent.pitjarus.co", 443, 3) ]);
array_push($data, [ "dms.sariroti.com", ping("dms.sariroti.com", 443, 3) ]);
array_push($data, [ "rdws.sariroti.com", ping("rdws.sariroti.com", 443, 3) ]);
array_push($data, [ "dms-agent-api.pitjarus.co", ping("dms-agent-api.pitjarus.co", 443, 3) ]);
array_push($data, [ "dms-mainapi.pitjarus.co", ping("dms-mainapi.pitjarus.co", 443, 3) ]);
array_push($data, [ "dms-monitoring.pitjarus.co", ping("dms-monitoring.pitjarus.co", 443, 3) ]);
array_push($data, [ "103.93.53.15", ping("103.93.53.15", 443, 3) ]);
array_push($data, [ "103.93.53.50", ping("103.93.53.50", 443, 3) ]);


foreach($data as $item) {
  if(0==0) {
  	$text .= $item[0] . " : " . $item[1]. " \n\n" ;
  }
}

if( strlen($text) > 2 ) {
    $tlgrm->sendMessage($sender, $text);
	echo "Succeed";
	exit();
} 