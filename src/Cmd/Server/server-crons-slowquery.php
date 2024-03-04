<?php
require_once '../vendor/autoload.php';
use Armaulan\DmsTelegram\Class\Telegram;

$tlgrm = new Telegram('BOT_RTPDM');

$json = file_get_contents("https://dms-monitoring.pitjarus.co/process_list_json");
$array = json_decode($json, true);

$output = [];
$msg = "";
foreach($array as $item){
    
    foreach($item['result'] as $item2) {
       $temp_array = [];
       
       $temp_server = $item['server'];
       $temp_command = $item2['COMMAND'];
       $temp_state = "";
       if(isset($item2['INFO'])) {
        $temp_state = $item2['STATE'];
        } 
       $temp_time = (int)$item2['TIME'];
       $temp_time_minute = $temp_time /60;
       $temp_id = $item2['ID'];
       $temp_user = $item2['USER'];
       $temp_db = $item2['DB'];
       $temp_host = $item2['HOST'];
       $temp_info = "";
       if(isset($item2['INFO'])) {
            $temp_info = substr($item2['INFO'], 0, 20);
       } 
    
       if( ($temp_command == "Query" && $temp_time > 350) ) {
           $temp_array['server'] = $temp_server;
           $temp_array['id'] = $temp_id;
           $temp_array['user'] = $temp_user;
           $temp_array['db'] = $temp_db;
           $temp_array['user'] = $temp_user;
           $temp_array['state'] = $temp_state;
           $temp_array['host'] = $temp_host;
           $temp_array['time'] = number_format((double)$temp_time_minute, 2, '.', '') . " minutes";
           $temp_array['info'] = $temp_info;
           
           array_push($output, $temp_array);
       }
    }
}

if(count($output) == 0) {
    echo "Clear!";
    return "Clear!";
}

foreach($output as $row){
    $msg .= "Server : " . $row['server'] . "\n" .
            "Id : " . $row['id'] . "\n" .
            "User : " . $row['user'] . "\n" .
            "Host : " . $row['host'] . "\n" .
            "Db : " . $row['db'] . "\n" .
            "State : " . $row['state'] . "\n" .
            "time : " . $row['time'] . "\n" .
            "Query : " . $row['info'] . "\n \n" ;
}

$data = $tlgrm->sendMessage('-1001991684578', $msg);
return "Success";