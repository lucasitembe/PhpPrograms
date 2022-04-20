<?php
include("./includes/connection.php");
if(isset($_GET['assigned_approval_level_id'])){
   $assigned_approval_level_id=$_GET['assigned_approval_level_id'];
}else{
   $assigned_approval_level_id="";
}
$sql_delete_approval_employee_result=mysqli_query($conn,"DELETE FROM tbl_employee_assigned_approval_level WHERE assigned_approval_level_id='$assigned_approval_level_id'") or die(mysqli_error($conn));