<?php
header("Content-type: application/json");
require_once "../../Database.php";
$conn = (new Database())->createConnection();
$data = json_decode(file_get_contents('php://input'), true);
$testKEY = $data["test_key"];
foreach ($data["result"] as $item){
    $answer = $item["pairs"];
    $questionID = $item["id"];
    $points = $item["goodAns"];
    $points = $points*2;
    $shufle = json_encode($item["shufle"]);
    $strm = $conn->prepare("insert IGNORE into student_answer(student_test_id,question_id,answer,points,shufle) values (:student_test_id,:question_id,:answer,:points,:shufle)");
    $strm->bindParam(":student_test_id",$testKEY);
    $strm->bindParam(":shufle",$shufle);
    $strm->bindParam(":question_id",$questionID);
    $strm->bindParam(":points",$points,PDO::PARAM_INT);
    $strm->bindParam(":answer",$answer);
    $strm->execute();
}
echo json_encode($data);
