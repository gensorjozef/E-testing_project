<?php

header('Content-type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json);

$activity_file = file_get_contents('activity.json');
$activity = json_decode($activity_file, true);

$activity[$data->ais_id] = $data->hidden;

file_put_contents('activity.json', json_encode($activity));

echo "success";
