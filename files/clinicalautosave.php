<?php
    include("./includes/connection.php");
    include './includes/cleaninput.php';
    @session_start();
   
    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
    
    $_GET=  sanitize_input($_GET); 
    
    //die($employee_ID);
    $fieldName = mysqli_real_escape_string($conn,$_GET['fieldName']);
    $fieldValue = mysqli_real_escape_string($conn,$_GET['fieldValue']);
    $consultation_ID = mysqli_real_escape_string($conn,$_GET['consultation_ID']);
    $from_consulted = mysqli_real_escape_string($conn, $_GET['from_consulted']);
   
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $doctor_notice_display_max_time= mysqli_fetch_assoc(mysqli_query($conn,"SELECT consulted_patient_display_max_time FROM tbl_hospital_consult_type WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'"))['consulted_patient_display_max_time'];
    if($from_consulted =="yes"){
        $update_query = mysqli_query($conn, "UPDATE tbl_consultation SET $fieldName='$fieldValue' WHERE consultation_ID = '$consultation_ID' AND  employee_ID='$Employee_ID'") or die(mysqli_error($conn));    
        if($update_query){
            echo 2;
        }
    }else{
        $update_query = "UPDATE tbl_consultation SET $fieldName='$fieldValue' WHERE consultation_ID = '$consultation_ID' AND  employee_ID='$Employee_ID'";     
        if (mysqli_query($conn,$update_query)) {
                $Update_consultation_history= mysqli_query($conn, "UPDATE tbl_consultation_history SET $fieldName='$fieldValue' WHERE consultation_ID = '$consultation_ID'  AND employee_ID='$Employee_ID'") or die(mysqli_error($conn));
            echo 1;
            
        }else{
            die(mysqli_error($conn));
        }
    }

?>