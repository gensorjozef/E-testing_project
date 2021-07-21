<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../Database.php";
$conn = (new Database())->createConnection();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link rel="stylesheet" href="../../styles/basics.css">
    <title>Add multichoice</title>
</head>

<body>
    <?php
    if (isset($_GET["test_code"])) {
        $key = $_GET["test_code"];
    }
    ?>
    <div class="container mt-4 rounded col-md-6">
        <div class="p-4 row">
            <div class="form-group m-2">
                <label for="member">Počet odpovedí na otázku:</label><br>
                <input type="text" id="member" name="member" class="form-control" value="">
                <a href="#" id="filldetails" onclick="addFields()">Uprav počet</a>
            </div>
            <form method="post" action="submitMulti.php">
                <input type="hidden" id="test_code" name="test_code" value='<?php echo $key ?>'>
                <div class="form-group m-2">
                    <label for="title">Názov otázky:</label><br>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div id="container"></div>
                <div class="form-group m-2">
                    <label for="points">Body za otázku:</label><br>
                    <input type="text" name="points" id="points" class="form-control" required>
                </div>
                <div class="form-group m-2">
                    <input type="submit" class="btn text-light" value="Pridať otázku">
                </div>
            </form>
            <div class="m-2">
                <a href="../addTest.php?test_code=<?php echo $key ?>" class="btn text-light" >Naspäť</a>
            </div>
        </div>
    </div>




    <script type='text/javascript'>
        function addFields() {
            var number = document.getElementById("member").value;
            var container = document.getElementById("container");
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            for (i = 0; i < number; i++) {
                var div = document.createElement("div");
                div.classList.add("form-group");
                div.classList.add("m-2");

                div.appendChild(document.createTextNode("Odpoveď " + (i + 1)));
                var input = document.createElement("input");
                input.type = "text";
                input.name = "ans" + (i + 1);
                input.id = "ans" + (i + 1);
                input.classList.add("form-control");
                input.setAttribute('required', 'required');
                div.appendChild(input);
                div.appendChild(document.createTextNode("Je správna? "));
                var checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.name = "ans" + (i + 1) + "ch";
                checkbox.id = "ans" + (i + 1) + "ch";
                checkbox.classList.add("form-check-input");
                div.appendChild(checkbox);
                container.appendChild(div);
            }
        }
    </script>
</body>

</html>