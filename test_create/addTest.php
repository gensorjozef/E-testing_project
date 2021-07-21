<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_key = md5(uniqid($_POST["title"], true));
    require_once "../Database.php";
    $conn = (new Database())->createConnection();
    $stmt = $conn->prepare("INSERT IGNORE INTO test (test_key, id_teacher, test_duration, status, title) VALUES (?,?,?,?,?)");
    $teacher_id = $_POST["teacher_id"];
    $length = $_POST["length"];
    $available = $_POST["available"];
    $title = $_POST["title"];
    $stmt->execute([$test_key, $teacher_id, $length, $available, $title]);
    if($stmt->rowCount() <= 0){
        echo "ROVNAKY NAZOV TESTU";
        sleep(10);
        header("Location: ../index.php");
    }
}
if(isset($_GET["test_code"])){
    $test_key = $_GET["test_code"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/basics.css">
    <title>Pridaj test</title>
</head>

<body>
    <div class="container mt-4 rounded col-md-6">
    <div class="p-4 row">
        <div class="m-2 row justify-content-center">
            <a class="btn col-lg-5 text-light" href="./questions/addMulti.php?test_code=<?php echo $test_key ?>">Pridaj otázku s viac odpoveďami</a>
        </div>
        <div class="m-2 row justify-content-center">
            <a class="btn col-lg-5 text-light" href="./questions/addShort.php?test_code=<?php echo $test_key ?>">Pridaj otázku s krátkou odpoveďou</a>
        </div>
        <div class="m-2 row justify-content-center">
            <a class="btn col-lg-5 text-light" href="./questions/addDraw.php?test_code=<?php echo $test_key ?>">Pridaj otázku na kreslenie</a>
        </div>
        <div class="m-2 row justify-content-center">
            <a class="btn col-lg-5 text-light" href="./questions/addmath.php?test_code=<?php echo $test_key ?>">Pridaj matematickú otázku</a>
        </div>
        <div class="m-2 row justify-content-center">
            <a class="btn col-lg-5 text-light" href="./questions/addPair.php?test_code=<?php echo $test_key ?>">Pridaj spájaciu otázku</a>
        </div>
        <div class="m-2 row justify-content-center">
            <a class="btn col-lg-5 light-bgr" href="../index.php">Ukonči a vytvor test</a>
        </div>
    </div>
    </div>
</body>

</html>