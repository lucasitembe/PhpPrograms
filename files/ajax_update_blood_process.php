<?php
    include("./includes/connection.php");
    $Rh1 = mysqli_real_escape_string($conn, $_POST['Rh1']);
    $Rh2 = mysqli_real_escape_string($conn, $_POST['Rh2']);
    $Rh3 = mysqli_real_escape_string($conn, $_POST['Rh3']);
    $Rh4 = mysqli_real_escape_string($conn, $_POST['Rh4']);
    $donor1 = mysqli_real_escape_string($conn, $_POST['donor1']);
    $donor2 = mysqli_real_escape_string($conn, $_POST['donor2']);
    $donor3 = mysqli_real_escape_string($conn, $_POST['donor3']);
    $donor4 = mysqli_real_escape_string($conn, $_POST['donor4']);
    $group1 = mysqli_real_escape_string($conn, $_POST['group1']);
    $group2 = mysqli_real_escape_string($conn, $_POST['group2']);
    $group3 = mysqli_real_escape_string($conn, $_POST['group3']);
    $group4 = mysqli_real_escape_string($conn, $_POST['group4']);
    $Quality = mysqli_real_escape_string($conn, $_POST['Quality']);
    $Pt_Hb = mysqli_real_escape_string($conn, $_POST['Pt_Hb']);
    $pt_Group = mysqli_real_escape_string($conn, $_POST['pt_Group']);
    $pt_Rh = mysqli_real_escape_string($conn, $_POST['pt_Rh']);
    $Employee_ID = mysqli_real_escape_string($conn, $_POST['Employee_ID']);
    $Blood_Transfusion_ID = mysqli_real_escape_string($conn, $_POST['Blood_Transfusion_ID']);
    $Comments = mysqli_real_escape_string($conn, $_POST['Comments']);
    $Coombs = mysqli_real_escape_string($conn, $_POST['Coombs']);

    // die("SELECT blood_Processing_ID FROM tbl_blood_transfusion_processing WHERE Blood_Transfusion_ID = '$Blood_Transfusion_ID' AND Process_Status = 'pending'");
    $Check_request = mysqli_query($conn, "SELECT blood_Processing_ID FROM tbl_blood_transfusion_processing WHERE Blood_Transfusion_ID = '$Blood_Transfusion_ID' AND Process_Status = 'pending'") or die(mysqli_error($conn));

    if(mysqli_num_rows($Check_request) > 0){
        $sql_save_result = mysqli_query($conn, "UPDATE tbl_blood_transfusion_processing SET Submitted_By = '$Employee_ID', Rh1 = '$Rh1', Rh2 = '$Rh2', Rh3 = '$Rh3', Rh4 = '$Rh4', donor1 = '$donor1', donor2 = '$donor2', donor3 = '$donor3', donor4 = '$donor4', group1 = '$group1', group2 = '$group2', group3 = '$group3', group4 = '$group4', Quality = '$Quality', Pt_Hb = '$Pt_Hb', pt_Group = '$pt_Group', pt_Rh = '$pt_Rh', Comments = '$Comments', Coombs = '$Coombs' WHERE Blood_Transfusion_ID = '$Blood_Transfusion_ID'") or die(mysqli_error($conn));
    }else{
        $Insert = mysqli_query($conn, "INSERT INTO tbl_blood_transfusion_processing (Blood_Transfusion_ID, Submitted_By, Submitted_At, Process_Status) VALUES('$Blood_Transfusion_ID', '$Employee_ID', NOW(), 'pending')") or die(mysqli_error($conn));
        if($Insert){
            $sql_save_result = mysqli_query($conn, "UPDATE tbl_blood_transfusion_processing SET Submitted_By = '$Employee_ID', Rh1 = '$Rh1', Rh2 = '$Rh2', Rh3 = '$Rh3', Rh4 = '$Rh4', donor1 = '$donor1', donor2 = '$donor2', donor3 = '$donor3', donor4 = '$donor4', group1 = '$group1', group2 = '$group2', group3 = '$group3', group4 = '$group4', Quality = '$Quality', Pt_Hb = '$Pt_Hb', pt_Group = '$pt_Group', pt_Rh = '$pt_Rh', Comments = '$Comments', Coombs = '$Coombs' WHERE Blood_Transfusion_ID = '$Blood_Transfusion_ID'") or die(mysqli_error($conn));
        }
    }

    if ($sql_save_result) {
        echo 200;
    } 
    else {
        echo 201;
    }
    mysqli_close($conn);
    ?>

