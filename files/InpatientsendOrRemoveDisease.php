<?php
    include("./includes/connection.php");
    if(isset($_GET['Round_ID'])){
        $Round_ID = $_GET['Round_ID'];
    }else{
        $Round_ID = 0;
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
    
    if($action=='REMOVE'){
        $delete_qr = "DELETE FROM tbl_ward_round_disease WHERE Round_ID = $Round_ID AND disease_ID =$disease_ID AND diagnosis_type='$Consultation_Type'";
        if(mysqli_query($conn,$delete_qr)){
            echo "removed";
        }
    }else{
        $Round_Disease_Date_And_Time=date('Y-m-d H:i:s');
        $insert_qr = "INSERT INTO tbl_ward_round_disease(disease_ID, Round_ID, diagnosis_type,Round_Disease_Date_And_Time)
        VALUES ('$disease_ID', '$Round_ID', '$Consultation_Type', NOW())";
        if(mysqli_query($conn,$insert_qr)){
            echo "added";
        }else{
            die(mysqli_error($conn));
        }
    }
?>