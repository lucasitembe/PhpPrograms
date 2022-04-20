<?php

include './includes/connection.php';
@session_start();

$transfer_type = '';
$getTransfers = '';

if (isset($_GET['getTransfers']) && !empty($_GET['getTransfers'])) {
    $getTransfers = $_GET['getTransfers'];
}

if (isset($_GET['transfer_type']) && !empty($_GET['transfer_type'])) {
    $transfer_type = $_GET['transfer_type'];
}

echo $getTransfers . ' ' . $transfer_type;

$data = '';
$doctor_filter = '';
$doctors = '';

if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
    $doctor_filter = " AND Employee_ID = '" . $_SESSION['userinfo']['Employee_ID'] . "'";
}

if ($transfer_type == 'Doctor_To_Doctor') {
    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
        $doctors = '<option selected="selected" value="Select a doctor">Select a doctor</option>';
    }

    $consult = mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor' $doctor_filter");
    while ($row = mysqli_fetch_array($consult)) {
        $Employee_IDS = $row['Employee_ID'];
        $Employee_Name = $row['Employee_Name'];
        $doctors.='<option  value="' . $Employee_IDS . '">' . $Employee_Name . '</option>';
    }

    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {

        $doctors_to = '<option selected="selected" value="Select a doctor">Select a doctor</option>';
        $consult_to = mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor'  AND Employee_ID != '" . $_SESSION['userinfo']['Employee_ID'] . "' ");
        while ($row = mysqli_fetch_array($consult_to)) {
            $Employee_IDS = $row['Employee_ID'];
            $Employee_Name = $row['Employee_Name'];
            $doctors_to.='<option  value="' . $Employee_IDS . '">' . $Employee_Name . '</option>';
        }
         $data = $doctors . 'tegnanisha' . $doctors_to;
    } else {
         $data = $doctors;
    }
} elseif ($transfer_type == 'Doctor_To_Clinic') {
    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
        $doctors = '<option selected="selected" value="Select a doctor">Select a doctor</option>';
    }

    $consult = mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor' $doctor_filter");
    while ($row = mysqli_fetch_array($consult)) {
        $Employee_IDS = $row['Employee_ID'];
        $Employee_Name = $row['Employee_Name'];
        $doctors.='<option  value="' . $Employee_IDS . '">' . $Employee_Name . '</option>';
    }

    $clinic = '<option selected="selected" value="Select clinic">Select clinic</option>';
    $consult_clinic = mysqli_query($conn,"Select * from tbl_clinic where Clinic_Status='Available' ");
    while ($row = mysqli_fetch_array($consult_clinic)) {
        $Clinic_ID = $row['Clinic_ID'];
        $Clinic_Name = $row['Clinic_Name'];

        $clinic.='<option  value="' . $Clinic_ID . '">' . $Clinic_Name . '</option>';
    }

    $data = $doctors . 'tegnanisha' . $clinic;
} elseif ($transfer_type == 'Clinic_To_Clinic') {
    $clinic = '<option selected="selected" value="Select clinic">Select clinic</option>';
    $consult_clinic = mysqli_query($conn,"Select * from tbl_clinic where Clinic_Status='Available' ");
    while ($row = mysqli_fetch_array($consult_clinic)) {
        $Clinic_ID = $row['Clinic_ID'];
        $Clinic_Name = $row['Clinic_Name'];

        $clinic.='<option  value="' . $Clinic_ID . '">' . $Clinic_Name . '</option>';
    }

    $data = $clinic;
} elseif ($transfer_type == 'Clinic_To_Doctor') {
    $doctors = '<option selected="selected" value="Select a doctor">Select a doctor</option>';
    $consult = mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor' ");
    while ($row = mysqli_fetch_array($consult)) {
        $Employee_IDS = $row['Employee_ID'];
        $Employee_Name = $row['Employee_Name'];
        $doctors.='<option  value="' . $Employee_IDS . '">' . $Employee_Name . '</option>';
    }

    $clinic = '<option selected="selected" value="Select clinic">Select clinic</option>';
    $consult_clinic = mysqli_query($conn,"Select * from tbl_clinic where Clinic_Status='Available' ");
    while ($row = mysqli_fetch_array($consult_clinic)) {
        $Clinic_ID = $row['Clinic_ID'];
        $Clinic_Name = $row['Clinic_Name'];

        $clinic.='<option  value="' . $Clinic_ID . '">' . $Clinic_Name . '</option>';
    }

    $data = $doctors . 'tegnanisha' . $clinic;
}

echo $data;
