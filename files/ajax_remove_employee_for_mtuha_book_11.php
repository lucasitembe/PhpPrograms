<?php 
   include("./includes/connection.php");
   session_start();
   $mtuha_book_11_report_employee_id= mysqli_real_escape_string($conn,$_POST['mtuha_book_11_report_employee_id']);
   mysqli_query($conn,"DELETE FROM `tbl_mtuha_book_11_report_employee` WHERE mtuha_book_11_report_employee_id='$mtuha_book_11_report_employee_id'") or die(mysqli_error($conn));