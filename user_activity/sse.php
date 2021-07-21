<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

while(true){
    $activity = json_decode(file_get_contents('activity.json'));
    $data = json_encode($activity);
    sendSse($data);
    flush();
    sleep(1);
}

function sendSse($data){
    echo "event: evt\n";
    echo "data: {$data}\n\n";

    ob_flush();
    flush();
}