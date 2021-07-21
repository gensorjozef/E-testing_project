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
    <div class="container mt-4 rounded">
        <div class="p-4 row">
            <form method="post" action="addTest.php">
                <input type="hidden" name="teacher_id" id="teacher_id" value=<?php echo $_GET["teacher_id"]; ?>>
                <div class="form-group m-2">
                    <label for="title">Názov testu</label>
                    <input class="form-control" type="text" name="title" id="title">
                </div>
                <div class="form-group m-2">
                    <label for="length">Dĺžka testu (minút)</label>
                    <input class="form-control" type="text" name="length" id="length">
                </div>
                <div class="form-group m-2">
                    <label for="available">Dostupnosť</label>
                    <select class="form-control" name="available" id="available">
                        <option value="on">Zapnutý</option>
                        <option value="off">Vypnutý</option>
                    </select>
                </div>
                <div class="m-2">
                    <input type="submit" class="btn text-light" value="Vytvoriť test">
                </div>
                <div class="m-2">
                    <a href="../teacher.php" class="btn text-light" >Naspäť</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>