<?php
    include("./includes/connection.php");
    $Clinic_ID = mysqli_real_escape_string($conn, $_POST['Clinic_ID']);
    $appointment_change = mysqli_real_escape_string($conn, $_POST['appointment_change']);


    
    $sql_save_result = mysqli_query($conn, "UPDATE  tbl_clinic SET Appointment_mandate = '$appointment_change' WHERE Clinic_ID = '$Clinic_ID'") or die(mysqli_error($conn));
    if ($sql_save_result) {
        echo "Clinic Appointment Setup Updated Successfully";
    } 
    else {
        echo "Failed To Update the Changes";
    }
    mysqli_close($conn);
    ?>

