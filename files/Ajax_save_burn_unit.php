<?php 
session_start();
include("./includes/connection.php");
include("middleware/burn_unit_function.php");
    
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if(isset($_POST['receiving_notes'])){
    $Burn_ID = mysqli_real_escape_string($conn, $_POST['Burn_ID']);
    $date_of_burn = mysqli_real_escape_string($conn, $_POST['date_of_burn']);
    $Classfication_of_burn = mysqli_real_escape_string($conn, $_POST['Classfication_of_burn']);
    $Condition_of_patient = mysqli_real_escape_string($conn, $_POST['Condition_of_patient']);
    $FBP = mysqli_real_escape_string($conn, $_POST['FBP']);
    $electrolyte = mysqli_real_escape_string($conn, $_POST['electrolyte']);
    $blood_grouping_x_matching = mysqli_real_escape_string($conn, $_POST['blood_grouping_x_matching']);
    $other_investigation_done = mysqli_real_escape_string($conn, $_POST['other_investigation_done']);
    $management_given = mysqli_real_escape_string($conn, $_POST['management_given']);
    $Registration_ID= mysqli_real_escape_string($conn, $_POST['Registration_ID']);
    $tbsa = mysqli_real_escape_string($conn, $_POST['tbsa']);
    $Admision_ID =$_POST['Admision_ID'];

    
        $sql_insert_note = mysqli_query($conn, "INSERT INTO tbl_burn_unit_receiving_notes (Burn_ID,Classfication_of_burn, date_of_burn,Condition_of_patient,FBP,electrolyte, blood_grouping_x_matching, other_investigation_done,Admision_ID,management_given,tbsa,Registration_ID,Employee_ID) values('$Burn_ID','$Classfication_of_burn','$date_of_burn','$Condition_of_patient', '$FBP','$electrolyte','$blood_grouping_x_matching', '$other_investigation_done','$Admision_ID','$management_given','$tbsa','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
    if(!$sql_insert_note){
        echo "Fail";

    }else{
        echo "Success";
    }
     
}
if(isset($_POST['receiving_notes_update'])){
    $Receiving_note_ID = $_POST['Receiving_note_ID'];
    $Burn_ID = mysqli_real_escape_string($conn, $_POST['Burn_ID']);
    $date_of_burn = mysqli_real_escape_string($conn, $_POST['date_of_burn']);
    $Classfication_of_burn = mysqli_real_escape_string($conn, $_POST['Classfication_of_burn']);
    $Condition_of_patient = mysqli_real_escape_string($conn, $_POST['Condition_of_patient']);
    $FBP = mysqli_real_escape_string($conn, $_POST['FBP']);
    $electrolyte = mysqli_real_escape_string($conn, $_POST['electrolyte']);
    $blood_grouping_x_matching = mysqli_real_escape_string($conn, $_POST['blood_grouping_x_matching']);
    $other_investigation_done = mysqli_real_escape_string($conn, $_POST['other_investigation_done']);
    $management_given = mysqli_real_escape_string($conn, $_POST['management_given']);
    $Registration_ID= mysqli_real_escape_string($conn, $_POST['Registration_ID']);
    $tbsa = mysqli_real_escape_string($conn, $_POST['tbsa']);

    $sql_updated_note = mysqli_query($conn, "UPDATE tbl_burn_unit_receiving_notes SET Classfication_of_burn='$Classfication_of_burn', date_of_burn='$date_of_burn',Condition_of_patient='$Condition_of_patient',FBP='$FBP',electrolyte='$electrolyte', blood_grouping_x_matching='$blood_grouping_x_matching', other_investigation_done='$other_investigation_done',management_given='$management_given',tbsa='$tbsa',Updated_by='$Employee_ID' WHERE Receiving_note_ID='$Receiving_note_ID'") or die(mysqli_error($conn));
    if(!$sql_updated_note){
        echo "Failed to update";

    }else{
        echo "Successful updated ";
    }
}
if(isset($_POST['request_btn'])){
    $Request_type = mysqli_real_escape_string($conn, $_POST['Request_type']);
    $Diagnosis = mysqli_real_escape_string($conn, $_POST['Diagnosis']);
    $Brief_case_summary = mysqli_real_escape_string($conn, $_POST['Brief_case_summary']);
    $Question = mysqli_real_escape_string($conn, $_POST['Question']);
    $Request_to = mysqli_real_escape_string($conn, $_POST['Request_to']);
    $Registration_ID = mysqli_real_escape_string($conn, $_POST['Registration_ID']);
    $Request_from = mysqli_real_escape_string($conn, $_POST['Request_from']);
    //$Request_type = mysqli_real_escape_string($conn, $_POST['Request_type']);



    $sql_insert_request = mysqli_query($conn, "INSERT INTO tbl_request_for_consultation(Request_type,Diagnosis,Brief_case_summary,Question,Request_to,Request_from,Registration_ID,Employee_ID) VALUES('$Request_type', '$Diagnosis', '$Brief_case_summary','$Question','$Request_to','$Request_from','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
    if(!$sql_insert_request){
        echo "Fail";
    }else{
        echo "Success";
    }
}
if(isset($_POST['request_btn_update'])){
    $Request_type = mysqli_real_escape_string($conn, $_POST['Request_type']);
    $Diagnosis = mysqli_real_escape_string($conn, $_POST['Diagnosis']);
    $Brief_case_summary = mysqli_real_escape_string($conn, $_POST['Brief_case_summary']);
    $Question = mysqli_real_escape_string($conn, $_POST['Question']);
    $Request_to = mysqli_real_escape_string($conn, $_POST['Request_to']);
    $Registration_ID = mysqli_real_escape_string($conn, $_POST['Registration_ID']);
   // $Request_from = mysqli_real_escape_string($conn, $_POST['Request_from']);
    $Request_Consultation_ID = mysqli_real_escape_string($conn, $_POST['Request_Consultation_ID']);
    $sql_insert_request = mysqli_query($conn, "UPDATE tbl_request_for_consultation SET Request_type='$Request_type',Diagnosis='$Diagnosis',Brief_case_summary='$Brief_case_summary',Question='$Question',Request_to='$Request_to',Updated_at=NOW() WHERE Request_Consultation_ID='$Request_Consultation_ID' ") or die(mysqli_error($conn));
    if(!$sql_insert_request){
        echo "Failed to Update";
    }else{
        echo "Updated Successful ";
    }

}
if(isset($_POST['replay_btn'])){
    $consultation_request_replay = mysqli_real_escape_string($conn, $_POST['consultation_request_replay']);
    $Request_Consultation_ID = mysqli_real_escape_string($conn, $_POST['Request_Consultation_ID']);
    $Registration_ID = mysqli_real_escape_string($conn, $_POST['Registration_ID']);

    $insert_replay = mysqli_query($conn, "INSERT INTO tbl_consultation_request_replay (consultation_request_replay, Request_Consultation_ID, Registration_ID, Employee_ID) VALUES('$consultation_request_replay', '$Request_Consultation_ID', '$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));

    if(!$insert_replay){
        echo "Fail";
    }else{
        echo "success";
    }

}

if(isset($_POST['btn_assessment'])){
    $type_burn = $_POST['type_burn'];
    $data =array(array(
        "significant_life_criss" => $_POST['significant_life_criss'],
        "current_health_status "=>$_POST['current_health_status'],
        "status" => $_POST['status'],
        "medication_information "=>$_POST['medication_information'],
        "social_history" => $_POST['social_history'],
        "relatives" => $_POST['relatives'],
        "nursing_history "=>$_POST['nursing_history'],
        "Registration_ID" => $_POST['Registration_ID'],
        "Employee_ID "=>$Employee_ID,
        "Admision_ID" =>$_POST['Admision_ID']
        
    ));
   // die(print_r($data));
    if(save_patient_nurse_assessment(json_encode($data))>0){

        echo "ok";
    }else{
        echo mysqli_error($conn);
    }
}

if(isset($_POST['info_btn'])){
   
    $data =array(array(
        "Assessment_data" => $_POST['Assessment_data'],
        "Registration_ID" => $_POST['Registration_ID'],
        "Assessment_ID" => $_POST['Assessment_ID'],
        "Employee_ID "=>$Employee_ID
    ));
   // die(print_r($data));
    if(save_burn_assessment_info(json_encode($data))>0){

        echo "ok";
    }else{
        echo mysqli_error($conn);
    }
}

if(isset($_POST['update_info_btn'])){
        $Assessment_data_update = $_POST['Assessment_data_update'];        
        $Info_assessment_ID = $_POST['Info_assessment_ID'];
       
    $sql_updated_assessment_info = mysqli_query($conn, "UPDATE tbl_assessment_information SET Assessment_data='$Assessment_data_update',  Updated_at=NOW() WHERE Info_assessment_ID='$Info_assessment_ID' ") or die(mysqli_error($conn));
    if(!$sql_updated_assessment_info){
        echo "You can't Update";
    }
}
?>