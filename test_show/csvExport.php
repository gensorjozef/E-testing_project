<?php
require_once "../Database.php";
require_once("../config.php");
$conn = (new Database())->createConnection();

$stmt = $conn->prepare("SELECT ais_id,name,surname,points FROM student_test");
$stmt->execute();
$result=$stmt->fetchAll();



$mysqli = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
$test_key = $_POST["test_key"];
$query = $mysqli->query("SELECT ais_id,name,surname,points FROM student_test where test_key='$test_key'");

if($query->num_rows > 0){
    $delimiter = ";";
    $filename = "vysledky_" . $_POST["test_key"] . ".csv";

    //create a file pointer
    $f = fopen('php://memory', 'w');
    $BOM = "\xEF\xBB\xBF";
    fwrite($f, $BOM);

    //set column headers
    $fields = array('ais_id', 'name', 'surname', 'points');
    fputcsv($f, $fields, $delimiter);

    //output each row of the data, format line as csv and write to file pointer
    while($row = $query->fetch_assoc()){
        $lineData = array($row['ais_id'], $row['name'], $row['surname'], $row['points']);
        fputcsv($f, $lineData, $delimiter);
    }

    //move back to beginning of file
    fseek($f, 0);

    //set headers to download file rather than displayed
    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    //output all remaining data on a file pointer


    fpassthru($f);

}else{
    $delimiter = ";";
    $filename = "vysledky_" . $_POST["test_key"] . ".csv";

    //create a file pointer
    $f = fopen('php://memory', 'w');
    $BOM = "\xEF\xBB\xBF";
    fwrite($f, $BOM);

    //set column headers

    //output each row of the data, format line as csv and write to file pointer

        $lineData = array("test", $_POST["test_key"], "nema" ,"vysledky");
        fputcsv($f, $lineData, $delimiter);


    //move back to beginning of file
    fseek($f, 0);

    //set headers to download file rather than displayed
    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    //output all remaining data on a file pointer


    fpassthru($f);
}
exit;

?>
