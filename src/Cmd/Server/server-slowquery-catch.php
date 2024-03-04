<?php
require_once '../vendor/autoload.php';
use Armaulan\DmsTelegram\Class\Telegram;

$tlgrm = new Telegram('BOT_RTPDM');

$json = file_get_contents("https://dms-monitoring.pitjarus.co/process_list_json");
$array = json_decode($json, true);

$output = [];
$msg = "";

echo $messageSecond;

foreach($array as $item){
    foreach($item['result'] as $item2) {
       $temp_array = [];
       $temp_server = $item['server'];
       $temp_command = $item2['COMMAND'];
       $temp_state = $item2['STATE'];
       $temp_time = (int)$item2['TIME'];
       $temp_time_minute = $temp_time /60;
       $temp_id = $item2['ID'];
       $temp_user = $item2['USER'];
       $temp_db = $item2['DB'];
       $temp_info = $item2['INFO'];

       if($temp_id == $messageSecond ) {
        $output_filename = "sql-".$messageSecond."-script.txt";
        
        $fp = fopen("../temp/$output_filename", 'w');
        fwrite($fp, $temp_info);
        fclose($fp);

        $data = $tlgrm->sendDocument($sender, "../temp/$output_filename");
        unlink("../temp/$output_filename");

   }
    }
}

return "Success";