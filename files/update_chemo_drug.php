<?php
include("includes/connection.php"); 
$patient_id = $_GET['reg'];
echo $selected_drug = $_GET['selected_frequency'];


$sql = "UPDATE tbl_patient_chemotherapy_drug SET Frequency='$selected_drug' WHERE Registration_ID='$patient_id'";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
if($result){
    echo $selected_drug;
}

?>