<?php
require_once "../Database.php";

$conn = (new Database())->createConnection();
$stmt = $conn->prepare("SELECT question.title, question.points, student_answer.points AS student_points, student_answer.answer, question.type_answer, student_answer.id AS answer_id, question.id AS question_id FROM `student_answer` 
                            LEFT JOIN question ON question.id = student_answer.question_id 
                            LEFT JOIN student_test ON student_test.id = student_answer.student_test_id 
                            WHERE student_test.ais_id = :ais_id AND student_test.test_key = :test_key");
$test_key = $_POST["test_key"];
$ais_id = $_POST["ais"];
$stmt->bindParam(":test_key", $test_key);
$stmt->bindParam(":ais_id", $ais_id);
$stmt->execute();
$answers = $stmt->fetchAll();

if($answers == null){
    ?>
    <script>
        window.location.href = "../teacher.php"
    </script>
    <?php
}
//^^^^^^^^^^^^ HORE JE ADAMOVE A VLADOVE ^^^^^^^^^^^^^

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../Database.php";
$conn = (new Database())->createConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_key = $_POST["test_key"];
    $ais = $_POST["ais"];
    $stmt = $conn->prepare("SELECT * FROM student_test where ais_id=:ais and test_key=:test_key");
    $stmt->bindParam(':ais', $ais);
    $stmt->bindParam(':test_key', $test_key);
    $stmt->execute();
    $result = $stmt->fetch();
    //     echo "<pre>";
    //     var_dump($result);
    //     echo "</pre>";
    $stmt = $conn->prepare("SELECT * FROM student_answer where student_test_id=:student_test_id");
    $student_test_id = $result["id"];
    $stmt->bindParam(':student_test_id', $student_test_id);
    $stmt->execute();
    $allStudentAnswers = $stmt->fetchAll();

    // echo "<pre>";
    // var_dump($allStudentAnswers);
    // echo "</pre>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail testu</title>
    <script src="https://zwibbler.com/zwibbler-demo.js"></script>
    <style>
        [z-canvas] {
            width: 600px;
            height: 450px;
        }

        body {
            background-color: #B5EADD !important;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="show_js/showTest.js"></script>
</head>

<body>
    <script>
        function toPDF() {
            var con = document.getElementById("con")
            html2pdf().from(con).save()
        }
    </script>
    <div id="con">
        <form method="post" id="detailForm" action="assignPoints.php" class="container">
            <div class="ml-5 mt-3 p-3"><h3><?php echo "Meno študenta: " . $result["name"] . " " . $result["surname"]?></h3></div>
            <div class="ml-5 p-3"><h3><?php echo "Získané body: " . $result["points"] ?></h3></div>
            <!-- NASLEDOVNE PHP JE ADAMOVE A VLADOVE -->
            <?php
            $obrazky = [];
            foreach ($answers as $key => $answer) {
                if($answer["student_points"] == null){
                    $ansPts = 0;
                }else{
                    $ansPts = $answer["student_points"];
                }
                if (strcmp($answer["type_answer"], "math") == 0) {

                    echo '<div class="otazka border border-dark m-5 p-3 form-group bg-light"><div>
                            <div><b>' . $answer["title"] . ' (' . $ansPts . '/' . $answer["points"] . 'b)</b></div>
                            <input type="hidden" id="mathPointsHidden' . $answer["answer_id"] . '" name="mathP' . $answer["answer_id"] . '" value=' . $answer["points"] . '>
                        </div>
                        <div>
                            <label>Answer:</label>
                            <math-field class="mathA border border-dark" read-only="true">' . $answer["answer"] . '</math-field>

                            <br>
                            <label>Je otázka správna?</label><br>
                            <input type="radio" name="mathA' . $answer["answer_id"] . '" value="yes">
                            <label>Ano</label>
                            <input type="radio" name="mathA' . $answer["answer_id"] . '" value="no">
                            <label>Nie</label>
                        </div></div>';
                }
                if ($answer["type_answer"] == "draw") {
                    array_push($obrazky, 'button' . $answer["question_id"]);


                    echo '<div class="otazka border border-dark m-5 p-3 form-group bg-light"><div><b>' . $answer["title"] . ' (' . $ansPts . '/' . $answer["points"] . 'b)</b></div>';
                    echo  '<input type="hidden" id="drawHidden' . $answer["question_id"] . '" name="DrawA' . $answer["question_id"] . '" value=' . $answer["answer"] . '>';
                    echo ' <zwibbler z-controller="mycontroller" showToolbar="false" showColourPanel="false"</div>';
                    echo '      <hidden button z-click="myOpen(' . $answer["question_id"] . ')" id="button' . $answer["question_id"] . '" ></button>';
                    echo '      <div z-canvas class="border border-dark m-auto overflow-auto"></div>';
                    echo '   </zwibbler>';
                    echo '<br>
                     <label>Je otázka správna?</label><br>
                     <input type="radio" name="drawA' . $answer["answer_id"] . '" value="yes">
                     <label>Ano</label>
                     <input type="radio" name="drawA' . $answer["answer_id"] . '" value="no">
                     <label>Nie</label>';
                    echo '<input type="hidden" id="drawPointsHidden' . $answer["answer_id"] . '" name="drawP' . $answer["answer_id"] . '" value=' . $answer["points"] . '>';
                    echo '</div>';
                }
                if (strcasecmp($answer["type_answer"], 'short') == 0) {
            ?>
                    <input type="hidden" name="student_test" id="student_test" value=<?php echo $student_test_id ?>>
            <?php
                    echo '<div class="otazka border border-dark m-5 p-3 form-group bg-light">';
                    echo '<div>';
                    echo '<label"><b>' . "{$answer['title']}" . '</b></label>';
                    echo '</div>';
                    $sql_correct_answer = "SELECT * FROM answer WHERE id_question = ?";
                    $stm_correct_answer = $conn->prepare($sql_correct_answer);
                    $stm_correct_answer->execute([$answer["question_id"]]);
                    $correct_answer = $stm_correct_answer->fetch(PDO::FETCH_ASSOC);
                    $sql_student_points = "SELECT points FROM student_answer WHERE question_id = ?";
                    $stm_student_points = $conn->prepare($sql_student_points);
                    $stm_student_points->execute([$answer["question_id"]]);
                    $student_points = $stm_student_points->fetch(PDO::FETCH_ASSOC);

                    if($answer['student_points'] == null){
                        $ansPts = 0;
                    }else{
                        $ansPts = $answer['student_points'];
                    }

                    echo '<div>';
                    echo '<label">' . "Správna Odpoveď: " . '</label>';
                    echo '<label">' . "{$correct_answer['answer']}" . '</label>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label">' . "Študentová Odpoveď: " . '</label>';
                    echo '<label">' . $answer["answer"] .  '</label>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label">' . "Bodové ohodnotenie: " . '</label>';
                    echo '<label">' . $ansPts . "/" . "{$answer["points"]}" . '</label>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label">' . "Zmeniť Body: " . '</label>';
                    echo '<input class="form-control col-sm-3" type="number" name="shortA' . $answer["answer_id"] . '" value="no" min="0">';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
            <!-- TU KONCI ADAMOVE A VLADOVE PHP -->

            <?php
            $multiQuestions = array();
            foreach ($allStudentAnswers as $answer) {
                $question_id = $answer["question_id"];
                $sql_question = "SELECT * FROM question WHERE id = ?";
                $stm_question = $conn->prepare($sql_question);
                $stm_question->execute([$answer["question_id"]]);
                $questionDetails = $question = $stm_question->fetch(PDO::FETCH_ASSOC);
                if ($questionDetails["type_answer"] == "multiple") {
                    if (!isset($multiQuestions[$question_id])) {
                        $multiQuestions[$question_id] = array();
                    }
                    $stmt = $conn->prepare("SELECT * FROM answer where id=:answer_id");
                    $answer_id = $answer["shufle"];
                    $stmt->bindParam(':answer_id', $answer_id);
                    $stmt->execute();
                    $answDetail = $stmt->fetch();

                    $allDetails["text_answer"] = $answDetail["answer"]; // text odpovede
                    $allDetails["correct_answer"] = $answDetail["correct"]; // yes alebo no
                    $allDetails["student_answer"] = $answer["answer"]; // on alebo off
                    $allDetails["points"] = $answer["points"]; // zisakne body za odpoved
                    ${$answDetail["id"]} = $allDetails;

                    $multiQuestions[$question_id]["title"] = $questionDetails["title"]; // nazov otazky
                    $multiQuestions[$question_id]["max_points"] = $questionDetails["points"]; // max body
                    array_push($multiQuestions[$question_id], $allDetails);
                }

                //            echo $answer["answer"] . "<br>";

            }
            foreach ($multiQuestions as $question) {
                echo '<div class="otazka border border-dark m-5 p-3 form-group bg-light">';
                echo "<b>" . $question["title"] . "</b>";
                echo "<p>Odpovede</p>";
                $totalPts = 0;
                foreach ($question as $index => $answ) {
                    if ($index == "title"  && $index != "0") {
                        continue;
                    }
                    if ($index == "max_points"  && $index != "0") {
                        continue;
                    }
            ?>
                    <label for="<?php echo $index ?>"> <?php echo $answ["text_answer"] ?></label>
                    <?php
                    if ($answ["student_answer"] == "on") {
                    ?>
                        <input type="checkbox" name="<?php echo $index ?>" checked disabled><br>
                    <?php
                    } else {
                    ?>
                        <input type="checkbox" name="<?php echo $index ?>" disabled><br>
                    <?php
                    }
                    $totalPts += $answ["points"];
                    ?>
                    <span>Získané body: <?php echo $answ["points"] ?> </span><br><br>
            <?php
                }
                // echo "<p>Max body spolu: " . $question["max_points"] . "</p>";
                echo "<p>Body: " . $totalPts . "/" . $question["max_points"] . "</p>";
                echo '</div>';
            }
            ?>
            <div id="myCanvas" class="otazka border border-dark m-5 p-3 bg-light"></div>
            <div class="m-5 p-3 text-center">
                <button type="submit" class="btn btn-success border border-dark pl-5 pr-5 m-3">Submit</button>
                <button type="button" class="btn btn-primary border border-dark pl-5 pr-5 m-3" onclick=toPDF()>toPDF</button>
                <a href="../teacher.php" class="btn btn-success border border-dark pl-5 pr-5 m-3">Naspäť</a>
            </div>
        </form>
        <?php

        ?>
        <script>
            showSubmited("<?php echo $student_test_id; ?>")
        </script>

        <!-- VLADOVE -->
        <script>
            var saved = "";

            function getSaved() {
                return saved;
            }
            window.onload = function() {
                var obrazky = <?php echo json_encode($obrazky); ?>;
                obrazky.forEach(element => document.getElementById(element).click());
            }

            Zwibbler.controller("mycontroller", (scope) => {
                const ctx = scope.ctx;
                scope.mySave = (qId) => {
                    saved = ctx.save();
                    alert(saved);
                    var answerHidden = document.getElementById("drawHidden" + qId);
                    // console.log(saved);
                    answerHidden.value = saved;
                }
                scope.myOpen = (qId) => {
                    var answerHidden = document.getElementById("drawHidden" + qId);
                    // console.log(saved);
                    saved = answerHidden.value;
                    if (!saved) {
                        // alert("Please save first.");
                        return;
                    }
                    ctx.load(saved);
                }

            })
        </script>
    </div>
</body>
<script src='https://unpkg.com/mathlive/dist/mathlive.min.js'></script>

</html>