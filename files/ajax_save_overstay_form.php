<?php
    include("./includes/connection.php");
    $Registration_ID = mysqli_real_escape_string($conn, $_POST['Registration_ID']);
    $Sponsor_ID = mysqli_real_escape_string($conn, $_POST['Sponsor_ID']);
    $Employee_ID = mysqli_real_escape_string($conn, $_POST['Employee_ID']);
    $consultation_ID = mysqli_real_escape_string($conn, $_POST['consultation_ID']);
    $Reason_For_Overstaying = mysqli_real_escape_string($conn, str_replace("'", "&#39;", $_POST['Reason_For_Overstaying']));
    $Admision_ID = mysqli_real_escape_string($conn, $_POST['Admision_ID']);
    $ward_room_id = mysqli_real_escape_string($conn, $_POST['ward_room_id']);
    $Check_In_ID = mysqli_real_escape_string($conn, $_POST['Check_In_ID']);



    
    $sql_save_result = mysqli_query($conn, "INSERT INTO tbl_inpatient_overstaying (Employee_ID, Registration_ID, Sponsor_ID, consultation_ID, Reason_For_Overstaying, Admision_ID, ward_room_id, Check_In_ID, Signed_Date_Time) VALUES ('$Employee_ID', '$Registration_ID', '$Sponsor_ID', '$consultation_ID', '$Reason_For_Overstaying', '$Admision_ID', '$ward_room_id', '$Check_In_ID', NOW())") or die(mysqli_error($conn));
    if ($sql_save_result) {
        echo "Overstay Notification Form was Saved Successfully, You can now Continue to your Ward Round Documentation";
    } 
    else {
        echo "Failed To Save Overstay Notification Form";
    }
    mysqli_close($conn);
    ?>

