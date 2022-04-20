<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    /*if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    } else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){
                
                    //get patient registration id for future use
                    if(isset($_GET['Registration_ID'])){
                        $Registration_ID = $_GET['Registration_ID'];
                    }else{
                        $Registration_ID = '';
                    }
		    header("Location: ./receptionsupervisorauthentication.php?Registration_ID=$Registration_ID&InvalidSupervisorAuthentication=yes");
		}
	    } 
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }*/
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
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
    
    //get the last folio number
    //find the last folio number based on selected patient
    //$get_folio = mysqli_query($conn,"select Folio_Number from tbl_patient_payments_others where
    //                            Registration_ID = '$Registration_ID'
    //                                order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    //
    //$fNumber = mysqli_num_rows($get_folio);
    //
    //if($fNumber > 0){
    //    while($Folio = mysqli_fetch_array($get_folio)){
    //        $Folio_Number = $Folio['Folio_Number'];
    //    }
    //}else{
    //    $Folio_Number = '';
    //}
    
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    
    
    //generating folio number
    if(isset($_GET['Registration_ID'])){
        $Folio_Number = 0;
        
        /*
        //select Patient sponsor id
        $get_sponsor = mysqli_query($conn,"select Sponsor_ID from tbl_patient_registration where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($get_sponsor);
        if($no > 0 ) {
            while($row = mysqli_fetch_array($get_sponsor)){
                $Sponsor_ID = $row['Sponsor_ID'];
            }
        }else{
            $Sponsor_ID = 0;
        }
        
        //GENERATING FOLIO NUMBER
        //GENERATING FOLIO NUMBER
        //GENERATING FOLIO NUMBER
        //GENERATING FOLIO NUMBER
        
        //GET BRANCH ID
        $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    
        //get the current date		
        $Today_Date = mysqli_query($conn,"select now() as today");
        while($row = mysqli_fetch_array($Today_Date)){
            $original_Date = $row['today'];
            $new_Date = date("Y-m-d", strtotime($original_Date));
            $Today = $new_Date; 
        }
        //check if the current date and the last folio number are in the same month and year
        //select the current folio number to check the month and year
        $current_folio = mysqli_query($conn,"select Folio_Number, Folio_date
                                        from tbl_folio where sponsor_id = '$Sponsor_ID' and
                                            Branch_ID = '$Folio_Branch_ID' order by folio_id desc limit 1");
        $no = mysqli_num_rows($current_folio); 
        if($no > 0){
            while($row = mysqli_fetch_array($current_folio)){
                $Folio_Number = $row['Folio_Number'];
                $Folio_date = $row['Folio_date'];
            } 
        }else{
            $Folio_Number = 1;
            $Folio_date = 0;
        }
        
        //check the current month and year (Remove the day part of the date)
        $Current_Month_and_year = substr($Today,0,7); 
        
        //check month and year of the last folio number (Remove the day part of the date)
        $Last_Folio_Month_and_year = substr($Folio_date,0,7); 
        
        //compare month and year    
        if($Last_Folio_Month_and_year == $Current_Month_and_year){
            mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
                        values(($Folio_Number+1),(select now()),'$Sponsor_ID','$Folio_Branch_ID')") or die(mysqli_error($conn));
            $Folio_Number = $Folio_Number + 1;
        }else{
            mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
                        values(1,(select now()),'$Sponsor_ID','$Folio_Branch_ID')");
            $Folio_Number = 1;
        }
            
        //END OF GENERATING FOLIO NUMBER
        //END OF GENERATING FOLIO NUMBER
        //END OF GENERATING FOLIO NUMBER
        //END OF GENERATING FOLIO NUMBER
        */
    }
    
    //end of generating folio number
    
    
    if($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0){
        
        
        //select billing type, Sponsor Name, Sponsor ID, claim form number
        $select_details = mysqli_query($conn,"select Billing_Type, Sponsor_Name, Sponsor_ID, Claim_Form_Number from tbl_reception_items_list_cache_others
                                        where Registration_ID = '$Registration_ID' and
                                            Employee_ID = '$Employee_ID' limit 1") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_details);
        if($num > 0){
            while($row = mysqli_fetch_array($select_details)){
                $Billing_Type = $row['Billing_Type'];
                $Sponsor_Name = $row['Sponsor_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
		$Claim_Form_Number = $row['Claim_Form_Number'];
            }
        }else{
            $Billing_Type = '';
            $Sponsor_Name = '';
            $Sponsor_ID = 0;
	    $Claim_Form_Number = '';
        }
        
        if($Billing_Type != '' && $Sponsor_Name != '' && $Sponsor_ID != 0){
            //insert data into tbl_patient_payments_others
            $Insert_Data_To_tbl_patient_payments = "insert into tbl_patient_payments_others(
                                registration_id,supervisor_id,employee_id,
                                    payment_date_and_time,folio_number,
                                        sponsor_id,sponsor_name,billing_type,receipt_date,branch_id,Claim_Form_Number)
                            values(
                                '$Registration_ID','$Supervisor_ID','$Employee_ID',
                                    (select now()),'$Folio_Number',
                                        '$Sponsor_ID','$Sponsor_Name','$Billing_Type',(select now()),'$Branch_ID','$Claim_Form_Number')";
                                        
            $result = mysqli_query($conn,$Insert_Data_To_tbl_patient_payments) or die(mysqli_error($conn));
            //get receipt number to use as a foreign key
        
            if($result){
                $select_Patient_Payment = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments_others
                                                        where Registration_ID = '$Registration_ID' and
                                                            Employee_ID = '$Employee_ID' and
                                                                Supervisor_ID = '$Supervisor_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                $num_rows = mysqli_num_rows($select_Patient_Payment);
                if($num_rows > 0 ){
                    while($row = mysqli_fetch_array($select_Patient_Payment)){
                        $Patient_Payment_ID =  $row['Patient_Payment_ID'];
                    }
                    //get all items from
                    $select_items = mysqli_query($conn,"select * from tbl_reception_items_list_cache_others
                                                    where Employee_ID = '$Employee_ID' and
                                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                    
                    $num_details = mysqli_num_rows($select_items);
                    
                    if($num_details > 0){
                        
                        while($row = mysqli_fetch_array($select_items)){
                            
                            //get items data                            
                            $Check_In_Type = $row['Check_In_Type'];
                            $Item_ID = $row['Item_ID'];
                            $Discount = $row['Discount'];
                            $Price = $row['Price'];
                            $Quantity = $row['Quantity'];
                            $Patient_Direction = $row['Patient_Direction'];
                            $Consultant = $row['Consultant'];
                            $Consultant_ID = $row['Consultant_ID'];
                            
                            
                            //insert selected item data
                            if(strtolower($Patient_Direction) == 'direct to doctor' || strtolower($Patient_Direction) == 'direct to doctor via nurse station'){    
                                $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list_others(
                                        check_In_type,item_id,discount,price,quantity,
                                                patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time)
                                        values(
                                            '$Check_In_Type','$Item_ID',
                                                '$Discount','$Price','$Quantity',
                                                    '$Patient_Direction','$Consultant',(select Employee_ID from tbl_employee where employee_name = '$Consultant' limit 1),
                                                        '$Patient_Payment_ID',(select now()))";
                            }elseif(strtolower($Patient_Direction) == 'direct to clinic' || strtolower($Patient_Direction) == 'direct to clinic via nurse station'){
                                $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list_others(
                                        check_In_type,item_id,discount,price,quantity,
                                                patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time)
                                        values(
                                            '$Check_In_Type','$Item_ID',
                                                '$Discount','$Price','$Quantity',
                                                    '$Patient_Direction','$Consultant',(select clinic_ID from tbl_clinic where clinic_name = '$Consultant')
                                                        ,'$Patient_Payment_ID',(select now()))";
                            }
                            
                            mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list);
                        }
                        
                        mysqli_query($conn,"delete from tbl_reception_items_list_cache_others where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        mysqli_query($conn,"update tbl_check_in set Check_In_Status = 'saved' where registration_id = '$Registration_ID' and branch_id = '$Branch_ID'");                        
                        header("Location: ./patientbillingreviewothers.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
                    }
                }
            }
        }
        
    }
    
?>