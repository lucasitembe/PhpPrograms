<?php 
include("middleware/burn_unit_function.php");
session_start();


    $Employee_ID  = $_SESSION['userinfo']['Employee_ID'];
if(isset($_POST['save_record'])){
    $Brief_history_findings = $_POST['Brief_history_findings'];
    $Histology_FNAC = $_POST['Histology_FNAC'];
    $TNM_classfication = $_POST['TNM_classfication'];
    $Question_tumorboard = $_POST['Question_tumorboard'];
    $Desicion_of_Tumorboard = $_POST['Desicion_of_Tumorboard'];
   $Registration_ID =$_POST['Registration_ID'];
    $data =array(array(
        "Brief_history_findings" => $Brief_history_findings, 
        "Employee_ID "=>$Employee_ID,
        "Histology_FNAC" => $Histology_FNAC,
        "TNM_classfication "=>$TNM_classfication,
        "Question_tumorboard" => $Question_tumorboard,
        "Desicion_of_Tumorboard "=>$Desicion_of_Tumorboard,
        "Registration_ID" => $Registration_ID
        
    ));
   // die(print_r($data));
    if(save_tumorboard_registration(json_encode($data))>0){

        echo "ok";
    }else{
        echo mysqli_error($conn);
    }
}

?>