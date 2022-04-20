<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){

    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
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
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    
    
    
    $Folio_Number = 0;
    //end of generating folio number
    
    
    if($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0){
        
        
        //select billing type, Sponsor Name, Sponsor ID, claim form number
        $select_details = mysqli_query($conn,"select Billing_Type, Sponsor_Name, Sponsor_ID, Claim_Form_Number from tbl_reception_items_list_cache
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
            //insert data into tbl_patient_payments_mobile
            $Insert_Data_To_tbl_patient_payments_mobile = "insert into tbl_patient_payments_mobile(
                                registration_id,supervisor_id,employee_id,
                                    payment_date_and_time,folio_number,
                                        sponsor_id,sponsor_name,billing_type,receipt_date,branch_id,Claim_Form_Number,Payment_Mode)
                            values(
                                '$Registration_ID','$Supervisor_ID','$Employee_ID',
                                    (select now()),'$Folio_Number',
                                        '$Sponsor_ID','$Sponsor_Name','$Billing_Type',(select now()),'$Branch_ID','$Claim_Form_Number','Mobile')";
                                        
            $result = mysqli_query($conn,$Insert_Data_To_tbl_patient_payments_mobile) or die(mysqli_error($conn));
            //get receipt number to use as a foreign key
            if($result){
                $select_Patient_Payment = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments_mobile
                                                        where Registration_ID = '$Registration_ID' and
                                                            Employee_ID = '$Employee_ID' and
                                                                Supervisor_ID = '$Supervisor_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                $num_rows = mysqli_num_rows($select_Patient_Payment);
                if($num_rows > 0 ){
                    while($row = mysqli_fetch_array($select_Patient_Payment)){
                        $Patient_Payment_ID =  $row['Patient_Payment_ID'];
                    }
                    //get all items from
                    $select_items = mysqli_query($conn,"select * from tbl_reception_items_list_cache
                                                    where Employee_ID = '$Employee_ID' and
                                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                    
                    $num_details = mysqli_num_rows($select_items);
                    $total = 0;
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
                            
                            $total = $total + ($Quantity * ($Price - $Discount));
			    
                            //insert selected item data
                            if(strtolower($Patient_Direction) == 'direct to doctor' || strtolower($Patient_Direction) == 'direct to doctor via nurse station'){    
                                $Insert_Data_To_tbl_patient_payment_item_list_mobile = "insert into tbl_patient_payment_item_list_mobile(
                                        check_In_type,item_id,discount,price,quantity,
                                                patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time)
                                        values(
                                            '$Check_In_Type','$Item_ID',
                                                '$Discount','$Price','$Quantity',
                                                    '$Patient_Direction','$Consultant',(select Employee_ID from tbl_employee where employee_name = '$Consultant' limit 1),
                                                        '$Patient_Payment_ID',(select now()))";
                            }elseif(strtolower($Patient_Direction) == 'direct to clinic' || strtolower($Patient_Direction) == 'direct to clinic via nurse station'){
                                $Insert_Data_To_tbl_patient_payment_item_list_mobile = "insert into tbl_patient_payment_item_list_mobile(
                                        check_In_type,item_id,discount,price,quantity,
                                                patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time)
                                        values(
                                            '$Check_In_Type','$Item_ID',
                                                '$Discount','$Price','$Quantity',
                                                    '$Patient_Direction','$Consultant',(select clinic_ID from tbl_clinic where clinic_name = '$Consultant')
                                                        ,'$Patient_Payment_ID',(select now()))";
                            }
                            
                            mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list_mobile);
                        }
                        //generate payment code
			$Total_Char = 8;
			$Temp_Char = strlen($Patient_Payment_ID);
			$Initial_Char = '003';
			$Final_Value = '';
			//generate Last Value
			for($i = 5 - $Temp_Char; $i > 0; $i--){
			    $Final_Value = $Final_Value.'0';
			}
			$Payment_Code = $Initial_Char.$Final_Value.$Patient_Payment_ID;
			
			//get patient name
			$sql_select = mysqli_query($conn,"select Patient_Name from tbl_Patient_Registration where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
			$num = mysqli_num_rows($sql_select);
			if($num > 0){
			    while($name = mysqli_fetch_array($sql_select)){
				$Patient_Name = $name['Patient_Name'];
			    }
			}else{
			    $Patient_Name = '';
			}
			
			//get current date
			$sql_date = mysqli_query($conn,"select now() as Date");
			while($row = mysqli_fetch_array($sql_date)){
			    $Current_Date_Time = $row['Date'];
			}
			
			
			mysqli_query($conn,"insert into ehms_mobile.tbl_payments(
					payment_code, payment_date, payment_mode, amount, payment_status, customer_name, Registration_ID, Patient_Payment_ID)
					values('$Payment_Code',(select now()),'mPESA','$total','not paid','$Patient_Name','$Registration_ID','$Patient_Payment_ID')") or die(mysqli_error($conn));
			
			$theURL = "http://gpitg.com/api/add.php?ClientName=".$Patient_Name."&Amount=".$total."&PaymentCode=".$Payment_Code."&Date=".$Current_Date_Time."&PatientPaymentID=".$Patient_Payment_ID;
			$addthepayment  = simplexml_load_file($theURL);
			$status = $addthepayment->status;
			
                        mysqli_query($conn,"delete from tbl_reception_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        mysqli_query($conn,"update tbl_check_in set Check_In_Status = 'saved' where registration_id = '$Registration_ID' and branch_id = '$Branch_ID'");
                        header("Location: ./mobilepaymentsdetails.php?Registration_ID=$Registration_ID&Payment_Code=$Payment_Code&Total=$total&PatientBilling=PatientBillingThisForm");
                    }
                }
            }
        }
    }
    
?>