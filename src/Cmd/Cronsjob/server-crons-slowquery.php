<?php
$dotenv = parse_ini_file('.env', true);
$server = $dotenv['SERVER'];
$result = file_get_contents("$server/server-crons-slowquery");
echo $result;
exit();