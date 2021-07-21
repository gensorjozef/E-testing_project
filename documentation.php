<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/teacherStyle.css">
    <title>Document</title>
    <style>
        .btn{
            background-color: #B5EADD !important;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="p-3 mt-5 bg-light rounded">
        <table class="table table-light text-center">
        <thead class="thead-dark">
            <tr>
                <th>Úloha</th>
                <th>Body</th>
                <th>Študent</th>
            </tr>
        </thead>
        <tbody>
            <tr class="table-success">
                <td>prihlasovanie sa do aplikácie (študent, učiteľ)</td>
                <td>6</td>
                <td>Tomáš Čupeľa</td>
            </tr>
            <tr class="table-info">
                <td>realizácia otázok s viacerými odpoveďami (zadávanie, zobrazovanie, vyhodnotenie)</td>
                <td>10</td>
                <td>Lukáš Daniš</td>
            </tr>
            <tr class="table-danger">
                <td>realizácia otázok s krátkymi odpoveďami (zadávanie, zobrazovanie, vyhodnotenie)</td>
                <td>10</td>
                <td>Jozef Genšor</td>
            </tr>
            <tr class="table-success">
                <td>realizácia párovacích otázok (zadávanie, zobrazovanie, vyhodnotenie)</td>
                <td>12</td>
                <td>Tomáš Čupeľa</td>
            </tr>
            <tr class="table-warning">
                <td>realizácia otázok s kreslením (zadávanie, zobrazovanie, vkladanie výsledku do test, vyhodnotenie)</td>
                <td>15</td>
                <td>Vladislav Sieklik</td>
            </tr>
            <tr class="table-primary">
                <td>realizácia otázok s matematickým výrazom (zadávanie, zobrazovanie, vkladanie výsledku do test, vyhodnotenie)</td>
                <td>15</td>
                <td>Adam Kuchcik</td>
            </tr>
            <tr class="table-danger">
                <td>ukončenie testu (tlačidlo, čas)</td>
                <td>8</td>
                <td>Jozef Genšor</td>
            </tr>
            <tr class="table-danger">
                <td>možnosť zadefinovania viacerých testov, ich aktivácia a deaktivácia</td>
                <td>8</td>
                <td>Jozef Genšor</td>
            </tr>
            <tr class="table-primary">
                <td>info pre učiteľa ozbiehaní testov (kto už ukončil akto opustil danú stránku)</td>
                <td>10</td>
                <td>Adam Kuchcik</td>
            </tr>
            <tr class="table-success">
                <td>export do pdf</td>
                <td>10</td>
                <td>Tomáš Čupeľa</td>
            </tr>
            <tr class="table-warning">
                <td>export do csv</td>
                <td>10</td>
                <td>Vladislav Sieklik</td>
            </tr>
            <tr class="table-info">
                <td>docker balíček</td>
                <td>16</td>
                <td>Lukáš Daniš</td>
            </tr>
        </tbody>
        </table>
    </div>
    <div class="p-3 mt-2 bg-light rounded">
        Zbiehanie testov (kto už ukončil a kto opustil danú stránku) je pri DOCKERi spomalené (aspoň u nás (skúšali sme iba na jednom počítači)).
    </div>
    <div class="row m-4">
        <a href="./" class="btn">Naspäť</a>
    </div>
</div>
</body>
</html>