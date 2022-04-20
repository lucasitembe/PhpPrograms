<?php
    session_start();
    include("./includes/header.php");
    include("./includes/connection.php");
        if (!isset($_SESSION['userinfo'])) {
            @session_destroy();
            header("Location: ../index.php?InvalidPrivilege=yes");
        }
        if (isset($_POST['consultation_ID'])) {
            $consultation_ID = $_POST['consultation_ID'];
        } else {
            $consultation_ID = 0;
        }
        if(isset($_POST['Employee_ID'])){ 
            $Employee_ID= $_POST['Employee_ID'];
            }else{
            $Employee_ID="";   
            }
        
        


            if(isset($_POST['Requisition_ID'])){
                $Requisition_ID= $_POST['Requisition_ID'];
            }else{
            $Requisition_ID="";    
            }
            if(isset($_POST['Payment_Cache_ID'])){
                $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
            }else{
                $Payment_Cache_ID= "";
            }
        if(isset($_POST['consent_signed_pre'])){
            $Requisition_ID=mysqli_real_escape_string($conn, $_POST['Requisition_ID']);
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            //select anesthesia record id
            $anasthesia_record = "SELECT list_ID FROM tbl_mrv_items WHERE Requisition_ID = '$Requisition_ID'";
                $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                // if(mysqli_num_rows($anasthesia_record_result)>0){
                //     $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['list_ID'];
                // }else{
                //     $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                //     $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID','$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                //     $anasthesia_record_id=mysqli_insert_id($conn);
                    
                // }
   
    }
        //select anesthesia record id
        
    //insert premedication
    
        

     //insert maintanance vitals
     
    //=====================Pre and Post Vitals CRUD==========================

    
    //===========Post vitals ====================
     

        
    //================Vent data=============
    if(isset($_POST['vent'])){
        $Mode = mysqli_real_escape_string($conn, $_POST['Mode']);
        $V_1_I_E = mysqli_real_escape_string($conn, $_POST['V_1_I_E']);
        $F1o2_RR = mysqli_real_escape_string($conn, $_POST['F1o2_RR']);
        $Air_02 = mysqli_real_escape_string($conn, $_POST['Air_02']);
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);
            
    }
    $renalto_medication = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_vent ( Mode, V_1_I_E, F1o2_RR,Air_02,  anasthesia_record_id, Registration_ID, Employee_ID) 
    VALUES ( '$Mode', '$V_1_I_E', '$F1o2_RR', '$Air_02','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
    if(!$renalto_medication){
        die("Couldn't insert Vent data  ".mysqli_error($conn));
    } else{
        header("location: anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID");
    }

    }
    //=====================End Vent =============
    //investigation post
    if(isset($_POST['Investigation'])){

        $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
        $biochemistry_ca = mysqli_real_escape_string($conn, $_POST['biochemistry_ca']);
        $biochemistry_cr = mysqli_real_escape_string($conn, $_POST['biochemistry_cr']);
        $biochemistry_urea = mysqli_real_escape_string($conn, $_POST['biochemistry_urea']);
        $biochemistry_gl = mysqli_real_escape_string($conn, $_POST['biochemistry_gl']);
        $biochemistry_k = mysqli_real_escape_string($conn, $_POST['biochemistry_k']);
        $biochemistry_na = mysqli_real_escape_string($conn, $_POST['biochemistry_na']);
        $biochemistry_cl = mysqli_real_escape_string($conn, $_POST['biochemistry_cl']);
        $fbp_hb = mysqli_real_escape_string($conn, $_POST['fbp_hb']);
        $fbp_hct = mysqli_real_escape_string($conn, $_POST['fbp_hct']);
        $fbp_platelets = mysqli_real_escape_string($conn, $_POST['fbp_platelets']);
        $fbp_wbc = mysqli_real_escape_string($conn, $_POST['fbp_wbc']);
        $inr_pt = mysqli_real_escape_string($conn, $_POST['inr_pt']);
        $inr_ptt = mysqli_real_escape_string($conn, $_POST['inr_ptt']);
        $inr_fibrinogen = mysqli_real_escape_string($conn, $_POST['inr_fibrinogen']);
        $inr_bleeding_time = mysqli_real_escape_string($conn, $_POST['inr_bleeding_time']);
        $lft = mysqli_real_escape_string($conn, $_POST['lft']);
        $other_hormones = mysqli_real_escape_string($conn, $_POST['other_hormones']);
        $blood_gas_sao2 = mysqli_real_escape_string($conn, $_POST['blood_gas_sao2']);
        $blood_gas_be = mysqli_real_escape_string($conn, $_POST['blood_gas_be']);
        $blood_gas_bic = mysqli_real_escape_string($conn, $_POST['blood_gas_bic']);
        $blood_gas_pco2 = mysqli_real_escape_string($conn, $_POST['blood_gas_pco2']);
        $blood_gas_ph = mysqli_real_escape_string($conn, $_POST['blood_gas_ph']);
        $cxr_findings = mysqli_real_escape_string($conn, $_POST['cxr_findings']);
        $ecg_findings = mysqli_real_escape_string($conn, $_POST['ecg_findings']);
        $echo_findings = mysqli_real_escape_string($conn, $_POST['echo_findings']);
        $ct_scan_findings = mysqli_real_escape_string($conn, $_POST['ct_scan_findings']);
        
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 
         //select anesthesia record id
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
            $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
            if(mysqli_num_rows($anasthesia_record_result)>0){
                $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
            }else{
                $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                $anasthesia_record_id=mysqli_insert_id($conn);
                
            }
        $investigation = mysqli_query($conn, "INSERT INTO tbl_anasthesia_investigation(blood_group, biochemistry_ca, biochemistry_cr, biochemistry_urea, biochemistry_gl, biochemistry_k, biochemistry_na, biochemistry_cl, fbp_hb, fbp_hct, fbp_platelets, fbp_wbc, inr_pt, inr_ptt, inr_fibrinogen, inr_bleeding_time, lft, other_hormones, blood_gas_sao2, blood_gas_be, blood_gas_bic, blood_gas_pco2, blood_gas_ph, cxr_findings, ecg_findings, echo_findings, ct_scan_findings,  anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES ('$blood_group','$biochemistry_ca','$biochemistry_cr','$biochemistry_urea','$biochemistry_gl', '$biochemistry_k', '$biochemistry_na', '$biochemistry_cl', '$fbp_hb', '$fbp_hct', '$fbp_platelets', '$fbp_wbc','$inr_pt','$inr_ptt','$inr_fibrinogen','$inr_bleeding_time','$lft','$other_hormones','$blood_gas_sao2','$blood_gas_be', '$blood_gas_bic', '$blood_gas_pco2','$blood_gas_ph', '$cxr_findings','$ecg_findings','$echo_findings', '$ct_scan_findings', '$anasthesia_record_id','$Registration_ID','$Employee_ID')");

        if(!$investigation){
            die("Couldn't insert data ".mysqli_error($conn));
        }else{
            echo "
                <script>
                    alert('Saved Successfully');
                    document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
                </script>
            ";
        }
    }

    //asa classification saving checkbox
    if(isset($_POST['asa_classification'])){
        $selected_val="";
       foreach($_POST['asa_classfication'] as $selected){
        $selected_val .=$selected.",";
        }
            $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

            //select anesthesia record id
                $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
                $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                if(mysqli_num_rows($anasthesia_record_result)>0){
                    $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
                }else{
                    $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                    $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                    $anasthesia_record_id=mysqli_insert_id($conn);
                    
            }
            $gastrointestinal = mysqli_query($conn, "INSERT INTO tbl_anasthesia_asa_classfication (created_at, asa_classfication, 
             anasthesia_record_id, Registration_ID, Employee_ID) 
            VALUES (NOW(),  '$selected_val', '$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
            if(!$gastrointestinal){
                die("Couldn't insert data ".mysqli_error($conn));
            }else{
                echo "
                    <script>
                        alert('Saved Successfully');
                        document.location='anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID'
                    </script>
                ";
            }
        
    }

    // ================End of anaesthesiaquery===================
    if(isset($_POST['end_of_anasthesia'])){
        $technic = mysqli_real_escape_string($conn,  $_POST["technic"]);
        $duration_of_anaesthesia = mysqli_real_escape_string($conn,  $_POST["duration_of_anaesthesia"]);
        $duration_of_operation = mysqli_real_escape_string($conn,  $_POST["duration_of_operation"]);
        $blood_loss = mysqli_real_escape_string($conn,  $_POST["blood_loss"]);
        $total_input = mysqli_real_escape_string($conn,  $_POST["total_input"]);
        $total_output = mysqli_real_escape_string($conn,  $_POST["total_output"]);      
        $fluid_balance = mysqli_real_escape_string($conn,  $_POST["fluid_balance"]);
        $Anaesthesia_notes = mysqli_real_escape_string($conn,  $_POST["Anaesthesia_notes"]);
        $Comments = mysqli_real_escape_string($conn,  $_POST["Comments"]);
        $starting_time = mysqli_real_escape_string($conn,  $_POST["starting_time"]);      
        $Pre_ECG = mysqli_real_escape_string($conn,  $_POST["Pre_ECG"]);
       $finishing_time = mysqli_real_escape_string($conn,  $_POST["finishing_time"]);
       $Cent_L = mysqli_real_escape_string($conn,  $_POST["Cent_L"]);
        $Cannulation_No = mysqli_real_escape_string($conn,  $_POST["Cannulation_No"]);  
        $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 

        //select anesthesia record id
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID', '$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);
                
        }
        $insert_end_of_anaesthesia = mysqli_query($conn, "INSERT INTO tbl_end_of_anaesthesia (  duration_of_anaesthesia, duration_of_operation,blood_loss, total_output, fluid_balance,Anaesthesia_notes,Comments,starting_time,total_input,finishing_time, anasthesia_record_id, Registration_ID, Employee_ID) 
        VALUES ( '$duration_of_anaesthesia', '$duration_of_operation', '$blood_loss', '$total_output','$fluid_balance', '$Anaesthesia_notes','$Comments','$starting_time', '$total_input','$finishing_time','$anasthesia_record_id', '$Registration_ID', '$Employee_ID')");
        if(!$insert_end_of_anaesthesia){
            die("Couldn't insert end anaesthesia ".mysqli_error($conn));
        } else{
            $updated_anaesthesia_record_chart = mysqli_query($conn, "UPDATE tbl_anasthesia_record_chart SET Anaesthesia_status='Completed' WHERE anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
            if($updated_anaesthesia_record_chart){
               header("location: anesthesia_record_chart.php?consultation_ID=$consultation_ID&Registration_ID=$Registration_ID"); 
            }else{
                echo "<script>alert('Did not update anaesthesia status to complete')</script>";
            }
            
        }
    }