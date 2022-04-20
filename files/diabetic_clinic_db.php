<?php 

    include("./includes/header.php");
    include("./includes/connection.php");
    session_start();
    if(isset($_POST['Employee_ID'])){
    $Employee_ID= $_POST['Employee_ID'];
    }else{ 
    $Employee_ID="";   
    }

        $Patient_Payment_ID= $_POST['Patient_Payment_ID'];
        $Patient_Payment_Item_List_ID= $_POST['Patient_Payment_Item_List_ID'];
        $Registration_ID= $_POST['Registration_ID'];



     //query to post date for diabetes clinic
     if(isset($_POST['clinic'])){
        $diabetic_clinic_no = mysqli_real_escape_string($conn,  $_POST["diabetic_clinic_no"]);
        $clinic_type = mysqli_real_escape_string($conn,  $_POST["clinic_type"]);
        $year_of_diagnosis = mysqli_real_escape_string($conn,  $_POST["year_of_diagnosis"]);
        $occupation = mysqli_real_escape_string($conn,  $_POST["occupation"]);
        $physical_activity = mysqli_real_escape_string($conn,  $_POST["physical_activity"]);
        $kind_of_treatment = mysqli_real_escape_string($conn,  $_POST["kind_of_treatment"]);
        $self_injecting = mysqli_real_escape_string($conn,  $_POST["self_injecting"]);
        $body_weight = mysqli_real_escape_string($conn,  $_POST["body_weight"]);
        $height = mysqli_real_escape_string($conn,  $_POST["height"]);
        $closest_hospital = mysqli_real_escape_string($conn,  $_POST["closest_hospital"]);
        $bim = mysqli_real_escape_string($conn,  $_POST["bim"]);
        $rbg = mysqli_real_escape_string($conn,  $_POST["rbg"]);
        $bp = mysqli_real_escape_string($conn,  $_POST["bp"]);
        $dead = mysqli_real_escape_string($conn,  $_POST["dead"]);
        $other_diagnosis = mysqli_real_escape_string($conn,  $_POST["other_diagnosis"]);
        $since = mysqli_real_escape_string($conn,  $_POST["since"]);
        $treatment = mysqli_real_escape_string($conn,  $_POST["treatment"]);
        $special_needs = mysqli_real_escape_string($conn,  $_POST["special_needs"]);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

        $send_diabet_scinic = "INSERT INTO  diabetic_clinic (created_at, diabetic_clinic_no, clinic_type,year_of_diagnosis, occupation, closest_hospital,physical_activity, kind_of_treatment, self_injecting, body_weight, height,
        bim, rbg,bp, dead, other_diagnosis, since, treatment, special_needs, Registration_ID, Employee_ID  ) 
        VALUES (NOW(), '$diabetic_clinic_no', '$clinic_type', '$year_of_diagnosis', '$occupation', '$closest_hospital', '$physical_activity', '$kind_of_treatment', '$self_injecting', '$body_weight', '$height',
        '$bim', '$rbg', '$bp', '$dead','$other_diagnosis', '$since', '$treatment', '$special_needs', '$Registration_ID', '$Employee_ID')";

        $result = mysqli_query($conn, $send_diabet_scinic);
        if(!$result){
            die("Couldn't insert data ".mysqli_error($conn)); 
        }else{
            echo "<script>alert('Saved successful')
            document.location='diabetes_clinic.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID'
            </script>";

           
        }

    }

    //query to post Fundoscopy or vibration test.
    if(isset($_POST['vbTest'])){
        $created_at = mysqli_real_escape_string($conn,  $_POST["created_at"]);
        $fundoscopy_test = mysqli_real_escape_string($conn,  $_POST["fundoscopy_test"]);       
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

        $send_test = "INSERT INTO tbl_fundoscopy(created_at, fundoscopy_test, Registration_ID, Employee_ID ) VALUES(NOW(), '$fundoscopy_test', '$Registration_ID', '$Employee_ID')";

        $result = mysqli_query($conn, $send_test);

        if(!$result){
            die( "Couldn't insert data".mysqli_error($conn));
        }else{
            echo "<script>alert('Saved successful')
            document.location='diabetes_clinic.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID'
            </script>";
           
            
        }
    }

    //query for posting admissions 
    if(isset($_POST['admission'])){
        $created_at = mysqli_real_escape_string($conn,  $_POST["created_at"]);
        $admission_diagnosis = mysqli_real_escape_string($conn,  $_POST["admission_diagnosis"]);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $send_admisions_diagnosis = "INSERT INTO tbl_admission_clinic (created_at, admission_diagnosis, Registration_ID, Employee_ID ) VALUES(NOW(), '$admission_diagnosis', '$Registration_ID', '$Employee_ID')";

        $result = mysqli_query($conn, $send_admisions_diagnosis);

        if(!$result){
            die( "Couldn't insert data".mysqli_error($conn));
        }else{
            echo "<script>alert('Saved successful')
            document.location='diabetes_clinic.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID'
            </script>";
           
        }
    }

     //Query to post diabetes result 
     if(isset($_POST['diabetes_education'])){
        $created_at = mysqli_real_escape_string($conn,  $_POST["created_at"]);
        $general = mysqli_real_escape_string($conn,  $_POST["general"]);
        $diet = mysqli_real_escape_string($conn,  $_POST["diet"]);
        $injection_technique = mysqli_real_escape_string($conn,  $_POST["injection_technique"]);
        $urine_testing = mysqli_real_escape_string($conn,  $_POST["urine_testing"]);
        $hyper_hypoglycemic = mysqli_real_escape_string($conn,  $_POST["hyper_hypoglycemic"]);
        $foot_care = mysqli_real_escape_string($conn,  $_POST["foot_care"]);
        $late_complication = mysqli_real_escape_string($conn,  $_POST["late_complication"]);
        $drugs = mysqli_real_escape_string($conn,  $_POST["drugs"]);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

        $send_diabetes_education = "INSERT INTO diabetes_education (created_at, general,diet, injection_technique, urine_testing, hyper_hypoglycemic, foot_care, late_complication, drugs, Registration_ID, Employee_ID ) 
        VALUES (NOW(), '$general', '$diet', '$injection_technique','$urine_testing', '$hyper_hypoglycemic', '$foot_care', '$late_complication', '$drugs', '$Registration_ID', '$Employee_ID')";

        $result = mysqli_query($conn, $send_diabetes_education);
        if(!$result){
            die("Couldn't insert data ".mysqli_error($conn));
        }else{
            echo "<script>alert('Saved successful')
            document.location='diabetes_clinic.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID'
            </script>";
           
        }

    }


    if(isset($_POST['follow_up_visit'])){
        $created_at = mysqli_real_escape_string($conn,  $_POST["created_at"]);
        $bwt = mysqli_real_escape_string($conn,  $_POST["bwt"]); 
        $bp = mysqli_real_escape_string($conn,  $_POST["bp"]);
        $rbg = mysqli_real_escape_string($conn,  $_POST["rbg"]);
        $clinical_notes = mysqli_real_escape_string($conn,  $_POST["clinical_notes"]);
       
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $send_follow_up_visit = "INSERT INTO follow_up_visit (created_at, bwt, bp, rbg, clinical_notes, Registration_ID, Employee_ID) 
        VALUES (NOW(), '$bwt', '$bp', '$rbg','$clinical_notes', '$Registration_ID', '$Employee_ID')";

        $result = mysqli_query($conn, $send_follow_up_visit);
        if(!$result){
            die("Couldn't insert data ".mysqli_error($conn));
        }else{
            echo "<script>alert('Saved successful')
            document.location='diabetes_clinic.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID'
            </script>";
           
        }

    }
     

    if(isset($_POST['regular_check'])){
        $created_at = mysqli_real_escape_string($conn,  $_POST["created_at"]);
        $hb = mysqli_real_escape_string($conn,  $_POST["hb"]);
        $hba1c = mysqli_real_escape_string($conn,  $_POST["hba1c"]);
        $microalb = mysqli_real_escape_string($conn,  $_POST["microalb"]);
        $bun = mysqli_real_escape_string($conn,  $_POST["bun"]);
        $crea = mysqli_real_escape_string($conn,  $_POST["crea"]);
        $esr = mysqli_real_escape_string($conn,  $_POST["esr"]);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        
        $send_checkup = "INSERT INTO regural_check (created_at,hb,hba1c,microalb,bun,crea,esr, Registration_ID, Employee_ID) 
        VALUES (NOW(), '$hb', '$hba1c', '$microalb', '$bun', '$crea', '$esr', '$Registration_ID','$Employee_ID')";

        $result = mysqli_query($conn, $send_checkup) or die(mysqli_error($conn));

        if(!$result){
            die( "Couldn't insert data".mysqli_error($conn));
        }else{
            echo "<script>alert('Saved successful')
            document.location='diabetes_clinic.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID'
            </script>";
        }
    }