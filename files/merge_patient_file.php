<?php

   include("./includes/connection.php");
   session_start();
   $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
   $old_Registration_ID= $_POST['old_Registration_ID'];
   $new_Registration_ID= $_POST['new_Registration_ID'];

   $check_if_exist=mysqli_query($conn,"SELECT Registration_ID FROM tbl_patient_registration WHERE Registration_ID='$new_Registration_ID'");
        if(mysqli_num_rows($check_if_exist)>0){
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_patient_registration SET patient_merge='merged' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_consultation SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_check_in SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_payment_cache SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_patient_payments SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_admission SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_ward_round SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_patient_bill SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_check_in_details SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_inpatient_medicines_given SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_attachment SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_post_operative_notes SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            $merge_patient_file=mysqli_query($conn,"UPDATE tbl_prepaid_details SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            // ************************DIALYSIS TABLES***********************************
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_dialysis_details SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_dialysis_doctor_notes SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_dialysis_incident_records SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_dialysis_inpatient_prescriptions SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_dialysis_monthly_rounds SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_dialysis_oder SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_dialysis_temporary_neck_line SET Registration_ID='$old_Registration_ID' WHERE Registration_ID='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_dialysis_vitals SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_save_machine_access SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_heparain_save SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_access_orders SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_data_collection SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_observation_chart SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // $merge_patient_file=mysqli_query($conn,"UPDATE tbl_medication_chart SET Patient_reg='$old_Registration_ID' WHERE Patient_reg='$new_Registration_ID'")  or die(mysqli_error($conn));
            // ***********************************************************************
            
            if($merge_patient_file){
                echo "Successfull merged";
            }
        }else{
            echo "PATIENT WITH REGISTRTAION NUMBER ".$new_Registration_ID." DOES NOT EXIST";
        }
        

            
         
  
?>