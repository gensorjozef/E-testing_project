<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="../../styles/basics.css">
    <title>init test</title>
</head>
<body>
<div class="container mt-4 rounded col-md-7 p-3 text-center">
    <form>
        <div class="form-group mb-2">
            <label for="count">Počet otázok: </label>
            <input id="count" type="number" name="count" min="2" max="6" value="0" style="width: 40px">
            <button id="vytvorit" class="btn text-light" type="button" onclick=initTest("<?php if(isset($_GET["test_code"])){echo $_GET["test_code"];}?>")>Vytvorit</button>
        </div>
                <div id="QstAns"></div>
    </form>
</div>
<script src="../questions_js/iniTest.js"></script>
</body>
</html>
