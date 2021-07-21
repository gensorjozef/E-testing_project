<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../../styles/basics.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var counter = 2;
            document.getElementById("questions_count").value = counter - 1;
            $("#addButton").click(function() {

                if (counter > 10) {
                    alert("Maximalny pocet otazok je 10");
                    return false;
                }

                var newQuestionDiv = $(document.createElement('div'))
                    .attr("id", 'question' + counter)
                    .attr("name", 'question' + counter)
                    .attr("class", 'form-group m-2');

                newQuestionDiv.after().html('<label>Otázka #' + counter + ' : </label>' +
                    '<input type="text" class="form-control" name="question' + counter +
                    '" id="question' + counter + '">');

                var newAnswerDiv = $(document.createElement('div'))
                    .attr("id", 'answer' + counter)
                    .attr("name", 'answer' + counter)
                    .attr("class", 'form-group m-2');

                newAnswerDiv.after().html('<label>Odpoveď #' + counter + ' : </label>' +
                    '<input type="text" class="form-control" name="answer' + counter +
                    '" id="answer' + counter + '">');

                var newPointsDiv = $(document.createElement('div'))
                    .attr("id", 'points' + counter)
                    .attr("name", 'points' + counter)
                    .attr("class", 'form-group m-2');

                newPointsDiv.after().html('<label>Body #' + counter + ' : </label>' +
                    '<input type="number" class="form-control" name="points' + counter +
                    '" id="points' + counter + '">');

                newQuestionDiv.appendTo("#questions");
                newAnswerDiv.appendTo("#questions");
                newPointsDiv.appendTo("#questions");


                counter++;
                document.getElementById("questions_count").value = counter - 1;
            });

            $("#removeButton").click(function() {
                if (counter == 2) {
                    alert("Minimalny pocet otazok je 1");
                    return false;
                }

                counter--;
                document.getElementById("questions_count").value = counter - 1;
                $("#question" + counter).remove();
                $("#answer" + counter).remove();
                $("#points" + counter).remove();

            });

        });
    </script>
    <style>
        .btn {
            white-space:normal !important;
            word-wrap: break-word; 
        }
    </style>
</head>

<body>
    <?php
    if (isset($_GET["test_code"])) {
        $key = $_GET["test_code"];
    }
    ?>
    <div class="container mt-4 rounded col-md-6">
        <div class="p-4 row justify-content-center">
            <form action="submitShort.php" method="post" class="col">
                <input type="hidden" id="test_code" name="test_code" value='<?php echo $key ?>'>

                <input type="hidden" id="questions_count" name="questions_count" value="">
                <div id='questions'>
                    <div id="question1" class="form-group m-2">
                        <label >Otázka #1 : </label>
                        <input type='text' id='question1' name="question1" class="form-control" required>
                    </div>
                    <div id="answer1" class="form-group m-2">
                        <label >Odpoveď #1 : </label>
                        <input type='text' id='answer1' name="answer1" class="form-control" required>
                    </div>
                    <div id="points1" class="form-group m-2">
                        <label >Body #1 : </label>
                        <input type='number' id='points1' name="points1" class="form-control" required>
                    </div>
                </div>
                <div class="m-2">
                <input type='button' class="btn text-light col-lg-4" value='Ďalšia otázka' id='addButton'>
                </div>
                <div class="m-2">
                <button type="button" id='removeButton' class="btn text-light col-lg-4">Odstráň poslednú otázku</button>
                <!-- <input type='button' class="btn text-light col-lg-4" value='Odstráň poslednú otázku' id='removeButton'> -->
                </div>
                    <div class="m-2">
                        <button type="submit" class="btn text-light col-lg-4">Pridať otázku</button>
                    </div>
                <div class="m-2">
                    <a href="../addTest.php?test_code=<?php echo $key ?>" class="btn text-light" >Naspäť</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>