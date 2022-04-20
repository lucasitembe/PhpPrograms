<?php

$host = "157.230.84.237";
$username = "integration";
$database = "gateway";
$password = "ehms2gpitg2014";

$con = mysqli_connect($host, $username, $password, $database);
if (!$con) {
    die("Database connection failed:oooooo " . mysqli_connect_error($con));
}

// 2. Select a database to use  acc_fresh

$database_select2 = mysqli_select_db($con, $database);
//$database_select = mysql_select_db("final_one",$connection);

if (!$database_select2) {
    die("Database selection failed: " . mysqli_error($con));
}

?>