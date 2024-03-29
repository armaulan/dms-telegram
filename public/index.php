<?php
date_default_timezone_set('Asia/jakarta');

# set default index path
$path = '/index';

# update path according what user type in request
if(isset($_SERVER['PATH_INFO'])){
    $path = $_SERVER['PATH_INFO'];
}

$routes = array(
    # Undefined
    '/dist-last-trans' => '../src/Cmd/Dist/dist-last-trans.php',
    '/agent-chatbot-order' => '../src/Cmd/Agent/agent-chatbot-order.php',
    '/telegram-rtpdm' => '../src/Cmd/Webhook/telegram-rtpdm.php',
    '/dms-data-alerting' => '../src/Cmd/Monitoring/dms-data-alerting.php',
    '/dms-data-monitoring' => '../src/Cmd/Monitoring/dms-data-monitoring.php',
    '/dms-data-query' => '../src/Cmd/Monitoring/dms-data-query-download.php',

    # Server
    '/server-crons-delay' => '../src/Cmd/Server/server-crons-delay.php',
    '/server-crons-slowquery' => '../src/Cmd/Server/server-crons-slowquery.php',
    '/server-internal-usage' => '../src/Cmd/Server/server-usage.php'
);

if (isset($routes[$path])) {
    include $routes[$path];

} else {
    header("HTTP/1.0 404 Not Found");
    echo "Not Found";
    exit();
}