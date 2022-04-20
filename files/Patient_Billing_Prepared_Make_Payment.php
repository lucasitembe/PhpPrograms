<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Patient_Payment_Cache_ID'])){
        $Patient_Payment_Cache_ID = $_GET['Patient_Payment_Cache_ID'];
    }else{
        $Patient_Payment_Cache_ID = 0;
    }
    
    
    if($Patient_Payment_Cache_ID != 0){
        //get patient payment cache details then insert into patient payment table
        
        
    //get sponsor id, sponsor name and branch id
    $select_sponsor = mysqli_query($conn,"select Sponsor_ID, Sponsor_Name from tbl_patient_payments_cache
                                    where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_sponsor);
    if($num > 0){
        while($row = mysqli_fetch_array($select_sponsor)){
            $Sponsor_ID = $row['Sponsor_ID'];
            $Sponsor_Name = $row['Sponsor_Name'];
        }
    }else{
        $Sponsor_ID = 0;
        $Sponsor_Name = '';
    }
    
    //get registration id
    //select data from patient payment cache
    $get_details = mysqli_query($conn,"
                    select * from tbl_patient_payments_cache where
                        Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID' and transaction_status = 'submitted'") or die(mysqli_error($conn));
    
    $num = mysqli_num_rows($get_details);
    if($num > 0){    
        while($row = mysqli_fetch_array($get_details)){
            $Registration_ID = $row['Registration_ID'];
        }
    }else{
        $Registration_ID = 0;
    }
    
    //get branch id
    if(isset($_SESSION['userinfo'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    
    include("./includes/Folio_Number_Generator.php");
    
    
    //get supervisor id
    if(isset($_SESSION['supervisor'])){
        $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
    }else{
        $Supervisor_ID = 0;
    }
    //echo "Sponsor ID - ".$Sponsor_ID.". Folio number - ".$Folio_Number;
    
    
    //get employee id
    if(isset($_SESSION['userinfo'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{ 
        $Employee_ID = 0;
    }
    
if(isset($_GET['auth_code'])){
	$auth_code=$_GET['auth_code'];
}else{
	$auth_code="";
}
if(isset($_GET['terminal_id'])){
	$terminal_id=$_GET['terminal_id'];
}else{
	$terminal_id="";
}

$manual_offline=$_GET['manual_offline'];


    //select data from patient payment cache
    $get_details = mysqli_query($conn,"select * from tbl_patient_payments_cache where
                        Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID' and transaction_status = 'submitted'");
    
    $num = mysqli_num_rows($get_details);
    if($num > 0 && $Supervisor_ID != 0 && $Employee_ID != 0){
        
        while($row = mysqli_fetch_array($get_details)){
            $Registration_ID = $row['Registration_ID'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Sponsor_Name = $row['Sponsor_Name'];
            $Billing_Type = $row['Billing_Type'];
        }
        
        
        include("./includes/Get_Patient_Transaction_Number.php");
        //insert into patient payment table
        $insert = mysqli_query($conn,"insert into tbl_patient_payments(
                                Registration_ID,Supervisor_ID,Employee_ID,
                                    Payment_Date_And_Time,Folio_Number,Sponsor_ID,Check_In_ID,
                                        Sponsor_Name,Billing_Type,Receipt_Date,
                                            branch_id,Patient_Bill_ID,terminal_id,auth_code,manual_offline)
                                
                                values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                    (select now()),'$Folio_Number','$Sponsor_ID','$Check_In_ID',
                                        '$Sponsor_Name','$Billing_Type',(select now()),
                                            '$Branch_ID','$Patient_Bill_ID','$terminal_id','$auth_code','$manual_offline')") or die(mysqli_error($conn));
        
        //select patient payment id
        $select_id = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments where
                                    Employee_ID = '$Employee_ID' and
                                        registration_ID = '$Registration_ID' order by Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($select_id)){
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
        }
        
        
        //get details from patient payment item list cache then insert into patient payment item list
        $select_into_item_cache = mysqli_query($conn,"select * from tbl_patient_payment_item_list_cache where
                                                Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
        
        $num = mysqli_num_rows($select_into_item_cache);
        if($num > 0){
            while($row = mysqli_fetch_array($select_into_item_cache)){
                $Patient_Payment_Item_List_ID = $row['Patient_Payment_Item_List_ID'];
                $Check_In_Type = $row['Check_In_Type'];
                $Item_ID = $row['Item_ID'];
                $Discount = $row['Discount'];
                $Price = $row['Price'];
                $Quantity = $row['Quantity'];
                $Patient_Direction = $row['Patient_Direction'];
                $Consultant = $row['Consultant'];
                $Consultant_ID = $row['Consultant_ID'];
                $Clinic_ID = $row['Clinic_ID'];
                
                
                
                //insert into tbl_patient_payment_item_list
                
                if($Patient_Direction=='Direct To Clinic' || $Patient_Direction=='Direct To Clinic Via Nurse Station'){
                    $insert = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
                                        Check_In_Type,Item_ID,Discount,
                                            Price,Quantity,Patient_Direction,Consultant,
                                                Clinic_ID,Patient_Payment_ID,Transaction_Date_And_Time
                                    ) values(
                                        '$Check_In_Type','$Item_ID','$Discount',
                                            '$Price','$Quantity','$Patient_Direction',
                                                '$Consultant','$Consultant_ID','$Patient_Payment_ID',
                                                    (select now()))") or die(mysqli_error($conn));
                }else{
                $insert = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
                                        Check_In_Type,Item_ID,Discount,
                                            Price,Quantity,Patient_Direction,Consultant,
                                                Consultant_ID,Patient_Payment_ID,Transaction_Date_And_Time,Clinic_ID
                                    ) values(
                                        '$Check_In_Type','$Item_ID','$Discount',
                                            '$Price','$Quantity','$Patient_Direction',
                                                '$Consultant','$Consultant_ID','$Patient_Payment_ID',
                                                    (select now()),'$Clinic_ID')") or die(mysqli_error($conn));
                }
                if($insert){
                    mysqli_query($conn,"delete from tbl_patient_payment_item_list_cache
                                    where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
                
                }
            }
            
            //select items from patient payment item list to
            //make sure there is no any item befor to delete the cache
            
            $select_items = mysqli_query($conn,"select * from tbl_patient_payment_item_list_cache
                                            where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
            
            $no = mysqli_num_rows($select_items);
            if($no < 1){
                mysqli_query($conn,"update tbl_check_in set Check_In_Status = 'saved' where registration_id = '$Registration_ID' and branch_id = '$Branch_ID'");
                mysqli_query($conn,"delete from tbl_patient_payments_cache where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
                header("Location: ./patientbillingreview.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
            }
            
        }
        
    }
    }

?>