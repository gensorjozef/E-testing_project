<?php
header("Content-type: application/json");
require_once "../../Database.php";
$conn = (new Database())->createConnection();
$data = json_decode(file_get_contents('php://input'), true);
$myMap = $data["map"];
$myMap = str_replace("[","",$myMap);
$myMap = str_replace("]","",$myMap);
$myMap = str_replace("\"","",$myMap);
$myMapArr = str_getcsv($myMap,",");
$questions = [];
$answers = [];
$x = 1;
for ($i = 0; $i<sizeof($myMapArr); $i++){
    if($x%2==0){
        array_push($answers,$myMapArr[$i]);
    }else{
        array_push($questions,$myMapArr[$i]);
    }
    $x++;
}
$Q = "";
$A = "";
$points = 0;
for ($i = 0; $i<sizeof($questions); $i++){
    $Q .= $questions[$i].",";
    $A .= $answers[$i].",";
    $points += 2;
}
$id_question=0;
$j = $data["testKey"];
$type = "pair";
$strm = $conn->prepare("insert IGNORE into question(test_key,type_answer,title,points) values (:test_key,:type_answer,:title,:points)");
$strm2 = $conn->prepare("insert IGNORE into answer(id_question,answer) values (:id_question,:answer)");
$count = sizeof($questions);
if($count < 6){
    $strm->bindParam(":title",$Q);
    $strm->bindParam(":test_key",$j);
    $strm->bindParam(":type_answer",$type);
    $strm->bindParam(":points",$points,PDO::PARAM_INT);
    $strm->execute();
    $id_question = $conn->lastInsertId();
    $strm2->bindParam(":id_question",$id_question,PDO::PARAM_INT);
    $strm2->bindParam(":answer",$A);
    $strm2->execute();
    $x++;
    echo json_encode(["status"=>"success","questions"=>$Q,"answers"=>$A,"jaj"=>sizeof($questions)]);
}else{
    echo json_encode(["status"=>"failed",$count]);
}
?>
