<?php

include("../includes/connection.php");

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$sex = mysqli_real_escape_string($conn, trim($_POST['sex']));

$state_of_birth = mysqli_real_escape_string($conn, trim($_POST['state_of_birth']));

$apgar = mysqli_real_escape_string($conn, trim($_POST['apgar']));

$birth_weight = mysqli_real_escape_string($conn, trim($_POST['birth_weight']));

$length = mysqli_real_escape_string($conn, trim($_POST['length']));

$head_circumference = mysqli_real_escape_string($conn, trim($_POST['head_circumference']));

$abnormalities = mysqli_real_escape_string($conn, trim($_POST['abnormalities']));

$drugs = mysqli_real_escape_string($conn, trim($_POST['drugs']));

$paediatrician = mysqli_real_escape_string($conn, trim($_POST['paediatrician']));

$transferred_to = mysqli_real_escape_string($conn, trim($_POST['transferred_to']));

$reason = mysqli_real_escape_string($conn, trim($_POST['reason']));

$transferred_by = mysqli_real_escape_string($conn, trim($_POST['transferred_by']));

$name = mysqli_real_escape_string($conn, trim($_POST['name']));

$temperature = mysqli_real_escape_string($conn, trim($_POST['temperature']));

$select_baby_record = "SELECT sex, state_of_birth, apgar, birth_weight, length, head_circumference, 
                                abnormalities, drugs, paediatrician, transferred_to, reason, 
                                transferred_by, name, temperature
                            FROM tbl_baby_record 
                            WHERE registration_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_baby_record);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) 
{
    $update_baby_record = "UPDATE tbl_baby_record
                            SET sex = ?, state_of_birth = ?, apgar = ?, birth_weight = ?, length = ?, head_circumference = ?, 
                                abnormalities = ?, drugs = ?, paediatrician = ?, transferred_to = ?, reason = ?, 
                                transferred_by = ?, name = ?, temperature = ?
                            WHERE registration_id=? AND admission_id=?";

    $update_stmt = mysqli_prepare($conn, $update_baby_record);

    mysqli_stmt_bind_param(
        $update_stmt,
        "ssssssssssssssii",
        $sex,
        $state_of_birth,
        $apgar,
        $birth_weight,
        $length,
        $head_circumference,
        $abnormalities,
        $drugs,
        $paediatrician,
        $transferred_to,
        $reason,
        $transferred_by,
        $name,
        $temperature,
        $registration_id,
        $admission_id
    );

    if (mysqli_stmt_execute($update_stmt)) {

        echo "Updated successfully";
    } else {

        echo "Data updated failure";
    }

} else {

    $insert_baby_record = "INSERT INTO tbl_baby_record
                                (registration_id, admission_id, sex, state_of_birth, apgar, birth_weight, length, head_circumference, 
                                abnormalities, drugs, paediatrician, transferred_to, reason, 
                                transferred_by, name, temperature) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insert_stmt = mysqli_prepare($conn, $insert_baby_record);

    mysqli_stmt_bind_param(
        $insert_stmt,
        "iissssssssssssss",
        $registration_id,
        $admission_id,
        $sex,
        $state_of_birth,
        $apgar,
        $birth_weight,
        $length,
        $head_circumference,
        $abnormalities,
        $drugs,
        $paediatrician,
        $transferred_to,
        $reason,
        $transferred_by,
        $name,
        $temperature
    );

    if (mysqli_stmt_execute($insert_stmt)) {

        echo "Added successfully";
    } else {

        echo "FAILURE";
    }
}
