<?php
require_once '../vendor/autoload.php';

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
$filePath = "../src/Query/" . $data["scriptName"] . ".sql";
$fileName = $data["scriptName"] . ".sql";

# If file is exist
if (file_exists($filePath)) {

    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
    header("Cache-Control: public"); // needed for internet explorer
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: Binary");
    header("Content-Length:".filesize($filePath));
    header("Content-Disposition: attachment; filename=$fileName");
    readfile($filePath);
    die();        

} else {
    die("Error: File not found.");
} 
