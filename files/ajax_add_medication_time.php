<?php
include("./includes/connection.php");
session_start();
if(isset($_POST['medication_time_txt'])){
   $medication_time_txt=$_POST['medication_time_txt']; 
   $added_by=$_SESSION['userinfo']['Employee_ID'];
   mysqli_query($conn,"INSERT INTO tbl_medication_time (medication_time,added_by) VALUES('$medication_time_txt','$added_by')") or die(mysqli_error($conn));
}
//select all saved medication time
$sql_select_all_saved_medication_time_result=mysqli_query($conn,"SELECT medication_time FROM tbl_medication_time") or die(mysqli_error($conn));

if(mysqli_num_rows($sql_select_all_saved_medication_time_result)>0){
    echo "<option value=''>Select Medication TIme</option>";
    while($time_rows=mysqli_fetch_assoc($sql_select_all_saved_medication_time_result)){
        $medication_time=$time_rows['medication_time'];
        echo "<option value='$medication_time'>$medication_time</option>";
    }
}