<?php

$host = "localhost";

$username = "ehms_user";

$database = "ehms_lugalo2";

$password = "root";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed:oooooo============== " . mysqli_connect_error($conn));
}

$database_select = mysqli_select_db($conn, $database);

if (!$database_select) {
    die("Database selection failed: " . mysqli_error($conn));
}