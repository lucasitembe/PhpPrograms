<?php
    @session_start();
    include("./includes/connection.php");

    //get registration id
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];    
    }else{
        $Registration_ID = 0;
    }
    
    //get temp registration id
    if(isset($_GET['Temp_Registration_ID'])){
        $Temp_Registration_ID = $_GET['Temp_Registration_ID'];    
    }else{
        $Temp_Registration_ID = 0;
    }
    
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    //select details based on details submited and status
    
    $select = mysqli_query($conn,"select Patient_Payment_Cache_ID from tbl_patient_payments_cache where
                            Registration_ID = '$Temp_Registration_ID' and
                                Employee_ID = '$Employee_ID' and
                                    Transaction_status = 'pending'") or die(mysqli_error($conn));
    
    $no = mysqli_num_rows($select);
    if($no > 0){
        while($dec = mysqli_fetch_array($select)){
            $Patient_Payment_Cache_ID = $dec['Patient_Payment_Cache_ID'];
        }
        
        //delete items based on selected patient payment cache id
        $Delete_Details = mysqli_query($conn,"delete from tbl_patient_payment_item_list_cache
                                        where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
        if($Delete_Details){
            $Delete_Prepared_Receipt = mysqli_query($conn,"delete from tbl_patient_payments_cache
                                                        where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
            if($Delete_Prepared_Receipt){
                header("Location: ./patientbillingprepare.php?Registration_ID=$Registration_ID&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm");        
            }else{
                header("Location: ./patientbillingprepare.php?Registration_ID=$Temp_Registration_ID&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm");        
            }
        }else{
            header("Location: ./patientbillingprepare.php?Registration_ID=$Temp_Registration_ID&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm");        
        }
        
    }else{
        header("Location: ./patientbillingprepare.php?Registration_ID=$Temp_Registration_ID&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm");        
    }
    
    
    
?>