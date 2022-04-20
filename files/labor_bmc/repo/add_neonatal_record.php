<?php

include("../includes/connection.php");

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$dob = mysqli_real_escape_string($conn, trim($_POST['dob']));

$sex = mysqli_real_escape_string($conn, trim($_POST['sex']));

$wt = mysqli_real_escape_string($conn, trim($_POST['wt']));

$apgar = mysqli_real_escape_string($conn, trim($_POST['apgar']));

$maturity = mysqli_real_escape_string($conn, trim($_POST['maturity']));

$membranes_ruptured = mysqli_real_escape_string($conn, trim($_POST['membranes_ruptured']));

$amniotic_fluids = mysqli_real_escape_string($conn, trim($_POST['amniotic_fluids']));

$antenatal_care = mysqli_real_escape_string($conn, trim($_POST['antenatal_care']));

$diseases_complications = mysqli_real_escape_string($conn, trim($_POST['diseases_complications']));

$delivery_type = mysqli_real_escape_string($conn, trim($_POST['delivery_type']));

$indication = mysqli_real_escape_string($conn, trim($_POST['indication']));

$fhr = mysqli_real_escape_string($conn, trim($_POST['fhr']));

$placenta = mysqli_real_escape_string($conn, trim($_POST['placenta']));

$placenta_weight = mysqli_real_escape_string($conn, trim($_POST['placenta_weight']));

$abnormalities = mysqli_real_escape_string($conn, trim($_POST['abnormalities']));

$resucitation = mysqli_real_escape_string($conn, trim($_POST['resucitation']));

$drugs_given = mysqli_real_escape_string($conn, trim($_POST['drugs_given']));

$eye_drops = mysqli_real_escape_string($conn, trim($_POST['eye_drops']));

$sent_to = mysqli_real_escape_string($conn, trim($_POST['sent_to']));

$delivered_by = mysqli_real_escape_string($conn, trim($_POST['delivered_by']));

$prem_unit_by = mysqli_real_escape_string($conn, trim($_POST['prem_unit_by']));

$recieved_by = mysqli_real_escape_string($conn, trim($_POST['recieved_by']));

$condition_on_arrival = mysqli_real_escape_string($conn, trim($_POST['condition_on_arrival']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$select_neonatal_record = "SELECT dob, sex, weight, apgar, maturity, membranes_ruptured, amniotic_fluids, 
                                    antenatal_care, diseases_complications, delivery_type, indication, 
                                    fhr, placenta, placenta_weight, abnormalities, resucitation, drugs_given, eye_drops, 
                                    sent_to, delivered_by, prem_unit_by, recieved_by, condition_on_arrival, time
                        FROM tbl_neonatal_record 
                        WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_neonatal_record);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    $update_neonatal_record = "UPDATE tbl_neonatal_record
                            SET dob = ?, sex = ?, weight = ?, apgar = ?, maturity = ?, membranes_ruptured = ?, 
                                    amniotic_fluids = ?, antenatal_care = ?, diseases_complications = ?, delivery_type = ?, indication = ?, 
                                    fhr = ?, placenta = ?, placenta_weight = ?, abnormalities = ?, resucitation = ?, drugs_given = ?, eye_drops = ?, 
                                    sent_to = ?, delivered_by = ?, prem_unit_by = ?, recieved_by = ?, condition_on_arrival = ?, time = ?
                            WHERE patient_id=? AND admission_id=?";

    $update_stmt = mysqli_prepare($conn, $update_neonatal_record);

    mysqli_stmt_bind_param(
        $update_stmt,
        "ssssssssssssssssssssssssii",
        $dob,
        $sex,
        $wt,
        $apgar,
        $maturity,
        $membranes_ruptured,
        $amniotic_fluids,
        $antenatal_care,
        $diseases_complications,
        $delivery_type,
        $indication,
        $fhr,
        $placenta,
        $placenta_weight,
        $abnormalities,
        $resucitation,
        $drugs_given,
        $eye_drops,
        $sent_to,
        $delivered_by,
        $prem_unit_by,
        $recieved_by,
        $condition_on_arrival,
        $time,
        $registration_id,
        $admission_id
    );

    if (mysqli_stmt_execute($update_stmt)) {

        echo "Updated successfully";
    } else {

        echo "Data updated failure";
    }
} else {
    $insert_neonatal_record = "INSERT INTO tbl_neonatal_record(patient_id, admission_id, dob, sex, weight, apgar, maturity, membranes_ruptured, amniotic_fluids, 
                                antenatal_care, diseases_complications, delivery_type, indication, 
                                fhr, placenta, placenta_weight, abnormalities, resucitation, drugs_given, eye_drops, 
                                sent_to, delivered_by, prem_unit_by, recieved_by, condition_on_arrival, time)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $insert_record_stmt = mysqli_prepare($conn, $insert_neonatal_record);

    mysqli_stmt_bind_param(
        $insert_record_stmt,
        "iissssssssssssssssssssssss",
        $registration_id,
        $admission_id,
        $dob,
        $sex,
        $wt,
        $apgar,
        $maturity,
        $membranes_ruptured,
        $amniotic_fluids,
        $antenatal_care,
        $diseases_complications,
        $delivery_type,
        $indication,
        $fhr,
        $placenta,
        $placenta_weight,
        $abnormalities,
        $resucitation,
        $drugs_given,
        $eye_drops,
        $sent_to,
        $delivered_by,
        $prem_unit_by,
        $recieved_by,
        $condition_on_arrival,
        $time
    );

    if (mysqli_stmt_execute($insert_record_stmt) or die(mysqli_error($conn))) {
        echo "Inserted successfully";
    } else {

        echo "Data insertion failure";
    }
}
