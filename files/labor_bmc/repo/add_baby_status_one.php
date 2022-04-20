<?php

include("../includes/connection.php");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

if(mysqli_real_escape_string($conn, trim($_POST['heart_rate'])) == '')
{
    $heart_rate = 'no';

}else{
    $heart_rate = mysqli_real_escape_string($conn, trim($_POST['heart_rate']));
}

if(mysqli_real_escape_string($conn, trim($_POST['respiration'])) == '')
{
    $respiration = 'no';

}else{
    $respiration = mysqli_real_escape_string($conn, trim($_POST['respiration']));
}

if(mysqli_real_escape_string($conn, trim($_POST['muscle_tone'])) == '')
{
    $muscle_tone = 'no';

}else{
    $muscle_tone = mysqli_real_escape_string($conn, trim($_POST['muscle_tone']));
}

if(mysqli_real_escape_string($conn, trim($_POST['reflexe'])) == '')
{
    $reflexe = 'no';

}else{
    $reflexe = mysqli_real_escape_string($conn, trim($_POST['reflexe']));
}

if(mysqli_real_escape_string($conn, trim($_POST['color'])) == '')
{
    $color = 'no';

}else{
    $color = mysqli_real_escape_string($conn, trim($_POST['color']));
}

$sum = mysqli_real_escape_string($conn, trim($_POST['sum']));

$data = array();

$insert_baby_status = "INSERT INTO tbl_baby_status_one(patient_id, admission_id, heart_rate, respiration, muscle_tone, reflexe, color, sum)
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_baby_status);

mysqli_stmt_bind_param($insert_stmt, "iisssssi", $patient_id, $admission_id, $heart_rate, $respiration, $muscle_tone, $reflexe, $color, $sum);

if (mysqli_stmt_execute($insert_stmt)) 
{

    $data['sum'] = $sum;

    $data['display'] = "Added successfully";

    echo json_encode($data);
} else {

    echo "Data insertion failure";
}