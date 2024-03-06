<?php
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
    '/dms-data-monitoring' => '../src/Cmd/Monitoring/dms-data-qc.php',
    '/dms-query-monitoring' => '../src/Cmd/Monitoring/dms-data-query-download.php',

    # Server
    '/server-crons-delay' => '../src/Cmd/Server/server-crons-delay.php',
    '/server-crons-slowquery' => '../src/Cmd/Server/server-crons-slowquery.php'
);

if (isset($routes[$path])) {
    include $routes[$path];

} else {
    header("HTTP/1.0 404 Not Found");
    echo "Not Found";
    exit();
}