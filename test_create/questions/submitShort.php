<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../../Database.php";
$conn = (new Database())->createConnection();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_REQUEST['questions_count'])) {
        $questions_count = $_REQUEST['questions_count'];

        for ($x = 1; $x <= $questions_count; $x++) {
            $sql_question = "INSERT INTO question (test_key, type_answer, title, points) VALUES (?,?,?,?)";
            $stm_question = $conn->prepare($sql_question);
            $test_key = $_POST["test_code"];
            $stm_question->execute([$test_key, 'short', $_REQUEST['question' . $x], $_REQUEST['points' . $x]]);
            $id_question = $conn->lastInsertId();

            $sql_answer = "INSERT INTO answer (id_question, answer) VALUES (?,?)";
            $stm_answer = $conn->prepare($sql_answer);
            $stm_answer->execute([$id_question, $_REQUEST['answer' . $x]]);
        }
    }
}
header("Location: ../addTest.php?test_code=".$test_key);