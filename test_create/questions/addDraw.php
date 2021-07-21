<?php
if (isset($_GET["test_code"])) {
    $key = $_GET['test_code'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link rel="stylesheet" href="../../styles/basics.css">
    <title>AddDraw</title>
</head>

<body>
    <div class="container mt-4 rounded col-md-6">
        <div class="p-4 row">
            <form method="POST" action="submitDraw.php">
                <input type="hidden" id="test_code" name="test_code" value='<?php echo $key ?>'>
                <div class="form-group m-2">
                    <label for="title">Názov otázky:</label>
                    <input class="form-control" type="text" id="title" name="title">
                </div>
                <div class="form-group m-2">
                    <label for="points">Body za otázku:</label>
                    <input class="form-control" type="number" id="points" name="points">
                </div>
                <div class="m-2">
                    <button type="submit" class="btn text-light">Pridať otázky</button>
                </div>
            </form>
            <div class="m-2">
                <a href="../addTest.php?test_code=<?php echo $key ?>" class="btn text-light" >Naspäť</a>
            </div>
        </div>
    </div>
</body>


</html>