<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ODOVZDANY TEST</title>
</head>

<body>
    ODOVZDANY TEST
    <?php
    require_once "../Database.php";
    $conn = (new Database())->createConnection();
    $student_test_id = $_POST["student_test"];
    $end_test = date('Y-m-d H:i:s', strtotime('2 hour'));
    $points_test = 0;
    $allQA = array();
    foreach ($_POST as $key => $postElement) {
        $pieces = explode("_", $key);

        if (count($pieces) == 4) {
            if (!isset(${$pieces[1]})) {
                ${$pieces[1]} = array();
            }
            array_push(${$pieces[1]}, $pieces[3]);

            $question_id = $pieces[1];
            $answer_id = $pieces[3];
            $allQA[$pieces[1]] = ${$pieces[1]};
        }

        if (strcmp(substr($key, 0, 5), "mathA") == 0 || strcmp(substr($key, 0, 5), "DrawA") == 0) {
            $stmtQ = $conn->prepare("INSERT INTO student_answer (student_test_id, question_id, answer) VALUES (:student_test_id, :question_id, :answer)");
            $qId = substr($key, 5);
            $stmtQ->bindParam(':student_test_id', $student_test_id);
            $stmtQ->bindParam(':question_id', $qId);
            $stmtQ->bindParam(':answer', $postElement);
            $stmtQ->execute();
        }
            
        if (strcmp(substr($key, 0, 6), "shortA") == 0) {
            $poits = 0;
            $qId = substr($key, 6);            
            //vyber spravnej odpovede
            $sql_answer = "SELECT * FROM answer WHERE id_question = ?";
            $stm_answer = $conn->prepare($sql_answer);
            $stm_answer->execute([$qId]);
            $answer= $stm_answer->fetch(PDO::FETCH_ASSOC);
            //porovnanie spravnej a studentovej, prepocitanie bodov
            if (strcasecmp($answer["answer"], $postElement) == 0){
                $sql_points = "SELECT * FROM question WHERE id = ?";
                $stm_points = $conn->prepare($sql_points);
                $stm_points->execute([$qId]);
                $points= $stm_points->fetch(PDO::FETCH_ASSOC);
                $poits = $points["points"];
                $points_test += $poits;
            }
            $stmtQ = $conn->prepare("INSERT INTO student_answer (student_test_id, question_id, answer, points) VALUES (:student_test_id, :question_id, :answer, :points)");
            $stmtQ->bindParam(':student_test_id', $student_test_id);
            $stmtQ->bindParam(':question_id', $qId);
            $stmtQ->bindParam(':points', $poits);
            $stmtQ->bindParam(':answer', $postElement);
            $stmtQ->execute();
        }
            
    }
    $stmt = $conn->prepare("SELECT id FROM question where test_key=:test_key and type_answer=:type_answer");
    $test_key = $_POST["test_code"];
    $stmt->bindParam(":test_key", $test_key);
    $type_answer = "multiple";
    $stmt->bindParam(":type_answer", $type_answer);
    $stmt->execute();
    $allMultipleIds = $stmt->fetchAll();

    foreach ($allMultipleIds as $idDb) {
        $stmt = $conn->prepare("SELECT question.points as maxpoints FROM question where id=:id_question");
        $stmt->bindParam(":id_question", $idDb["id"]);
        $stmt->execute();
        $result = $stmt->fetch();
        $max_points = $result["maxpoints"];
        $stmt = $conn->prepare("SELECT * FROM answer where id_question=:id_question");
        $stmt->bindParam(":id_question", $idDb["id"]);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $max_answers = $stmt->rowCount();
        $correct_count = 0;
        $incorrect_count = 0;
        $stmtInsert = $conn->prepare("INSERT INTO student_answer (student_test_id, question_id, answer, points, shufle) VALUES (:student_test_id, :question_id, :answer, :points, :shufle)");
        $answer = "off";
        $points = 0.0;
        $shufle = "";
        if (array_key_exists($idDb["id"], $allQA)) {
            $qId = $idDb["id"];
            $stmtInsert->bindParam(':question_id', $qId);
            foreach ($result as $row) {
                if (in_array($row['id'], $allQA[$idDb["id"]]) && $row['correct'] == "yes") {
                    $answer = "on";
                    $points = $max_points / $max_answers;
                    $correct_count++;
                } elseif (in_array($row['id'], $allQA[$idDb["id"]]) && $row['correct'] == "no") {
                    $answer = "on";
                    $points = 0.0;
                    $incorrect_count++;
                } elseif (!in_array($row['id'], $allQA[$idDb["id"]]) && $row['correct'] == "yes") {
                    $answer = "off";
                    $points = 0.0;
                    $incorrect_count++;
                } elseif (!in_array($row['id'], $allQA[$idDb["id"]]) && $row['correct'] == "no") {
                    $answer = "off";
                    $points = $max_points / $max_answers;
                    $correct_count++;
                }else{
                    echo "PROBLEMS";
                }
                $shufle = $row['id'];
                $points_test += $points;
                $stmtInsert->bindParam(':student_test_id', $student_test_id);
                $stmtInsert->bindParam(':answer', $answer);
                $stmtInsert->bindParam(':points', $points);
                $stmtInsert->bindParam(':shufle', $shufle);
                $stmtInsert->execute();
            }
        } else {
            $qId = $idDb["id"];
            $answer = "no";
            $stmtInsert->bindParam(':question_id', $qId);
            foreach ($result as $row) {
                if ($row['correct'] == "no") {
                    $answer = "off";
                    $points = $max_points / $max_answers;
                    $correct_count++;
                } elseif ($row['correct'] == "yes") {
                    $answer = "off";
                    $points = 0.0;
                    $incorrect_count++;
                }
                $shufle = $row['id'];
                $points_test += $points;
                $stmtInsert->bindParam(':student_test_id', $student_test_id);
                $stmtInsert->bindParam(':answer', $answer);
                $stmtInsert->bindParam(':points', $points);
                $stmtInsert->bindParam(':shufle', $shufle);
                $stmtInsert->execute();
            }
        }
        $point_result = $max_points / $max_answers * $correct_count;

    }
    $type = "pair";
    $strm = $conn->prepare("SELECT SUM(student_answer.points) as points FROM student_answer 
                                        LEFT JOIN question on student_answer.question_id = question.id 
                                        WHERE question.type_answer = :type 
                                        and student_answer.student_test_id = :student_test_id");
    $strm->bindParam(":student_test_id",$student_test_id);
    $strm->bindParam(":type",$type);
    $strm->execute();
    $pnts = $strm->fetch();
    $points_test += $pnts["points"];


    $sql_update_student_test = "UPDATE student_test SET points=?, end_test=? WHERE id=?";
    $stm_update_student_test = $conn->prepare($sql_update_student_test);
    $stm_update_student_test->bindValue(1,$points_test);
    $stm_update_student_test->bindValue(2,$end_test);
    $stm_update_student_test->bindValue(3,$student_test_id);
    $stm_update_student_test->execute();


    //VYMAZANIE ACTIVITY
    $activity_file = file_get_contents('../user_activity/activity.json');
    $activity = json_decode($activity_file, true);
    unset($activity[$_POST["deleteActivity"]]);
    file_put_contents('../user_activity/activity.json', json_encode($activity));

    ?>
    <script>
        window.location.href = "../"
    </script>
</body>

</html>