<?php
#call all functions related to optical

$today = date("Y-m-d: h:m");
if (isset($_GET['date'])) {
    $todayDate = $_GET['date'];
} else {
    $todayDate = date("Y-m-d");
}


$responseDistanceReadingRight = getPatientRightDistanceReading($patientId, $todayDate);
$responseDistanceReadingLeft = getPatientLeftDistanceReading($patientId, $todayDate);
$spectacleDistanceRight = getPatientLeftSepctacleDistance($patientId, $todayDate);
$spectacleDistanceLeft = getPatientRightSepctacleDistance($patientId, $todayDate);

$ipDistanceRight = getIpRightValue($patientId, $todayDate);
$ipDistanceLeft = getIpDistanceLeftValue($patientId, $todayDate);
$vaRgihtEye = getVaRightEye($patientId, $todayDate);
$vaLeftEye = getVaLeftEyeValue($patientId, $todayDate);

$sepctacleStatus = checkIfSpectacleShouldBeOffered($patientId, $todayDate);
$noSpectacleReason = getNoSpectacleReason($patientId, $todayDate);
$Guarantor_Name = sponsorName($patientId)[0];
$Sponsor_ID = sponsorName($patientId)[1];


?>