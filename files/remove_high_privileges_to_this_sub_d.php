<?php
include("./includes/connection.php");
if(isset($_GET['Sub_Department_ID'])){
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
    $sql_update_sub_d_privileges_result=mysqli_query($conn,"UPDATE tbl_sub_department SET privileges='normal' WHERE Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
}
require "high_privileges_sub_department.php";
