<?php
    include("./includes/connection.php");
    $Registration_ID = mysqli_real_escape_string($conn, $_POST['Registration_ID']);
    $vehicle1 = mysqli_real_escape_string($conn, $_POST['vehicle']);
    $Employee_ID = mysqli_real_escape_string($conn, $_POST['Employee_ID']);
    $consent_amputation = mysqli_real_escape_string($conn, $_POST['consent_amputation']);
    $consultation_id = mysqli_real_escape_string($conn, $_POST['consultation_id']);
    $behalf = mysqli_real_escape_string($conn, $_POST['behalf']);


    
    $sql_save_result = mysqli_query($conn, "INSERT INTO tbl_consert_blood_forms_details (Employee_ID, Registration_ID, consent_by, consultation_id, Signed_at, consent_amputation, behalf) VALUES ('$Employee_ID', '$Registration_ID', '$vehicle1', '$consultation_id', NOW(), '$consent_amputation', '$behalf')") or die(mysqli_error($conn));
    if ($sql_save_result) {
        echo "Consent Form was Saved Successfully, You can now take Signatures";
    } 
    else {
        echo "Failed To Save Consent Information";
    }
    mysqli_close($conn);
    ?>

