<!DOCTYPE html>
<html>
<head>
    <title>Amana Hospital</title>
</head>
<body>
<h1>Control Number: <?php  getBill(); ?></h1>
<?php
include_once 'bill/Bill.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Africa/Dar_es_Salaam");
function getBill(){
    $host = "localhost";
    $username = "root";
    $db = "gepg";
    $password = "ehms2gpitg2014";
    $conn = new mysqli($host, $username, $password,$db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql ="select * from bill where BillId =".$_GET['billId']."";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // $bills=[];
        while($row = $result->fetch_assoc()) {
            echo $row["BillControlNumber"];
        }
    } else {
        echo "0 results";
    }
    $conn->close();
}

?>
</body>
</html>