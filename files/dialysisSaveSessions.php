<?php
    include("./includes/connection.php");
if(isset($_POST['updateSession'])){
    $employee_id=$_POST['employee_id'];
    $session_id=$_POST['session_id'];
    $start_time=$_POST['start_time'];
    $end_time=$_POST['end_time'];
    $update_sql = "UPDATE tbl_dialysis_session_time_setup SET start_time='$start_time',end_time='$end_time',updated_by='$employee_id' WHERE session_time_setup_id='$session_id'";
    $update_query=  mysqli_query($conn,$update_sql) or die(mysqli_error($conn));
    if($update_query){ 
        echo 'Session time range was updated successfully';
    }else{
        echo 'error: '.mysqli_error($conn);
    }
}
if(isset($_POST['new_session'])){
    $employee_id=$_POST['employee_id'];
    $session_name=$_POST['session_name'];
    // $session_id=$_POST['session_id'];
    $session_to=$_POST['session_to'];
    $session_from=$_POST['session_from'];
    $update_sql = "INSERT INTO `tbl_dialysis_session_time_setup`( `session_description`, `start_time`, `end_time`, `updated_by`) VALUES ('$session_name','$session_from','$session_to','$employee_id')";
    $update_query=  mysqli_query($conn,$update_sql) or die(mysqli_error($conn));
    if($update_query){ 
        echo 'Session Saved successfully';
    }else{
        echo 'error: '.mysqli_error($conn);
    }
}