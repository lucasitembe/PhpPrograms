<?php
    @session_start();
    include("./includes/connection.php");
    require_once './functions/items.php';
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
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
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
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
  
    
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    
    
    include("./includes/Folio_Number_Generator.php");
    

    if($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0){
        
        
        //select billing type, Sponsor Name, Sponsor ID, claim form number
        $select_details = mysqli_query($conn,"SELECT Billing_Type, Sponsor_Name, Sponsor_ID, Claim_Form_Number from tbl_reception_items_list_cache
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
        
        include("./includes/Get_Patient_Transaction_Number.php");
        
        if($Billing_Type != '' && $Sponsor_Name != '' && $Sponsor_ID != 0){
            //insert data into tbl_patient_payments
            $Insert_Data_To_tbl_patient_payments = "INSERT INTO tbl_patient_payments(
                                registration_id,supervisor_id,employee_id,
                                    payment_date_and_time,folio_number,Check_In_ID,
                                        sponsor_id,sponsor_name,billing_type,receipt_date,branch_id,Claim_Form_Number,Patient_Bill_ID)
                            values(
                                '$Registration_ID','$Supervisor_ID','$Employee_ID',
                                    (select now()),'$Folio_Number','$Check_In_ID',
                                        '$Sponsor_ID','$Sponsor_Name','$Billing_Type',(select now()),'$Branch_ID','$Claim_Form_Number','$Patient_Bill_ID')";
                                        
            $result = mysqli_query($conn,$Insert_Data_To_tbl_patient_payments) or die(mysqli_error($conn));
            //get receipt number to use as a foreign key
        
            if($result){

                $insert_data_this = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                    Registration_ID, Employee_ID, Payment_Date_And_Time,
                    Folio_Number, Sponsor_ID,
                    Billing_Type, Receipt_Date, branch_id, Check_In_ID)
                        
                    values('$Registration_ID','$Employee_ID',(select now()),
                            '$Folio_Number','$Sponsor_ID',
                            '$Billing_Type',(select now()),'$Branch_ID', '$Check_In_ID')") or die(mysqli_error($conn));


        if($insert_data_this){
            $Payment_Cache_ID = mysqli_insert_id($conn);
        }
                $select_Patient_Payment = mysqli_query($conn,"SELECT Patient_Payment_ID from tbl_patient_payments
                                                        where Registration_ID = '$Registration_ID' and
                                                            Employee_ID = '$Employee_ID' and
                                                                Supervisor_ID = '$Supervisor_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

                $num_rows = mysqli_num_rows($select_Patient_Payment);
                if($num_rows > 0 ){
                    while($row = mysqli_fetch_array($select_Patient_Payment)){
                        $Patient_Payment_ID =  $row['Patient_Payment_ID'];
                    }
                    //get all items from
                    $select_items = mysqli_query($conn,"SELECT * from tbl_reception_items_list_cache
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
                            $Clinic_ID = $row['Clinic_ID'];
                            $clinic_location_id = $row['clinic_location_id'];
                            $finance_department_id = $row['finance_department_id'];
                            
                            //insert selected item data
                            if(strtolower($Patient_Direction) == 'direct to doctor' || strtolower($Patient_Direction) == 'direct to doctor via nurse station'){    
                                $Insert_Data_To_tbl_patient_payment_item_list = "INSERT into tbl_patient_payment_item_list(
                                        check_In_type,item_id,discount,price,quantity,
                                                patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,Clinic_ID,Sub_Department_ID,finance_department_id)
                                        values(
                                            '$Check_In_Type','$Item_ID',
                                                '$Discount','$Price','$Quantity',
                                                    '$Patient_Direction','$Consultant',(select Employee_ID from tbl_employee where employee_name = '$Consultant' limit 1),
                                                        '$Patient_Payment_ID',(select now()),'$Clinic_ID','$clinic_location_id','$finance_department_id')";
                            }elseif(strtolower($Patient_Direction) == 'direct to clinic' || strtolower($Patient_Direction) == 'direct to clinic via nurse station'){
                                $Insert_Data_To_tbl_patient_payment_item_list = "INSERT into tbl_patient_payment_item_list(
                                        check_In_type,item_id,discount,price,quantity,
                                                patient_direction,consultant,clinic_id,patient_payment_id,Transaction_Date_And_Time,Sub_Department_ID,finance_department_id)
                                        values(
                                            '$Check_In_Type','$Item_ID',
                                                '$Discount','$Price','$Quantity',
                                                    '$Patient_Direction','$Consultant',(select clinic_ID from tbl_clinic where clinic_name = '$Consultant' limit 1)
                                                        ,'$Patient_Payment_ID',(select now()),'$clinic_location_id','$finance_department_id')";
                            }elseif(strtolower($Patient_Direction) == 'others'){
				$Insert_Data_To_tbl_patient_payment_item_list = "INSERT into tbl_patient_payment_item_list(
                                        check_In_type,item_id,discount,price,quantity,
                                            patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,Sub_Department_ID,finance_department_id)
                                        values(
                                            '$Check_In_Type','$Item_ID',
                                                '$Discount','$Price','$Quantity',
                                                    '$Patient_Direction','$Consultant','$Employee_ID'
                                                        ,'$Patient_Payment_ID',(select now()),'$clinic_location_id','$finance_department_id')";
			    }
                            

                            mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list);

                            $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                Check_In_Type, Item_ID,Price,Discount,
                                Quantity, Patient_Direction, 
                                Status, Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time,Clinic_ID,clinic_location_id,Consultant_ID,finance_department_id
                                ) values(
                                    'Doctor Room','$Item_ID','$Price','$Discount',
                                    '$Quantity','$Patient_Direction',
                                    'active','$Payment_Cache_ID',(select now()),
                                    '$Comment','$clinic_location_id','Cash',(select now()),'$Clinic_ID','$clinic_location_id','$Employee_ID','$finance_department_id')") or die(mysqli_error($conn));
                        }
                        
                        mysqli_query($conn,"DELETE from tbl_reception_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        mysqli_query($conn,"UPDATE tbl_check_in set Check_In_Status = 'saved' where registration_id = '$Registration_ID' and branch_id = '$Branch_ID'");   

                        header("Location: ./receptionpatientbillingreview.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
                    }
                }
            }
        }
        
    }
    
?>