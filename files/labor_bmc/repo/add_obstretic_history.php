<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$year_of_birth = mysqli_real_escape_string($conn, trim($_POST['year_of_birth']));

$matunity = mysqli_real_escape_string($conn, trim($_POST['matunity']));

$sex = mysqli_real_escape_string($conn, trim($_POST['sex']));

$mode_of_delivery = mysqli_real_escape_string($conn, trim($_POST['mode_of_delivery']));

$birth_weight = mysqli_real_escape_string($conn, trim($_POST['birth_weight']));

$place_of_birth = mysqli_real_escape_string($conn, trim($_POST['place_of_birth']));

$breastfed_duration = mysqli_real_escape_string($conn, trim($_POST['breastfed_duration']));

$puerperium = mysqli_real_escape_string($conn, trim($_POST['puerperium']));

$present_child_condition = mysqli_real_escape_string($conn, trim($_POST['present_child_condition']));

$insert_obstretic_history = "INSERT INTO tbl_obstretic_history( year_of_birth, matunity, sex, mode_of_delivery, birth_weight, place_of_birth, breastfed_duration, puerperium, child_condition,Admission_ID, Registration_ID, Employee_ID) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$insert_history_stmt = mysqli_prepare($conn, $insert_obstretic_history);

mysqli_stmt_bind_param($insert_history_stmt, "sssssssssiii", $year_of_birth, $matunity, $sex, $mode_of_delivery, $birth_weight, $place_of_birth, $breastfed_duration, $puerperium, $present_child_condition, $admission_id, $registration_id, $Employee_ID);

if (mysqli_stmt_execute($insert_history_stmt)) {
    echo "Added successfully";
} else {

    echo "FAILURE";
}
