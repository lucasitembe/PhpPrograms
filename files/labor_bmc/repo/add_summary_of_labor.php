<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$Registration_ID = mysqli_real_escape_string($conn, trim($_POST['Registration_ID']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$consultation_id = mysqli_real_escape_string($conn, trim($_POST['consultation_id']));

$sex = mysqli_real_escape_string($conn, trim($_POST['sex']));

$weight = mysqli_real_escape_string($conn, trim($_POST['weight']));

$abnormalities = mysqli_real_escape_string($conn, trim($_POST['abnormalities']));

$resuscitation = mysqli_real_escape_string($conn, trim($_POST['resuscitation']));

$drugs = mysqli_real_escape_string($conn, trim($_POST['drugs']));

$eye_drop = mysqli_real_escape_string($conn, trim($_POST['eye_drop']));

$select_summary_labor = "SELECT sex, weight, abnormalities, resuscitation, drugs, eye_drop
                            FROM tbl_summary_labor 
                            WHERE Registration_ID=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_summary_labor);

mysqli_stmt_bind_param($stmt, "ii", $Registration_ID, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    $update_summary_labor = "UPDATE tbl_summary_labor
                            SET sex = ?, weight = ?, abnormalities = ?, resuscitation = ?, drugs = ?, eye_drop = ?
                            WHERE Registration_ID=? AND admission_id=?";

    $update_stmt = mysqli_prepare($conn, $update_summary_labor);

    mysqli_stmt_bind_param($update_stmt, "ssssssii", $sex, $weight, $abnormalities, $resuscitation, $drugs, $eye_drop, $Registration_ID, $admission_id);

    if (mysqli_stmt_execute($update_stmt)) {

        $data['sex'] = $sex;

        $data['weight'] = $weight;

        $data['abnormalities'] = $abnormalities;

        $data['resuscitation'] = $resuscitation;

        $data['drugs'] = $drugs;

        $data['eye_drop'] = $eye_drop;

        $data['alert'] = "Data updated successfully";

        echo json_encode($data);
    } else {

        echo "Data update failure";
    }
} else {
    $insert_summary_of_labor = "INSERT INTO tbl_summary_labor (Registration_ID, admission_id, consultation_id, Employee_ID, sex, weight, abnormalities, resuscitation, drugs, eye_drop)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insert_stmt = mysqli_prepare($conn, $insert_summary_of_labor);

    mysqli_stmt_bind_param($insert_stmt, "iiiissssss", $Registration_ID, $admission_id, $consultation_id, $Employee_ID, $sex, $weight, $abnormalities, $resuscitation, $drugs, $eye_drop);

    if (mysqli_stmt_execute($insert_stmt)) {

        $data['sex'] = $sex;

        $data['weight'] = $weight;

        $data['abnormalities'] = $abnormalities;

        $data['resuscitation'] = $resuscitation;

        $data['drugs'] = $drugs;

        $data['eye_drop'] = $eye_drop;

        $data['alert'] = "Data added successfully";

        echo json_encode($data);

    } else {

        echo "Data insertion failure";
    }
}
