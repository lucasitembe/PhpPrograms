<?php
include("../includes/connection.php"); 
if(isset($_POST['reason'])){
    $reason = $_POST['reason'];
}

if(isset($_POST['patient_id'])){
  $patietnId = $_POST['patient_id'];  
}

$today = Date("Y-m-d");


$sql = "UPDATE tbl_spectacle_status 
            SET no_reason = '$reason' 
            WHERE patient_id='$patietnId'
            AND created_at='$today'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if($result)
        echo "save";

?>