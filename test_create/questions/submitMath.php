<?php
require_once "../../Database.php";
$title = $_POST["title"];
$answer = $_POST["input_answer"];
$points = $_POST["points"];
$test_code = $_POST["test_code"];
$conn = (new Database())->createConnection();
$stmtQ = $conn->prepare("INSERT INTO question (test_key, type_answer, title, points) VALUES (:test_code, 'math', :title, :points)");
$stmtQ->bindParam(':title', $title);
$stmtQ->bindParam(':test_code', $test_code);
$stmtQ->bindParam(':points', $points);
$stmtQ->execute();

$questionId = $conn->lastInsertId();

$stmtA = $conn->prepare("INSERT INTO answer (id_question, answer) VALUES (:id_question, :answer)");
$stmtA->bindParam(':id_question', $questionId);
$stmtA->bindParam(':answer', $answer);
$stmtA->execute();

header("Location: ../addTest.php?test_code=".$test_code);
?>