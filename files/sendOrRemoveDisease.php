<?php
    include("./includes/connection.php");
    @session_start();
    if(isset($_GET['consultation_id'])){
        $consultation_id = $_GET['consultation_id'];
    }else{
        $consultation_id = 0;
    }
    
    if(isset($_GET['disease_ID'])){
        $disease_ID = $_GET['disease_ID'];
    }else{
        $disease_ID = 0;
    }
    
    if(isset($_GET['action'])){
        $action = $_GET['action'];
    }else{
        $action = 0;
    }
    if(isset($_GET['Consultation_Type'])){
        $Consultation_Type = $_GET['Consultation_Type'];
    }else{
        $Consultation_Type = '';
    }
    
    $optical_remark=$_GET['optical_remark'];
    $take_Disease_Consultation_ID=$_GET['take_Disease_Consultation_ID'];


    $remark_ward=$_GET['remark_ward'];
    $take_Ward_Round_Disease_ID=$_GET['Ward_Round_Disease_ID'];

    $employee_ID=$_SESSION['userinfo']['Employee_ID'];
//    $hospitalConsultType=$_SESSION['hospitalConsultaioninfo']['consultation_Type'];
//     
//    if($hospitalConsultType=='One patient to many doctor'){
//        
//    }
    
    if($action=='REMOVE'){
        $delete_qr = "DELETE FROM tbl_disease_consultation WHERE consultation_id = $consultation_id AND disease_ID =$disease_ID AND diagnosis_type='$Consultation_Type' AND employee_ID='$employee_ID'";
        if(mysqli_query($conn,$delete_qr)){
            echo "removed";
        }
    }else{
        $Disease_Consultation_Date_And_Time = "(SELECT NOW())";
        $insert_qr = "INSERT INTO tbl_disease_consultation(disease_ID, consultation_ID,employee_ID, diagnosis_type,Disease_Consultation_Date_And_Time)
        VALUES ('$disease_ID', '$consultation_id','$employee_ID', '$Consultation_Type', $Disease_Consultation_Date_And_Time)";
        if(mysqli_query($conn,$insert_qr)){
            echo "added";
        }
    }


    
    $update_optical="UPDATE tbl_disease_consultation SET  optical_remark='$optical_remark' WHERE  Disease_Consultation_ID='$take_Disease_Consultation_ID'";
            mysqli_query($conn,$update_optical) or die(mysqli_error($conn));

            $update_ward="UPDATE tbl_ward_round_disease SET  remark='$remark_ward' WHERE   	Ward_Round_Disease_ID='$take_Ward_Round_Disease_ID'";
            mysqli_query($conn,$update_ward) or die(mysqli_error($conn));
?>