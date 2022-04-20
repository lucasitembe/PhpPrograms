<?php
    include("./includes/connection.php");
    session_start();
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $Registration_ID=mysqli_real_escape_string($conn,$_POST['Registration_ID']);
	$admission_id=mysqli_real_escape_string($conn,$_POST['admission_id']);	
	$consultation_id=mysqli_real_escape_string($conn,$_POST['consultation_id']);	
	// $date_birth=mysqli_real_escape_string($conn,$_POST['date_birth']);    //
    // $weight = mysqli_real_escape_string($conn,$_POST['weight']);
    // $sex = mysqli_real_escape_string($conn,$_POST['sex']);
    // $apgar = mysqli_real_escape_string($conn,$_POST['apgar']);
    // $method_delivery = mysqli_real_escape_string($conn,$_POST['method_delivery']);
    $first_stage = mysqli_real_escape_string($conn,$_POST['first_stage']);
    $second_stage = mysqli_real_escape_string($conn,$_POST['second_stage']);
    $third_stage = mysqli_real_escape_string($conn,$_POST['third_stage']);
    $fourth_stage = mysqli_real_escape_string($conn,$_POST['fourth_stage']);
    $placenta_membrane = mysqli_real_escape_string($conn,$_POST['placenta_membrane']);
    $blood_loss = mysqli_real_escape_string($conn,$_POST['blood_loss']);
    $reason_pph = mysqli_real_escape_string($conn,$_POST['reason_pph']);
    $perineum = mysqli_real_escape_string($conn,$_POST['perineum']);
    $repair_by = mysqli_real_escape_string($conn,$_POST['repair_by']);
    $delivery_by = mysqli_real_escape_string($conn,$_POST['delivery_by']);
    $supervision_by = mysqli_real_escape_string($conn,$_POST['supervision_by']);
    // $insert_summary=mysqli_query($conn,"INSERT INTO summary_labour( Registration_ID, admission_id, consultation_id, Employee_ID, date_birth, weight, sex, apgar, method_delivery, first_stage, second_stage, third_stage, fourth_stage, placenta_membrane, blood_loss, reason_pph, perineum, repair_by, delivery_by, supervision_by) VALUES ('$Registration_ID','$admission_id','$consultation_id','$Employee_ID','$date_birth','$weight','$sex','$apgar','$method_delivery','$first_stage','$second_stage','$third_stage','$fourth_stage','$placenta_membrane','$blood_loss','$reason_pph','$perineum','$repair_by','$delivery_by','$supervision_by')") or die(mysqli_error());

    $insert_summary=mysqli_query($conn,"INSERT INTO summary_labour( Registration_ID, admission_id, consultation_id, Employee_ID,first_stage, second_stage, third_stage, fourth_stage, placenta_membrane, blood_loss, reason_pph, perineum, repair_by, delivery_by, supervision_by) VALUES ('$Registration_ID','$admission_id','$consultation_id','$Employee_ID','$first_stage','$second_stage','$third_stage','$fourth_stage','$placenta_membrane','$blood_loss','$reason_pph','$perineum','$repair_by','$delivery_by','$supervision_by')") or die(mysqli_error());
    if($insert_summary){
        echo "Data Successfully Saved";
    }else{
        echo "Data Not Saved";
    }
       
?>