<?php
    
	include("./includes/connection.php");
    session_start();
    $nurse_id = $_POST['nurse_id'];
    $ward_id = $_POST['ward_id'];
    $Registration_ID = $_POST['Registration_ID'];
    // $doctor_id = $_POST['doctor_id'];
    $doctor_id = $_SESSION['userinfo']['Employee_ID'];
    $sponsor_id = $_POST['sponsor_id'];
    $dept_id = $_POST['dept_id'];
    
    $select_oncall_patient = mysqli_query($conn, "SELECT Registration_ID FROM tbl_oncall_claims WHERE Registration_ID='$Registration_ID' AND doctor_id='$doctor_id' AND DATE(date_time) =CURDATE()") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_oncall_patient)>0){
        echo "Sorry You Already claimed for this Patient today Continue.. Save Notes";
    }else{
        $insert_claim = mysqli_query($conn,"INSERT INTO tbl_oncall_claims(Registration_ID, doctor_id, nurse_id, ward_id, sponsor_id, dept_id) VALUES ('$Registration_ID', '$doctor_id', '$nurse_id', '$ward_id', '$sponsor_id', '$dept_id')") or die(mysqli_error($conn));

        if(!$insert_claim){
            echo 'Something went wrong, on call claim was not saved, Please try again.';
            
        }else{
            echo 'On call claim saved successfully';
        }
    }
   