<?php
require_once "../Database.php";

$conn = (new Database())->createConnection();
$stmt = $conn->prepare("UPDATE student_answer SET points = :points WHERE id = :id");
var_dump($_POST);
foreach ($_POST as $key => $postElement) {
    if (strcmp(substr($key, 0, 5), "mathA") == 0) {
        $qId = substr($key, 5);
        if(strcmp($_POST["mathA".$qId], "yes") == 0){
            $qPoints = $_POST["mathP".$qId];
        }else{
            $qPoints = 0;
        }
        $stmt->bindParam(":points", $qPoints);
        $stmt->bindParam(":id", $qId);
        $stmt->execute();
    }
    if (strcmp(substr($key, 0, 5), "drawA") == 0) {
        $qId = substr($key, 5);
        if(strcmp($_POST["drawA".$qId], "yes") == 0){
            $qPoints = $_POST["drawP".$qId];
        }else{
            $qPoints = 0;
        }
        $stmt->bindParam(":points", $qPoints);
        $stmt->bindParam(":id", $qId);
        $stmt->execute();
    }
    if (strcmp(substr($key, 0, 6), "shortA") == 0) {
        $qId = substr($key, 6);

        if ($_POST["shortA" . $qId] != "") {
            $qPoints = $_POST["shortA" . $qId];
            $stmt->bindParam(":points", $qPoints);
            $stmt->bindParam(":id", $qId);
            $stmt->execute();
        }
    }
}
$sql_points_after = "SELECT SUM(points) FROM student_answer WHERE student_test_id = ?";
$stm_points_after = $conn->prepare($sql_points_after);
$stm_points_after->execute([$_POST["student_test"]]);
$points_after = $stm_points_after->fetch(PDO::FETCH_ASSOC);

$sql_update_student_test = "UPDATE student_test SET points=? WHERE id=?";
$stm_update_student_test = $conn->prepare($sql_update_student_test);
$stm_update_student_test->bindValue(1,$points_after["SUM(points)"]);
$stm_update_student_test->bindValue(2,$_POST["student_test"]);
$stm_update_student_test->execute();
?>
<script>
    window.location.href = "../teacher.php"
</script>