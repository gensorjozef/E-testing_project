<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once "../../Database.php";
$conn = (new Database())->createConnection();

$title = $_POST["title"];
$points = $_POST["points"];
$test_code = $_POST["test_code"];


$stmtQ = $conn->prepare("INSERT INTO question (test_key, type_answer, title, points) VALUES (:test_key, 'draw', :title, :points)");
$stmtQ->bindParam(':title', $title);
$stmtQ->bindParam(':points', $points);
$stmtQ->bindParam(':test_key', $test_code);

$stmtQ->execute();

$questionId = $conn->lastInsertId();
header("Location: ../addTest.php?test_code=".$test_code);
?>