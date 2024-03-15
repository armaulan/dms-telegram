<?php
require_once '../vendor/autoload.php';
use Armaulan\DmsTelegram\Class\Database;
use Armaulan\DmsTelegram\Class\Telegram;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;

if($_SERVER['REQUEST_METHOD'] != "POST"){
    header("HTTP/1.0 404 Not Found");
    echo "Method is not allowed !";
    exit();
}

# Get data , convert to array object, from post raw input
$data = json_decode(file_get_contents("php://input"));

# Construct path for sql file directory
$filePath = "../src/Query/" . $data->scriptName . ".sql";

# If file is exist
if (file_exists($filePath)) {
    
    # Create Log indicate that monitoring is triggered
    $log = new Logger('name');
    $log->pushHandler(new StreamHandler('../log/dms-monitoring-run.txt', Level::Debug));
    $log->info($data->scriptName. " run");

    # Object Instantiation
    $db = new Database();

    # Prepare variable for result
    $queryResult = [];

    # Create DB CONNECTION
    try { 
        if(substr(strtolower($data->scriptName), 0, 4 ) == "agen") {
            $db->getConnection("AGENT");
            $queryResult = $db->getAgentData(file_get_contents($filePath));

        } else if(substr(strtolower($data->scriptName), 0, 4 ) == "dist") {
            $db->getConnection("DISTRIBUTOR");
            $queryResult = $db->getDistributorData(file_get_contents($filePath));
        }
        
    } catch (PDOException $e) {
        header("HTTP/1.0 500 Error");
        echo "Something Bad Happened: line-48";
        exit();
    }

    # If data is exist
    if(count($queryResult) != 0) {
        $stringResult = "";
        $stringLog = "";

        # Loop each data and put into text variable
        foreach ($queryResult as $x => $y) {

            foreach ($y as $key => $value) {
                $stringResult .= $key. ": ". $value. "\n";
                $stringLog .= $key. ": ". $value. "/";
            }
            $stringResult .= "\n";           
    
            # Create Log: each row of issue found
            $log = new Logger('name');
            $log->pushHandler(new StreamHandler('../log/dms-monitoring-issue.txt', Level::Debug));
            $log->info($stringLog);
        }

        # Adding additional information for downloadble script
        $dotenv = Dotenv::createImmutable('../');
        $dotenv->load();
        $stringResult .= $_ENV['SERVER']. "/dms-data-monitoring?scriptName=". $data->scriptName;
        
        # Send to telegram
        $tlgrm = new Telegram('BOT_RTPDM');
        $tlgrm->setKey($data->botKey);
        $tlgrm->sendMessage($data->chatId, $stringResult);

        header("HTTP/1.0 200 Success");
        echo "Success !";
        exit();

    } else {

        # Create Log: no issue
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler('../log/dms-monitoring-noissue.txt', Level::Debug));
        $log->info("No Issue: ". $data->scriptName);

        if(isset($data->noIssueInfo)) {
            $tlgrm = new Telegram('BOT_RTPDM');
            $tlgrm->setKey($data->botKey);
            $tlgrm->sendMessage($data->noIssueInfo, "No Issue: ". $data->scriptName);

            header("HTTP/1.0 200 Success");
            echo "No Issue: Success !";
            exit();
        }

        header("HTTP/1.0 200 Success");
        echo "No Issue: Success !";
        exit();
    }

# If file doesn't exist
} else {
    header("HTTP/1.0 404 Not Found");
    echo "File not exist!";
    exit();
}
