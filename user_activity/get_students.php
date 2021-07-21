<?php
require_once "../Database.php";

header('Content-type: application/json');
$json = file_get_contents('php://input');
$data = json_decode($json);
$key = $data->testKey;

$conn = (new Database())->createConnection();
$stmt = $conn->prepare("SELECT name, surname, title, end_test, ais_id FROM `student_test` 
                        LEFT JOIN test ON test.test_key = student_test.test_key WHERE test.test_key = :test_key");
$stmt->bindParam(':test_key', $key);                
$stmt->execute();
$students = $stmt->fetchAll();

echo json_encode($students);