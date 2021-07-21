<?php
header("Content-type: application/json");
require_once "../../Database.php";
$conn = (new Database())->createConnection();
$data = json_decode(file_get_contents('php://input'), true);
$key = $data["key"];
$type = "pair";
$strm = $conn->prepare("SELECT title,answer FROM question join answer WHERE answer.id_question = question.id and test_key = :key and type_answer = :type");
$strm->bindParam(":key",$key);
$strm->bindParam(":type",$type);
$strm->execute();
$response = $strm->fetchAll();
echo json_encode($response);