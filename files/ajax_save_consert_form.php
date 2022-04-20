<?php
    include("./includes/connection.php");
    $Procedure_named = mysqli_real_escape_string($conn, $_POST['Procedure_named']);
    $surgery_doctor = mysqli_real_escape_string($conn, $_POST['surgery_doctor']);
    $behalf = mysqli_real_escape_string($conn, $_POST['behalf']);
    $relation = mysqli_real_escape_string($conn, $_POST['relation']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $patient_witness = mysqli_real_escape_string($conn, $_POST['patient_witness']);
    $Registration_ID = mysqli_real_escape_string($conn, $_POST['Registration_ID']);
    $presense_of_students = mysqli_real_escape_string($conn, $_POST['presense_of_students']);
    $photography_on_surgery = mysqli_real_escape_string($conn, $_POST['photography_on_surgery']);
    $consent_amputation = mysqli_real_escape_string($conn, $_POST['consent_amputation']);
    $vehicle1 = mysqli_real_escape_string($conn, $_POST['vehicle']);
    $Amptutation_of = mysqli_real_escape_string($conn, $_POST['Amptutation_of']);
    $surgery_doctor2 = mysqli_real_escape_string($conn, $_POST['surgery_doctor2']);
    $Consultation_ID = mysqli_real_escape_string($conn, $_POST['Consultation_ID']);
    $Language = mysqli_real_escape_string($conn, $_POST['Language']);
    $Payment_Item_Cache_List_ID = mysqli_real_escape_string($conn, $_POST['Payment_Item_Cache_List_ID']);
    $maelekezo = mysqli_real_escape_string($conn, $_POST['maelekezo']);


    
    $sql_save_result = mysqli_query($conn, "INSERT INTO tbl_consert_forms_details (procedure_taken, doctor_ID, on_behalf_name, maelekezo, relation, designation, patient_witness, Registration_ID, presense_of_students, photography_on_surgery, consent_amputation, consent_by, Amptutation_of, Responsible_dr, consultation_ID, Payment_Item_Cache_List_ID, Language, date) VALUES ('$Procedure_named', '$surgery_doctor', '$behalf', '$maelekezo', '$relation', '$designation', '$patient_witness', '$Registration_ID', '$presense_of_students', '$photography_on_surgery', '$consent_amputation', '$vehicle1', '$Amptutation_of', '$surgery_doctor2', '$Consultation_ID', '$Payment_Item_Cache_List_ID', '$Language', NOW())") or die(mysqli_error($conn));
    if ($sql_save_result) {
        echo "Consent Form was Saved Successfully, You can now take Signatures";
    } 
    else {
        echo "Failed To Save Consent Information";
    }
    mysqli_close($conn);
    ?>

