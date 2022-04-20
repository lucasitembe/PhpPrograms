<?php
    include("./includes/connection.php");
    $Registration_ID = mysqli_real_escape_string($conn, $_POST['Registration_ID']);
    $hour_days = mysqli_real_escape_string($conn, $_POST['hour_days']);
    $Employee_ID = mysqli_real_escape_string($conn, $_POST['Employee_ID']);
    $Priority = mysqli_real_escape_string($conn, $_POST['Priority']);
    $consultation_ID = mysqli_real_escape_string($conn, $_POST['consultation_ID']);
    $to_be_given = mysqli_real_escape_string($conn, $_POST['to_be_given']);
    $time_to_be_given = mysqli_real_escape_string($conn, $_POST['time_to_be_given']);
    $Clinical_History = mysqli_real_escape_string($conn, str_replace("'", "&#39;", $_POST['Clinical_History']));
    $reason_for_transfusion = mysqli_real_escape_string($conn, $_POST['reason_for_transfusion']);
    $previous_transfusion = mysqli_real_escape_string($conn, $_POST['previous_transfusion']);
    $dr_group = mysqli_real_escape_string($conn, $_POST['dr_group']);
    $amount_blood = mysqli_real_escape_string($conn, $_POST['amount_blood']);
    $operation_on = mysqli_real_escape_string($conn, $_POST['operation_on']);
    $Consent_ID = mysqli_real_escape_string($conn, $_POST['Consent_ID']);


    
    $sql_save_result = mysqli_query($conn, "INSERT INTO tbl_blood_transfusion_requests (Employee_ID, Registration_ID, hour_days, consultation_ID, Priority, to_be_given, Clinical_History, reason_for_transfusion, previous_transfusion, dr_group, amount_blood, operation_on, Consent_ID, time_to_be_given, Saved_date) VALUES ('$Employee_ID', '$Registration_ID', '$hour_days', '$consultation_ID', '$Priority', '$to_be_given', '$Clinical_History', '$reason_for_transfusion', '$previous_transfusion', '$dr_group', '$amount_blood', '$operation_on', '$Consent_ID', '$time_to_be_given', NOW())") or die(mysqli_error($conn));
    if ($sql_save_result) {
        echo "Consent Form was Saved Successfully, You can now send samples to Laboratory";
    } 
    else {
        echo "Failed To Save Consent Information";
    }
    mysqli_close($conn);
    ?>

