<?php
    include("./includes/connection.php");

    $Employee_ID = mysqli_real_escape_string($conn, $_POST['Employee_ID']);
    $Blood_Transfusion_ID = mysqli_real_escape_string($conn, $_POST['Blood_Transfusion_ID']);
    
    $sql_save_result = mysqli_query($conn, "UPDATE tbl_blood_transfusion_processing SET Employee_ID = '$Employee_ID', Process_Status = 'processed', Processed_Date_Time = NOW() WHERE Blood_Transfusion_ID='$Blood_Transfusion_ID'") or die(mysqli_error($conn));
    if ($sql_save_result){
        $Update = mysqli_query($conn, "UPDATE tbl_blood_transfusion_requests SET Process_Status = 'processed' WHERE Blood_Transfusion_ID='$Blood_Transfusion_ID'");
        echo "Blood Tranfusion Process has been Processed Successfully!";
    } 
    else {
        echo "Failed To Process the Request";
    }
    mysqli_close($conn);
    ?>

