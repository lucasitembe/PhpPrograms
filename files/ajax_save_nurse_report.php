<?php
include("./includes/connection.php");
@session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if(isset($_POST['nurse_ward_report'])){
   $nurse_ward_report= mysqli_real_escape_string($conn,$_POST['nurse_ward_report']); 
   $ward_id= mysqli_real_escape_string($conn,$_POST['ward_id']); 
   $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
   $sql_save_nurse_report_result=mysqli_query($conn,"INSERT INTO tbl_nurse_ward_report(nurse_ward_report,ward_id,saved_by,saved_date) VALUES('$nurse_ward_report','$ward_id','$Employee_ID',NOW())") or die(mysqli_error($conn));

   if($sql_save_nurse_report_result){
      echo "success"; 
   }else{
      echo "fail"; 
   }
}