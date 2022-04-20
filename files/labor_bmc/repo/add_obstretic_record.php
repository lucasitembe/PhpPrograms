<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$paeditrician = mysqli_real_escape_string($conn, trim($_POST['paeditrician']));

$anaesthetist = mysqli_real_escape_string($conn, trim($_POST['anaesthetist']));

$surgeon = mysqli_real_escape_string($conn, trim($_POST['surgeon']));

$physician = mysqli_real_escape_string($conn, trim($_POST['physician']));

$date_of_admission = mysqli_real_escape_string($conn, trim($_POST['date_of_admission']));

$date_of_anc = mysqli_real_escape_string($conn, trim($_POST['date_of_anc']));

$drug_allergies = mysqli_real_escape_string($conn, trim($_POST['drug_allergies']));

$date_of_discharge = mysqli_real_escape_string($conn, trim($_POST['date_of_discharge']));

$lmp_duration = mysqli_real_escape_string($conn, trim($_POST['lmp_duration']));

$edd_duration = mysqli_real_escape_string($conn, trim($_POST['edd_duration']));

$ga_duration = mysqli_real_escape_string($conn, trim($_POST['ga_duration']));

$para = mysqli_real_escape_string($conn, trim($_POST['para']));

$gravida = mysqli_real_escape_string($conn, trim($_POST['gravida']));

$blood_group = mysqli_real_escape_string($conn, trim($_POST['blood_group']));

$weight = mysqli_real_escape_string($conn, trim($_POST['weight']));

$height = mysqli_real_escape_string($conn, trim($_POST['height']));

$medical_surgical_history = mysqli_real_escape_string($conn, trim($_POST['medical_surgical_history']));

$family_history = mysqli_real_escape_string($conn, trim($_POST['family_history']));

$reason_for_admission = mysqli_real_escape_string($conn, trim($_POST['reason_for_admission']));

$select_obstretic_record = "SELECT paeditrician, anaesthetist, surgeon, physician, date_of_admission, date_of_anc, drug_allergies, 
                                    date_of_discharge, lmp_duration, edd_duration, ga_duration, para, gravida, blood_group, weight, 
                                    height, medical_surgical_history, family_history, reason_for_admission 
                            FROM tbl_obstretic_record 
                            WHERE Registration_ID=? AND Admission_ID=?";

$stmt = mysqli_prepare($conn, $select_obstretic_record);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) 
{
    $update_obstretic_record = "UPDATE tbl_obstretic_record
                                SET paeditrician = ?, anaesthetist = ?, surgeon = ?, physician = ?, 
                                    date_of_admission = ?, date_of_anc = ?, drug_allergies = ?, 
                                    date_of_discharge = ?, lmp_duration = ?, edd_duration = ?, 
                                    ga_duration = ?, para = ?, gravida = ?, blood_group = ?, weight = ?, 
                                    height = ?, medical_surgical_history = ?, family_history = ?, reason_for_admission = ?
                                WHERE Registration_ID=? AND Admission_ID=?";

    $update_stmt = mysqli_prepare($conn, $update_obstretic_record);

    mysqli_stmt_bind_param(
        $update_stmt,
        "sssssssssssssssssssii",
        $paeditrician,
        $anaesthetist,
        $surgeon,
        $physician,
        $date_of_admission,
        $date_of_anc,
        $drug_allergies,
        $date_of_discharge,
        $lmp_duration,
        $edd_duration,
        $ga_duration,
        $para,
        $gravida,
        $blood_group,
        $weight,
        $height,
        $medical_surgical_history,
        $family_history,
        $reason_for_admission,
        $registration_id,
        $admission_id
    );

    if (mysqli_stmt_execute($update_stmt)) {

        echo "Updated successfully";
    } else {

        echo "Data updated failure";
    }

} else {

    $insert_obstretic_record = "INSERT INTO tbl_obstretic_record
                                ( paeditrician, anaesthetist, surgeon, physician, date_of_admission, date_of_anc, drug_allergies, 
                                date_of_discharge, lmp_duration, edd_duration, ga_duration, para, gravida, blood_group, weight, 
                                height, medical_surgical_history, family_history, reason_for_admission, Admission_ID, 
                                Registration_ID, Employee_ID) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insert_record_stmt = mysqli_prepare($conn, $insert_obstretic_record);

    mysqli_stmt_bind_param(
        $insert_record_stmt,
        "sssssssssssssssssssiii",
        $paeditrician,
        $anaesthetist,
        $surgeon,
        $physician,
        $date_of_admission,
        $date_of_anc,
        $drug_allergies,
        $date_of_discharge,
        $lmp_duration,
        $edd_duration,
        $ga_duration,
        $para,
        $gravida,
        $blood_group,
        $weight,
        $height,
        $medical_surgical_history,
        $family_history,
        $reason_for_admission,
        $admission_id,
        $registration_id,
        $Employee_ID
    );

    if (mysqli_stmt_execute($insert_record_stmt)) {

        echo "Added successfully";
    } else {

        echo "FAILURE";
    }
}
