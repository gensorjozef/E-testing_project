<?php
require_once "../Database.php";

$key = $_POST["test_key"];

$conn = (new Database())->createConnection();
$stmt = $conn->prepare("SELECT title FROM test WHERE test.test_key = :test_key");
$stmt->bindParam(':test_key', $key);
$stmt->execute();
$title = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <title>User activity</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Itim&display=swap');

    body {
        background-image: url("../images/bgr2_t.jpg");
        background-size: cover;
        background-attachment: fixed;
        /* background-color: #96d9c7; */
    }
    .red{
        color: red;
    }
    .green{
        color: green;
    }
    .container{
        background-color: #B5EADD !important;
    }
    .btn{
        background-color: #6e24ce !important;
    }
</style>

<body>
    <div class="container bg-info rounded pb-3 mt-4">
        <div class="p-3 row">
            <div >
                <a class="btn text-light" href="../teacher.php">Naspäť</a>
            </div>
        </div>
        <div class="row p-2 m-3 text-center justify-content-center bg-light rounded">
            <div class="col-sm-3">Kód testu je: </div>
            <div class="col-sm-3 overflow-auto" id="testKey"><?php echo $_POST["test_key"] ?></div>
        </div>
        <div class="row p-2 m-3 text-center justify-content-center bg-light rounded">
            <div class="col-sm-3">Názov testu je:</div>
            <div class="col-sm-3"><?php echo $title["title"] ?></div>
        </div>
        <div class="row p-3 bg-light m-3 rounded">
            <table id='table' class="table table-light text-center col">
                <tr>
                    <th>Študent</th>
                    <th>Aktívny</th>
                    <th>Na inom tabe</th>
                </tr>
            </table>
        </div>
    </div>
    <!-- <div id="result"></div> -->
</body>
<script>
    if (!window.sessionStorage.getItem("ais_id") && !window.sessionStorage.getItem("pass")) {
        window.location.href = "../index.php"
    }
</script>
<script src="./script.js"></script>

</html>