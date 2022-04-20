<?php
if (isset($_SESSION['userinfo'])) {
    //Do something.....
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$nav = '';

$employee = $_SESSION['userinfo']['Employee_Name'];

if (isset($_GET['consultation_id'])) {
    $consultation_id = $_GET['consultation_id'];
}

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
}

if (isset($_GET['admission_id'])) {

    if ($_GET['admission_id'] != 0 && $_GET['admission_id'] != '') {
        $admision_id = $_GET['admission_id'];
    } else {
        $admision_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Registration_ID='$patient_id' AND Admission_Status IN('Admitted','pending') order by Admision_ID desc limit 1"))['Admision_ID'];
    }
}

if (isset($_GET['discharged'])) {
    $nav = '&discharged=discharged';
}

if (isset($_GET['this_page_from'])) {
    $this_page_from = $_GET['this_page_from'];
}

