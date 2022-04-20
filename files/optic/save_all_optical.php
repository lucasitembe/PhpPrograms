<?php
include("../includes/connection.php");

if(isset($_POST['patient_id'])){
    $patientID = $_POST['patient_id'];
}

$today = Date("Y-m-d");


$sql = "UPDATE tbl_spectacle_status 
        SET patient_status='saved'
        WHERE patient_id='$patientID'
        AND created_at='$today'";
$result = mysqli_query($conn,$sql) or die(mysql_error);
if($result){
    echo "successfull saved";
}
?>