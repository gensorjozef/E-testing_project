<?php
require_once "../../Database.php";
$conn = (new Database())->createConnection();

if (isset($_POST['title']) && isset($_POST['ans1']) && isset($_POST['ans2']) && !empty($_POST['ans1']) && !empty($_POST['ans2'])) {
    $title = $_POST['title'];
    if (isset($_POST['points'])) {
        $points = $_POST['points'];
    } else {
        $points = 0;
    }
    $stmt = $conn->prepare("INSERT INTO question (test_key, type_answer, title, points) VALUES (:test_key, :type_answer, :title, :points)");
    $test_key = $_POST["test_code"];
    $stmt->bindParam(":test_key", $test_key);

    $type_answer = "multiple";
    $stmt->bindParam(":type_answer", $type_answer);

    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":points", $points);
    $stmt->execute();
    $questionId = $conn->lastInsertId();
    $i = 1;
    while (isset($_POST['ans' . $i]) && !empty($_POST['ans' . $i])) {
        $stmt = $conn->prepare("INSERT INTO answer (id_question, answer, correct) VALUES (:id_question, :answer, :correct)");
        $stmt->bindParam(":id_question", $questionId);
        $ans = $_POST['ans' . $i];
        $stmt->bindParam(":answer", $ans);
        $correct = "Yes";
        if ($_POST['ans' . $i . 'ch'] == "on" && !empty($_POST['ans' . $i . 'ch'])) {
            $correct = "Yes";
        } else {
            $correct = "No";
        }
        $stmt->bindParam(":correct", $correct);
        $stmt->execute();
        $i++;
    }
    header("Location: ../addTest.php?test_code=" . $test_key);
} else {
    $test_key = $_POST["test_code"];
    echo "Nevyplneny nazov a aspon 2 prve odpovede <br>";
    ?>
    <a href="<?php echo '../addTest.php?test_code=' . $test_key?>">Naspäť</a>
    <?php
}
?>