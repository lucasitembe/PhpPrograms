<?php
session_start();
$_SESSION['doctors_selected_clinic']=$_GET['Clinic_ID'];
$_SESSION['finance_department_id']=$_GET['finance_department_id'];
header("location: admittedpatientlist.php");
//header("location: doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage");
