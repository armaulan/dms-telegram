<?php
require_once '../vendor/autoload.php';
use Armaulan\DmsTelegram\Class\Database;
use Dotenv\Dotenv;

if($_SERVER['REQUEST_METHOD'] != "GET"){
    header("HTTP/1.0 404 Not Found");
    echo "Method is not allowed !";
    exit();
}

$data = array();
parse_str($_SERVER['QUERY_STRING'], $data);

if(!isset($data["scriptName"])){
    header("HTTP/1.0 404 Not Found");
    echo "File name is not defined";
    exit();   
}

# Construct path for sql file directory
$scriptName = $data["scriptName"];
$filePath = "../src/Query/" . $scriptName . ".sql";

# If file is exist
if (file_exists($filePath)) {

    # Adding additional information for downloadble script
    $dotenv = Dotenv::createImmutable('../');
    $dotenv->load();
    $baseURL = $_ENV['SERVER']. "/dms-data-query?scriptName=". $scriptName;
    
    # Object Instantiation
    $db = new Database();

    # Prepare variable for result
    $queryResult = array("lists"=>[], "script"=>$baseURL);

    # Create DB CONNECTION
    try { 
        if(substr(strtolower($scriptName), 0, 5 ) == "agent") {
            $db->getConnection("AGENT");
            $queryResult["lists"] = $db->getAgentData(file_get_contents($filePath));

        } else if(substr(strtolower($scriptName), 0, 5 ) == "dist") {
            $db->getConnection("DISTRIBUTOR");
            $queryResult["lists"] = $db->getDistributorData(file_get_contents($filePath));
        }
        
    } catch (PDOException $e) {
        header("HTTP/1.0 500 Error");
        echo "Something Bad Happened: line-48";
        exit();
    }

    ob_end_clean(); // Clear any existing output buffering
    header("Content-Type: application/json");
    echo json_encode($queryResult);
    exit();

# If file doesn't exist
} else {
    header("HTTP/1.0 404 Not Found");
    echo "File not exist!";
    exit();
}
