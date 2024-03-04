<?php
#file_get_contents("https://tlgrm.tobbaca.my.id/server-crons-delay");
$dotenv = parse_ini_file('.env', true);
$server = $dotenv['SERVER'];
file_get_contents("$server/server-crons-delay");
exit();