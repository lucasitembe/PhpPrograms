<?php
    @session_start();
    include("./includes/connection.php");

    if(!isset($_SESSION['supervisor'])){ 
        header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
    }

            
    //---get supervisor id 
        if(isset($_SESSION['supervisor'])) {
            if(isset($_SESSION['supervisor']['Session_Master_Priveleges'])){
                if($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes'){
                    $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
                }else{
                    $Supervisor_ID = '';
                }
            }else{
                 $Supervisor_ID = '';
            }
        }else{
                 $Supervisor_ID = '';
        }
    //end of fetching supervisor id
    
   
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
    $Employee_ID = 0;
    }
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
    $Branch_ID = 0;
    }
    

    //get registration id
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get claim form number
    if(isset($_GET['Claim_Form_Number'])){
        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    }else{
        $Claim_Form_Number = 0;
    }
    
    
    //get Folio Number
    if(isset($_GET['Folio_Number'])){
        $Folio_Number = $_GET['Folio_Number'];
    }else{
        $Folio_Number = 0;
    }
    
    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;

    if($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0){
        //select all data from the laboratory cache table
        $select = mysqli_query($conn,"select * from tbl_laboratory_items_list_cache where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
        $num_rows = mysqli_num_rows($select);
        if($num_rows > 0){
            while($data = mysqli_fetch_array($select)){
                $Sponsor_ID = $data['Sponsor_ID'];
                $Sponsor_Name = $data['Sponsor_Name'];
                $Billing_Type = $data['Billing_Type'];
                $Sponsor_Name = $data['Sponsor_Name'];
            }
            
            
            //generate transaction type
            if(strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash'){
                $Transaction_Type = 'Cash';
            }else if(strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit'){
                $Transaction_Type = 'Credit';
            }
            
            //insert data to payment cache
            $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID')") or die(mysqli_error($conn));
            if($insert_data){
                //get the last Payment_Cache_ID (foreign key)
                $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if($no > 0){
                    while($row = mysqli_fetch_array($select)){
                        $Payment_Cache_ID = $row['Payment_Cache_ID'];
                    }
                    //insert data
                    $select_details = mysqli_query($conn,"select * from tbl_laboratory_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    $numrows = mysqli_num_rows($select_details);
                    if($numrows > 0){
                        while($dt = mysqli_fetch_array($select_details)){
                            $Item_ID = $dt['Item_ID'];
                            $Price = $dt['Price'];
                            $Quantity = $dt['Quantity'];
                            $Consultant_ID = $Employee_ID;
                            $Comment = $dt['Comment'];
                            $Sub_Department_ID = $dt['Sub_Department_ID'];
                            $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                                Check_In_Type, Item_ID,Price,
                                                Quantity, Patient_Direction, Consultant_ID,
                                                Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time,Status
                                                ) values(
                                                    'Laboratory','$Item_ID','$Price',
                                                    '$Quantity','others','$Consultant_ID',
                                                    '$Payment_Cache_ID',(select now()),
                                                    '$Comment','$Sub_Department_ID','$Transaction_Type',(select now()),'paid')");
                        }
                    }
                    
                    if($insert){
                        //check if patient exists into tbl_check_in
                        $check = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' and Visit_Date = '$Today'") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($check);
                        if($num < 1){
                            $insert2 = mysqli_query($conn,"INSERT INTO tbl_check_in(
                                                    Registration_ID, Visit_Date, 
                                                    Employee_ID, Check_In_Date_And_Time, Check_In_Status, 
                                                    Branch_ID, Saved_Date_And_Time, Check_In_Date,
                                                    Type_Of_Check_In, Folio_Status) 
                                                    VALUES ('$Registration_ID',(select now()),
                                                            '$Employee_ID',(select now()),'saved',
                                                            '$Branch_ID',(select now()),(select now()),
                                                            'Afresh','generated')");
                        }


                        //get the last check in id
                        $get_check_id = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
                        $nums = mysqli_num_rows($get_check_id);
                        if($nums > 0){
                            while ($dta = mysqli_fetch_array($get_check_id)) {
                                $Check_In_ID = $dta['Check_In_ID'];
                            }
                        }else{
                            $Check_In_ID = 0;
                        }

                        //insert details to tbl_patient_payments
                        $insert_details = mysqli_query($conn,"INSERT INTO tbl_patient_payments(
                                                        Registration_ID, Supervisor_ID, Employee_ID,
                                                        Payment_Date_And_Time, Folio_Number,
                                                        Claim_Form_Number, Sponsor_ID, Sponsor_Name,
                                                        Billing_Type, Receipt_Date, branch_id,Check_In_ID)
                                                        
                                                        VALUES ('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                                            (select now()),'$Folio_Number',
                                                            '$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
                                                            '$Billing_Type',(select now()),'$Branch_ID','$Check_In_ID')") or die(mysqli_error($conn));


                        if($insert_details){
                            //get the last Patient Payment ID
                            $select = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date, Payment_Date_And_Time from tbl_patient_payments
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select);
                            if($num > 0){
                                while($row = mysqli_fetch_array($select)){
                                    $Patient_Payment_ID = $row['Patient_Payment_ID'];
                                    $Receipt_Date = $row['Receipt_Date'];
                                    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                                }
                                
                                //insert data to tbl_patient_payment_item_list
                                $select_details = mysqli_query($conn,"select * from tbl_laboratory_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                    Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                
                                while($dt = mysqli_fetch_array($select_details)){
                                    $Item_ID = $dt['Item_ID'];
                                    $Price = $dt['Price'];
                                    $Quantity = $dt['Quantity'];
                                    $Consultant_ID = $Employee_ID;
                                    $Dosage = $dt['Dosage'];
                                    $insert = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(
                                                            Check_In_Type, Item_ID, Price,
                                                            Quantity, Patient_Direction, Consultant,
                                                            Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time)
                                                            
                                                            VALUES ('Laboratory','$Item_ID','$Price',
                                                            '$Quantity','others','others',
                                                            '$Consultant_ID', '$Patient_Payment_ID', (select now()))") or die(mysqli_error($conn));
                                    
                                }
                                if($insert){
                                    //update tbl_item_list_cache
                                    mysqli_query($conn,"update tbl_item_list_cache set
                                                    Patient_Payment_ID = '$Patient_Payment_ID',
                                                        Payment_Date_And_Time = '$Receipt_Date' where
                                                            Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
                                    
                                    //delete all data from cache
                                    mysqli_query($conn,"delete from tbl_laboratory_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                    header("Location: ./laboratoryothersworks.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
                                }else{
                                    header("Location: ./laboratoryothersworkspage.php?Registration_ID=$Registration_ID&LaboratoryPatientBilling=LaboratoryPatientBillingThisForm");
                                }
                            }
                        }
                    }else{
                        header("Location: ./laboratoryothersworkspage.php?Registration_ID=$Registration_ID&LaboratoryPatientBilling=LaboratoryPatientBillingThisForm");
                    }
                }
            }
        }
    }
}
?>