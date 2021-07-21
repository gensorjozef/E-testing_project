<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E testing STU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/homeStyle.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <h1 class="text-center title">E - testing</h1>
        </div>
        <div id="first" class="col">
            <div class="row col-md-5 m-2">
                <button onclick=showStudent() class="btn yellow ">Študent</button><br>
            </div>
            <div class="row col-md-5 m-2">
                <button onclick="showUcitel() " class="btn red">Učiteľ</button><br>
            </div>
            <div class="row col-md-5 m-2">
                <button onclick="window.location.href='documentation.php'" class="btn purple ">Dokumentácia</button>
            </div>

            <div class="row  m-2" id="student" style="display: none">
                <form method="post" action="test_make/index.php">
                    <h2 class="form-group">Spustenie testu</h2>
                    <!-- <label for="code">Kód testu</label><br> -->
                    <div class="form-group col-md-5">
                        <input type="text" name="code" id="code" class="form-control" placeholder="Kód testu" required><br>
                    </div>
                    <!-- <label for="name">Meno</label><br> -->
                    <div class="form-group col-md-5">
                        <input type="text" name="name" id="name" class="form-control" placeholder="Meno" required><br>
                    </div>
                    <!-- <label for="surname">Priezvisko</label><br> -->
                    <div class="form-group col-md-5">
                        <input type="text" name="surname" id="surname" class="form-control" placeholder="Priezvisko" required><br>
                    </div>
                    <!-- <label for="ais_id">AIS ID</label><br> -->
                    <div class="form-group col-md-5">
                        <input type="text" name="ais_id" id="ais_id" class="form-control" placeholder="AIS ID" required><br>
                    </div>
                    <div class="m-2">
                        <input type="submit" class="btn purple" value="Spustiť test">
                    </div>
                </form>
            </div>

            <div class="row  m-2" id="ucitel" style="display: none">
                <form id="log" method="POST" action="teacher.php">
                    <h2 class="form-group">Prihlásenie</h2>
                    <div class="form-group col-md-5">
                    <input type="text" name="ais_idP" id="ais_idP" class="form-control" placeholder="AIS ID" required><br>
                    </div>
                    <div class="form-group col-md-5">
                    <input type="password" name="pass" id="passP" class="form-control" placeholder="Heslo" required><br>
                    </div>
                    <div class="m-2">
                        <button type="button" class="btn purple" onclick="login(0)">Prihlásiť</button><br>
                    </div>
                    <div class="m-2">
                        <button type="button" class="btn purple" onclick="rgstr()">Prejdi na registráciu</button>
                    </div>
                </form>
                <form id="rgst" style="display: none">
                    <h2 class="form-group">Registrácia</h2>
                    <div class="form-group col-md-5">
                        <input type="text" name="ais_idR" id="ais_idR" class="form-control" placeholder="AIS ID" required><br>
                    </div>
                    <div class="form-group col-md-5">
                        <input type="text" name="name" id="nameR" class="form-control" placeholder="Meno" required><br>
                    </div>
                    <div class="form-group col-md-5">
                        <input type="text" name="surname" id="surnameR" class="form-control" placeholder="Priezvisko" required><br>
                    </div>
                    <div class="form-group col-md-5">
                        <input type="password" name="pass" id="passR" class="form-control" placeholder="Heslo" required><br>
                    </div>
                    <div class="m-2">
                        <button type="button" class="btn purple" onclick="signin()">Registrovať</button><br>
                    </div>
                    <div class="m-2">
                        <button type="button" class="btn purple" onclick="lgn()">Prejdi na prihlásenie</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        if (window.sessionStorage.getItem("ais_id") && window.sessionStorage.getItem("pass")) {
            login(1)
        }

        function showStudent() {
            document.getElementById("student").style.display = "block"
            document.getElementById("ucitel").style.display = "none"
        }

        function showUcitel() {
            document.getElementById("ucitel").style.display = "block"
            document.getElementById("student").style.display = "none"
        }

        function rgstr() {
            document.getElementById("log").style.display = "none"
            document.getElementById("rgst").style.display = "block"
        }

        function lgn() {
            document.getElementById("rgst").style.display = "none"
            document.getElementById("log").style.display = "block"
        }

        function login(x) {
            var pass, ais_id;
            if (x === 1) {
                ais_id = window.sessionStorage.getItem("ais_id")
                pass = window.sessionStorage.getItem("pass")
            } else {
                ais_id = document.getElementById("ais_idP").value
                pass = document.getElementById("passP").value
            }
            fetch("endpoints/login.php", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ais_id: ais_id,
                        pass: pass
                    })
                }).then((response) => response.json())
                .then((data) => {
                    if (data["msg"] == "success") {
                        window.sessionStorage.setItem("ais_id", ais_id)
                        window.sessionStorage.setItem("pass", pass)
                        document.getElementById("log").submit();
                    } else {
                        alert("Zle AIS ID alebo heslo!")
                    }
                })
        }

        function signin() {
            var ais_id = document.getElementById("ais_idR").value
            var name = document.getElementById("nameR").value
            var surname = document.getElementById("surnameR").value
            var pass = document.getElementById("passR").value
            console.log(name)
            console.log(surname)
            console.log(pass)
            if (name && surname && pass && ais_id) {
                fetch("endpoints/signin.php", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            name: name,
                            surname: surname,
                            pass: pass,
                            ais_id: ais_id
                        })
                    }).then((response) => response.json())
                    .then((data) => {
                        alert(data["msg"])
                    })
            } else {
                alert("Vyplnte vsetky udaje!")
            }
        }
    </script>
</body>

</html>