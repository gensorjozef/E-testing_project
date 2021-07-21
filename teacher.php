<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "Database.php";
$conn = (new Database())->createConnection();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/teacherStyle.css">
</head>

<body>
    <?php
    if (!isset($_SESSION["ais_id"])) {
        $_SESSION["ais_id"] = $_POST["ais_idP"];
    }
    $sql_teacher = "SELECT * FROM teacher WHERE ais_id = ?";
    $stm_teacher = $conn->prepare($sql_teacher);
    $stm_teacher->execute([$_SESSION["ais_id"]]);
    $teacher = $stm_teacher->fetch(PDO::FETCH_ASSOC);

    ?>
    <div class="logout-btn">
        <button type="button" class="btn purple text-white" onclick=logout()>Odhlásiť</button>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-3 text-center inputs-area mb-3">
                <div class="add-test-div">
                    <a href="test_create/index.php?teacher_id=<?php echo $teacher["id"] ?>" class="text-white btn purple inputs-style">Vytvoriť nový test</a>
                </div>
                <div class="test-activity-div">
                    <h5 class="text-purple">Aktivity študentov na teste</h5>
                    <form method="post" action="user_activity/activity.php">
                        <input type="text" name="test_key" id="code" class="inputs-style" required placeholder="Kód testu">
                        <input type="submit" class="btn purple text-white inputs-style" value="Zobraziť">
                    </form>
                </div>
                <div class="test-detail-div">
                    <h5 class="text-purple">Zobrazenie detailu testu</h5>
                    <form method="post" action="test_show/testDetail.php">
                        <input type="text" name="ais" id="ais" class="inputs-style" placeholder="AIS ID" required>
                        <input type="text" name="test_key" id="test_key" class="inputs-style" placeholder="Kód testu" required><br>
                        <input type="submit" class="btn purple text-white inputs-style" value="Zobraziť">
                    </form>
                </div>
                <div class="csv-export-div">
                    <h5 class="text-purple">Exportovanie výsledkov testu ako csv</h5>
                    <form method="post" action="test_show/csvExport.php">
                        <input type="text" name="test_key" id="test_key" class="inputs-style" placeholder="Kód testu" required><br>
                        <input type="submit" class="btn purple text-white inputs-style" value="Stiahnúť">
                    </form>
                </div>
            </div>
            <div class="col-lg-1"></div>
            <?php
            $sql_teacher_test = "SELECT * FROM test WHERE id_teacher = ?";
            $stm_teacher_test = $conn->prepare($sql_teacher_test);
            $stm_teacher_test->execute([$teacher["id"]]);
            $teacher_tests = $stm_teacher_test->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="tests-div col-lg-8 text-center mb-3 p-3">
                <div class="row">
                    <div class="table-head col-md-5">
                        <span class="text-purple">
                            Názov testu
                        </span>
                    </div>
                    <div class="table-head col-md-5">
                        <span class="text-purple">
                            Kód testu
                        </span>
                    </div>
                    <div class="table-head col-md-2">
                        <span class="text-purple">
                            Status testu
                        </span>
                    </div>
                </div>

                <hr>
                <?php
                foreach ($teacher_tests as $teacher_test) {
                ?>
                    <form>
                        <div class="form-group row">
                            <input type="hidden" name="test_key" id="test_key" value=<?php echo $teacher_test["test_key"] ?>>
                            <div class="col-md-5">
                                <label for="title"> <?php echo $teacher_test["title"] ?></label>
                            </div>

                            <div class="col-md-5">
                                <label for="test_key"> <?php echo $teacher_test["test_key"] ?></label>
                            </div>

                            <div class="col-md-2">
                                <select name="status" onchange="this.form.submit()">
                                    <?php
                                    if ($teacher_test["status"] == "on") {
                                        echo '<option value="on" selected>Zapnutý</option>';
                                        echo '<option value="off">Vypnutý</option>';
                                    } else {
                                        echo '<option value="on">Zapnutý</option>';
                                        echo '<option value="off" selected>Vypnutý</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                    </form>
                <?php
                }
                ?>
            </div>
        </div>
        <script>
            if (!window.sessionStorage.getItem("ais_id") && !window.sessionStorage.getItem("pass")) {
                window.location.href = "index.php"
            }

            function logout() {
                window.sessionStorage.clear()
                fetch("endpoints/logout.php", {
                    method: 'GET'
                })
                window.location.href = "index.php"
            }
        </script>
        <?php
        if (isset($_GET["status"]) && isset($_GET["test_key"])) {
            $sql_update_test_status = "UPDATE test SET status=? WHERE test_key=?";
            $stm_update_test_status = $conn->prepare($sql_update_test_status);
            $stm_update_test_status->bindValue(1, $_GET["status"]);
            $stm_update_test_status->bindValue(2, $_GET["test_key"]);
            $stm_update_test_status->execute();
        ?>
            <script>
                window.location.href = "teacher.php"
            </script>
        <?php
        }
        ?>
</body>

</html>