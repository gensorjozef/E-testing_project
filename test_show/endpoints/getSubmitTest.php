<?php
header("Content-type: application/json");
require_once "../../Database.php";
$conn = (new Database())->createConnection();
$data = json_decode(file_get_contents('php://input'), true);
$key = $data["key"];
$type = "pair";
$strm = $conn->prepare("SELECT *, student_answer.answer as pairs, student_answer.points as poin FROM `student_answer` 
                                INNER JOIN question on student_answer.question_id = question.id 
                                INNER JOIN answer on answer.id_question = question.id 
                                where question.type_answer = :type 
                                and student_answer.student_test_id = :test_key");
$strm->bindParam(":type",$type);
$strm->bindParam(":test_key",$key);
$strm->execute();
$response = $strm->fetchAll();
echo json_encode($response);
