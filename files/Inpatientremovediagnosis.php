<?php
include("./includes/connection.php");
$Ward_Round_Disease_ID=isset($_GET['Ward_Round_Disease_ID'])?$_GET['Ward_Round_Disease_ID']:0;

//echo $Ward_Round_Disease_ID;
mysqli_query($conn,"DELETE FROM tbl_ward_round_disease WHERE Ward_Round_Disease_ID=$Ward_Round_Disease_ID") or die(mysqli_error($conn));