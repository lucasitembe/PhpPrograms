<?php
session_start();
$_SESSION['doctors_selected_ward']=$_GET['Ward_ID'];
$_SESSION['finance_department_id']=$_GET['finance_department_id'];
header("location: admittedpatientlist.php");