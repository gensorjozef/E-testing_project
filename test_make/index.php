<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../Database.php";
$conn = (new Database())->createConnection();
$questions_id = array();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src='https://unpkg.com/mathlive/dist/mathlive.min.js'></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://zwibbler.com/zwibbler-demo.js"></script>
    <style>
        [z-canvas] {
            width: 600px;
            height: 450px;
        }

        .topright {
            position: absolute;
            top: 8px;
            right: 16px;
            font-size: 18px;
            width: 15%;
            z-index: 10;
        }

        body {
            background-color: #B5EADD !important;
        }

        #myCanvas label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="topright position-sticky m-2 bg-light text-center border border-dark">Test skončí za <b id="time"></b> minút!</div>
    <?php
    $stmt_status = $conn->prepare("SELECT status FROM test where test_key=:test_key");
    $stmt_status->bindParam(":test_key", $_POST['code']);
    $stmt_status->execute();
    $status = $stmt_status->fetch();

    if (isset($_POST['code']) && $status["status"] == "on") {
        $start_test = date('Y-m-d H:i:s', strtotime('2 hour'));
        $test_key = $_POST['code'];
        $stmt = $conn->prepare("SELECT * FROM question where test_key=:test_key");
        $stmt->bindParam(":test_key", $test_key);
        $stmt->execute();
        $result = $stmt->fetchAll();
        // select duration testu
        $stmt_duration_test = $conn->prepare("SELECT test_duration FROM test where test_key='{$test_key}'");
        $stmt_duration_test->execute();
        $duration_test = $stmt_duration_test->fetch();
        $duration = $duration_test["test_duration"];
        // insert vytvorenia student_test
        if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['ais_id'])) {
            $sql_student_test = "INSERT INTO student_test (name, surname, ais_id, test_key, start_test) VALUES (?,?,?,?,?)";
            $stm_student_test = $conn->prepare($sql_student_test);
            $stm_student_test->execute([$_POST['name'], $_POST['surname'], $_POST['ais_id'], $test_key, $start_test]);
            $student_test_id = $conn->lastInsertId();
        }
        if ($result == null) {
            // header('Location: ../index.php');
            echo "nenabindovane";
        }
    ?>
        <form method="post" id="testForm" action="checkTest.php" onsubmit="submitTest()" class="container">
            <input type="hidden" name="test_code" id="test_code" value=<?php echo $test_key ?>>
            <input type="hidden" name="student_test" id="student_test" value=<?php echo $student_test_id ?>>
            <?php
            foreach ($result as $question) {
                if ($question["type_answer"] == "multiple") {
                    echo '<div class="otazka border border-dark m-5 p-3 form-group bg-light"><b>';
                    $id_question = $question["id"];
                    echo $question["title"] . "</b><br>";
                    $stmt = $conn->prepare("SELECT * FROM answer where 	id_question=:id_question");
                    $stmt->bindParam(":id_question", $id_question);
                    $stmt->execute();
                    $resultAnsw = $stmt->fetchAll();
                    foreach ($resultAnsw as $index => $answer) {
            ?>
                        <input type="checkbox" name="<?php echo 'Q_' . $question["id"] . '_A_' . $answer["id"] ?>" id="<?php echo 'Q_' . $question["id"] . '_A_' . $answer["id"] ?>">
                        <span><?php echo $answer["answer"] ?></span><br>
            <?php
                    }
                    echo '</div>';
                } elseif ($question["type_answer"] == "short") {
                    echo '<div class="otazka border border-dark m-5 p-3 form-group bg-light">';
                    echo '<div class="form-group row">';
                    echo '<label class="col-sm-2 col-form-label"><b>' . "{$question['title']}" . '</b></label>';
                    echo '<input type="text" class="col-sm-2 form-control" name="shortA' . "{$question['id']}" . '" id=' . "{$question['id']}" . '>';
                    echo '</div>';
                    echo '</div>';
                } elseif ($question["type_answer"] == "draw") {
                    echo '<div class="otazka border border-dark m-5 p-3 form-group bg-light"><b>';
                    echo $question["title"] . " Pred odovzdaním testu stlačte 'SAVE'.<br>";
                    echo ' <zwibbler z-controller="mycontroller">';
                    echo '        <button z-click="ctx.newDocument()">New</button>';
                    echo '      <button z-click="mySave(' . $question["id"] . ')">Save</button>';
                    echo '      <button z-click="myOpen()">Open</button>';
                    echo '      <div class="border border-dark m-auto" z-canvas></div>';
                    echo '   </zwibbler>';
                    echo  '<input type="hidden" id="drawHidden' . $question["id"] . '" name="DrawA' . $question["id"] . '">';
                    echo '</div>';
                } elseif ($question["type_answer"] == "math") {
                    echo '<div class="otazka border border-dark m-5 p-3 form-group bg-light">';
                    echo '
                              <div><b>Title: ' . $question["title"] . '</b></div>
                          <div>
                              <label>Answer:</label>
                              <input type="hidden" id="mathAHidden' . $question["id"] . '" name="mathA' . $question["id"] . '">
                              <math-field class="mathA border border-dark" onchange="changeHiddenAnswer(this, ' . $question["id"] . ')" virtual-keyboard-mode="manual"></math-field>
                          </div><br>';
                    echo '</div>';
                }
            }
            echo '<input type="hidden" name="deleteActivity" value="' . $_POST["ais_id"] . '">';
            ?>
            <div class="otazka border border-dark m-5 p-3 bg-light" id="reset-div">
                <div id="myCanvas"></div>
                <button type="button" onclick=rst() class="btn btn-primary border border-dark pl-5 pr-5">Obnoviť spájacie otázky</button>
            </div>
            <div class="m-5 p-3 text-center">
                <button type="submit" class="btn btn-success border border-dark pl-5 pr-5 m-3">Odovzdať test</button>
            </div>
        </form>
    <?php
    } else {
        $message = "Test pre daný kľúč ešte nebol spustený.";
        echo "<script type='text/javascript'>alert('$message');</script>";
        header("location: ../index.php");
    }
    ?>
    <script>
        function onVisibilityChange() {
            var hidden = false;
            if (document.hidden) {
                hidden = true;
            } else {
                hidden = false;
            }
            const request = new Request('../user_activity/controller.php', {
                method: 'POST',
                body: JSON.stringify({
                    ais_id: <?php echo $_POST["ais_id"] ?>,
                    hidden: hidden,
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            });
            fetch(request);
        }
        onVisibilityChange();
        document.addEventListener('visibilitychange', onVisibilityChange);



        function changeHiddenAnswer(answer, qId) {
            var answerHidden = document.getElementById("mathAHidden" + qId);
            console.log(answer.value);
            answerHidden.value = answer.value;
        }

        function submitTest() {
            submit();
            document.getElementById("testForm").submit();
        }
        if (document.visibilityState == 'unloaded') {
            submitTest();
            alert(1);
        }
    </script>
    <script>
        var saved = "";

        function getSaved() {
            return saved;
        }
        Zwibbler.controller("mycontroller", (scope) => {
            const ctx = scope.ctx;
            scope.mySave = (qId) => {
                saved = ctx.save();
                alert(saved);
                var answerHidden = document.getElementById("drawHidden" + qId);

                answerHidden.value = saved;
            }
            scope.myOpen = () => {
                if (!saved) {
                    alert("Please save first.");
                    return;
                }
                ctx.load(saved);
            }
        })
    </script>
    <script>
        function startTimer(duration, display) {
            var timer = duration,
                minutes, seconds;
            setInterval(function() {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    submitTest();
                }
            }, 1000);
        }

        window.onload = function() {

            var fiveMinutes = 60 * "<?php echo $duration; ?>",
                display = document.querySelector('#time');
            startTimer(fiveMinutes, display);
        };
    </script>
    <script src='answers_js/getTest.js'></script>
</body>

</html>