<?php

@session_start();
include("includes/connection.php");

$chkout=mysqli_query($conn,"UPDATE tbl_attendance SET check_out=NOW() WHERE employee_id='".$_SESSION['userinfo']['Employee_ID']."' AND DATE(check_in)=CURDATE()") or die(mysqli_error($conn));

if($chkout){
   echo 1;
}
                
