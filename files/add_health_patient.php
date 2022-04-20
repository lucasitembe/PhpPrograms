<?php

include("./includes/connection.php");

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$vaccine = mysqli_real_escape_string($conn, trim($_POST['vaccine']));

$reason_for_other = mysqli_real_escape_string($conn, trim($_POST['reason_for_other']));

$batch_no = mysqli_real_escape_string($conn, trim($_POST['batch_no']));

$dose_no = mysqli_real_escape_string($conn, trim($_POST['dose_no']));

$date_of_vaccine = mysqli_real_escape_string($conn, trim($_POST['date_of_vaccine']));

$date_of_return = mysqli_real_escape_string($conn, trim($_POST['date_of_return']));

$health_education = mysqli_real_escape_string($conn, trim($_POST['health_education']));

$data = array();

$insert_vaccine_info = "INSERT INTO tbl_health_info(registration_id, vaccine, reason_for_other, batch_no, dose_no, date_of_vaccine, date_of_return, health_education)
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_vaccine_info);

mysqli_stmt_bind_param($insert_stmt, "isssisss", $registration_id, $vaccine, $reason_for_other, $batch_no, $dose_no, $date_of_vaccine, $date_of_return, $health_education);

if (mysqli_stmt_execute($insert_stmt)) {

    $data['vaccine'] = $vaccine;

    $data['reason_for_other'] = $reason_for_other;

    $data['batch_no'] = $batch_no;

    $data['dose_no'] = $dose_no;

    $data['date_of_vaccine'] = $date_of_vaccine;

    $data['date_of_return'] = $date_of_return;

    $data['health_education'] = $health_education;

    $data['display'] = "Added successfully";

    echo json_encode($data);
} else {

    echo "Data insertion failure";
}
