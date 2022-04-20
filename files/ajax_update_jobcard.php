<?php
    include("./includes/connection.php");
    $Jobcard_ID = mysqli_real_escape_string($conn, $_POST['Jobcard_ID']);
    $Comments = mysqli_real_escape_string($conn, $_POST['Comments']);
    $defects = mysqli_real_escape_string($conn, $_POST['defects']);
    $diagnosis_action = mysqli_real_escape_string($conn, $_POST['diagnosis_action']);
    $Employee_ID = mysqli_real_escape_string($conn, $_POST['Employee_ID']);

    
    $sql_save_result = mysqli_query($conn, "UPDATE  tbl_jobcards SET Comments = '$Comments', defects = '$defects', diagnosis_action = '$diagnosis_action', edited_by = '$Employee_ID', edited_at = NOW() WHERE jobcard_ID = '$Jobcard_ID'") or die(mysqli_error($conn));
    if ($sql_save_result) {
        echo "200";
    } 
    else {
        echo "201";
    }
    mysqli_close($conn);
    
?>

