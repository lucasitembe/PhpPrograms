<?php
include("./includes/connection.php");
session_start();
$Registration_ID=$_GET['Registration_ID'];
$Check_In_ID=$_GET['Check_In_ID'];
header("location: pharmacyinpatientpage.php?Registration_ID=$Registration_ID&Check_In_ID=$Check_In_ID&PharmacyInpatientPage=PharmacyInpatientPageThisForm&from_billing_work");