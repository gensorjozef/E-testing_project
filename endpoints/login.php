<?php
header("Content-type: application/json");
require_once "../Database.php";
function login(){
    $data = json_decode(file_get_contents('php://input'), true);
    $ais_id = $data["ais_id"];
    $pass = $data["pass"];
    $conn = (new Database())->createConnection();
    $strm = $conn->prepare('SELECT * from teacher where ais_id = :ais_id');
    $strm->bindParam(":ais_id",$ais_id);
    $strm->execute();
    $user = $strm->fetch();
    if($user){
        if(password_verify($pass,$user["password"])==true){
            echo json_encode(["msg"=>"success"]);
        }
        else{
            echo json_encode(["msg"=>"failed"]);
        }
    }else{
        echo json_encode(["msg"=>"failed"]);
    }

}
try {
    login();
}catch (Exception $exception){
    echo $exception;
}
