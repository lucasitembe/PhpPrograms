<?php
session_start();
$_SESSION['doctors_selected_clinic']=$_GET['Clinic_ID'];
$_SESSION['finance_department_id']=$_GET['finance_department_id'];
$_SESSION['clinic_location_id']=$_GET['clinic_location_id'];
header("location: clinicpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage");
//header("location: doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage");
