<?php 
   include("./includes/connection.php");
   session_start();
   $Employee_ID= mysqli_real_escape_string($conn,$_POST['Employee_ID']);
   $saved_by=$_SESSION['userinfo']['Employee_ID'];
   $sql_check_if_exist_result=mysqli_query($conn,"SELECT * FROM `tbl_mtuha_book_11_report_employee` WHERE Employee_ID='$Employee_ID'") or die(mysqli_error($conn));
   if(mysqli_num_rows($sql_check_if_exist_result)<=0){
    $sql_insert_employee_result=mysqli_query($conn,"INSERT INTO `tbl_mtuha_book_11_report_employee`( `Employee_ID`, `saved_by`, `saved_date`) VALUES('$Employee_ID','$saved_by',NOW())") or die(mysqli_error($conn));
   }
?>