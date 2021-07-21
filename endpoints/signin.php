<?php
header("Content-type: application/json");
require_once "../Database.php";
$conn = (new Database())->createConnection();
$data = json_decode(file_get_contents('php://input'), true);
$name = $data["name"];
$surname = $data["surname"];
$pass = $data["pass"];
$ais_id = $data["ais_id"];
$password = password_hash($pass,PASSWORD_DEFAULT);
$strm = $conn->prepare("insert IGNORE into teacher(ais_id,name,surname,password) values (:ais_id,:name,:surname,:password)");
$strm->bindParam(":name",$name);
$strm->bindParam(":ais_id",$ais_id);
$strm->bindParam(":surname",$surname);
$strm->bindParam(":password",$password);
$strm->execute();
echo json_encode(["msg"=>"Ucitelove konto bolo vytvorene"])
?>
