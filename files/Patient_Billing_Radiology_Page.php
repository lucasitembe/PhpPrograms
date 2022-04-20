<?php
    @session_start();
    include("./includes/connection.php");
    require_once './functions/items.php';
    //echo $Supervisor = $_SESSION['supervisor']['Employee_Name'];
    $imp='';
   
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

    //get all required information
    if(isset($_GET['Payment_Cache_ID'])){
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    }else{
        $Payment_Cache_ID = '';
    }
     if(isset($_GET['itemzero'])){
        $itemzero = $_GET['itemzero'];
		$zeroit=  explode("achanisha", $itemzero);
		//remove emprty
		$zeroppilc=array();
		foreach ($zeroit as $value) {
		  if(!empty($value)){
			  $zeroppilc[]=$value;
		  }
		}
		$imp='AND Payment_Item_Cache_List_ID NOT IN ('.implode(',', $zeroppilc).')';
    }else{
        $itemzero = '';
    }
    if(isset($_GET['Transaction_Type'])){
        $Transaction_Type = $_GET['Transaction_Type'];
    }else{
        $Transaction_Type = '';
    }
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    if(isset($_GET['Payment_Cache_ID'])){
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    }else{
        $Payment_Cache_ID = '';
    }
    if(isset($_GET['Billing_Type'])){
        $Temp_Billing_Type = strtolower($_GET['Billing_Type']);
    }else{
        $Temp_Billing_Type = '';
    }
    
    if($Temp_Billing_Type == 'outpatientcash' && strtolower($Transaction_Type) == 'cash'){
        $Billing_Type = 'Outpatient Cash';
    }elseif($Temp_Billing_Type == 'outpatientcash' && strtolower($Transaction_Type) == 'credit'){
        $Billing_Type = 'Outpatient Credit';
    }
    
    elseif($Temp_Billing_Type == 'outpatientcredit' && strtolower($Transaction_Type) == 'cash'){
        $Billing_Type = 'Outpatient Cash';
    }elseif($Temp_Billing_Type == 'outpatientcredit' && strtolower($Transaction_Type) == 'credit'){
        $Billing_Type = 'Outpatient Credit';
    }
   
    elseif($Temp_Billing_Type == 'inpatientcash' && strtolower($Transaction_Type) == 'cash'){
        $Billing_Type = 'Inpatient Cash';
    }elseif($Temp_Billing_Type == 'inpatientcash' && strtolower($Transaction_Type) == 'credit'){
        $Billing_Type = 'Inpatient Credit';
    }
    
    elseif($Temp_Billing_Type == 'inpatientcredit' && strtolower($Transaction_Type) == 'cash'){
        $Billing_Type = 'Inpatient Cash';
    }elseif($Temp_Billing_Type == 'inpatientcredit' && strtolower($Transaction_Type) == 'credit'){
        $Billing_Type = 'Inpatient Credit';
    }
    
    else{
        $Billing_Type = 'Patient From Outside';
    }
    
    
     $zeroit=  explode("achanisha", $itemzero);
    //remove emprty
    $zeroppilc=array();
    foreach ($zeroit as $value) {
      if(!empty($value)){
          $zeroppilc[]=$value;
      }
    }
    //echo $Payment_Cache_ID; exit;
    //echo strtolower($Billing_Type); exit;
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
    
    //get branch ID
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Branch_ID'])){
            $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        }else{
        $Branch_ID = '';
        }
    }else{
        $Branch_ID = '';
    }
    //end of fetching branch ID
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }

    
    //insert related information to generate receipt
    //get data from cache table then insert
    
    $select_cache_info = "select * from tbl_payment_cache where payment_cache_id = '$Payment_Cache_ID'";
    $result = mysqli_query($conn,$select_cache_info);
    while($row = mysqli_fetch_array($result)){
        $Registration_ID = $row['Registration_ID'];
        $Folio_Number = $row['Folio_Number'];
        $Sponsor_ID = $row['Sponsor_ID'];
        $Sponsor_Name = $row['Sponsor_Name'];
        $Transaction_status = 'active';
        $Transaction_type = 'indirect cash';
        //select the last claim form number
        $select_claim_form = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where
                                                Folio_number = '$Folio_Number' and
                                                    Registration_ID = '$Registration_ID' and
                                                        Sponsor_ID = '$Sponsor_ID'
                                                            order by patient_payment_id desc limit 1");
        while($row = mysqli_fetch_array($select_claim_form)){
            $Claim_Form_Number = $row['Claim_Form_Number'];
        }
        
        include("./includes/Get_Patient_Check_In_Id.php");
        include("./includes/Get_Patient_Transaction_Number.php");

        //time to insert data
        if(strtolower($Billing_Type) == 'inpatient cash' || strtolower($Billing_Type) == 'inpatient credit'){
            include("./includes/Get_Patient_Hospital_Ward_ID.php");
            $insert = "insert into tbl_patient_payments(
                        Registration_ID,Supervisor_ID,Employee_ID,
                            Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
                                Sponsor_ID,Sponsor_Name,Billing_Type,
                                    Receipt_Date,Transaction_status,Transaction_type,Branch_ID,Patient_Bill_ID,Check_In_ID,payment_type,Hospital_Ward_ID,terminal_id,auth_code,manual_offline)
                                    
                    values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                (select now()),'$Folio_Number','$Claim_Form_Number',
                                    '$Sponsor_ID','$Sponsor_Name','$Billing_Type',
                                        (select now()),'$Transaction_status','$Transaction_type','$Branch_ID','$Patient_Bill_ID','$Check_In_ID','pre','$Hospital_Ward_ID', '$terminal_id','$auth_code','$manual_offline')";
        }else{
            $insert = "insert into tbl_patient_payments(
                        Registration_ID,Supervisor_ID,Employee_ID,
                            Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
                                Sponsor_ID,Sponsor_Name,Billing_Type,
                                    Receipt_Date,Transaction_status,Transaction_type,Branch_ID,Patient_Bill_ID,Check_In_ID,payment_type,terminal_id,auth_code,manual_offline)
                                    
                    values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                (select now()),'$Folio_Number','$Claim_Form_Number',
                                    '$Sponsor_ID','$Sponsor_Name','$Billing_Type',
                                        (select now()),'$Transaction_status','$Transaction_type','$Branch_ID','$Patient_Bill_ID','$Check_In_ID','pre', '$terminal_id','$auth_code','$manual_offline')";
        }
        if(mysqli_query($conn,$insert)){ 
            //get patient payment id to use as a foreign key
            $select_patient_payment_id = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where
                                            Registration_ID = '$Registration_ID' and
                                                Supervisor_ID = '$Supervisor_ID' and
                                                    Employee_ID = '$Employee_ID' order by patient_payment_id desc limit 1");
            $no = mysqli_num_rows($select_patient_payment_id);
            if($no > 0){
                while($row2 = mysqli_fetch_array($select_patient_payment_id)){
                    $Patient_Payment_ID = $row2['Patient_Payment_ID'];
                    $Payment_Date_And_Time = $row2['Payment_Date_And_Time'];
                }
            }else{
                $Patient_Payment_ID = '';
                $Payment_Date_And_Time = '';
            } 
            
            if($Patient_Payment_ID !='' && $Payment_Date_And_Time != ''){
                
                //get all medication and then insert
                $select_medication = mysqli_query($conn,"select * from tbl_item_list_cache where
                                                    payment_cache_id = '$Payment_Cache_ID' and 
                                                        Transaction_Type = '$Transaction_Type' and
                                                            sub_department_id = '$Sub_Department_ID' and
                                                                status = 'active' $imp");    
                while($row3 = mysqli_fetch_array($select_medication)){
                    $Payment_Item_Cache_List_ID = $row3['Payment_Item_Cache_List_ID'];
                    $Check_In_Type = $row3['Check_In_Type'];
                    $Item_ID = $row3['Item_ID'];
                    $Discount = $row3['Discount'];
                    $Price = $row3['Price']; 
                    $Patient_Direction = $row3['Patient_Direction'];
                    $Consultant = $row3['Consultant'];
                    $Consultant_ID = $row3['Consultant_ID'];
                    $Quantity = $row3['Quantity'];
                    $Clinic_ID = $row3['Clinic_ID'];
                    $finance_department_id = $row3['finance_department_id'];
                    $Sub_Department_ID = $row3['Sub_Department_ID'];
                    
                    //insert select record
                    $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
                                    check_In_type,item_id,discount,
                                        price,quantity,patient_direction,
                                            consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,ItemOrigin,Clinic_ID,finance_department_id,Sub_Department_ID)
                                    values(
                                        '$Check_In_Type','$Item_ID','$Discount',
                                            '$Price','$Quantity','$Patient_Direction',
                                                '$Consultant','$Consultant_ID','$Patient_Payment_ID',(select now()),'Doctor','$Clinic_ID','$finance_department_id','$Sub_Department_ID')";
                    if(!mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list)){ 
		    //die(mysqli_error($conn));
		    /*echo "<script type='text/javascript'>
			    alert('TRANSACTION FAIL');
			    document.location = './patientbilling.php?Fail=True&TarnsactionFail=TransactionFailThisPage';
			    </script>";*/
                    }else{
                        mysqli_query($conn,"update tbl_item_list_cache set status = 'paid',
                                        Patient_Payment_ID = '$Patient_Payment_ID',
                                        Payment_Date_And_Time = '$Payment_Date_And_Time' 
                                        where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
                    }
                }

                /////////////////////////////////////////// Journal entry //////////////////////////////////////////

//                $payDetails = getPaymentsDetailsByReceiptNumber($Patient_Payment_ID);
//
//                $Product_Array=array();
//
//                $Product_Name_Array = array(
//                    'source_name' => 'ehms_sales_cash',
//                    'comment' => 'Receipt # ' . $Patient_Payment_ID . ", Date:" . $payDetails['Payment_Date_And_Time'] . ", Trans_Type:" . $payDetails['Payment_Mode'] . " Amount:  " . $payDetails['TotalAmount'] . " Tsh.",
//                    'debit_entry_ledger' => 'CASH IN HAND',
//                    'credit_entry_ledger' => 'SALES',
//                    'sub_total' => $payDetails['TotalAmount'],
//                    'source_id' => $Patient_Payment_ID,
//                    'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
//                    'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
//                );
//
//                array_push($Product_Array, $Product_Name_Array);
//
//
//
//               $acc = gAccJournalEntry(json_encode($Product_Array));
              
               //////////////////////////////////////////////////////////////////////////////////////////
				
                header("Location: ./radiologypatientbilling.php?section=Radiology&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&Billing_Type=$Billing_Type&Patient_Payment_ID=$Patient_Payment_ID&Payment_Date_And_Time=$Payment_Date_And_Time&Sub_Department_ID=$Sub_Department_ID&RadiologyWorks=RadiologyWorksThisPage"); 
            }
        }else{
            die(mysqli_error($conn));
        }
    }
    
    

?>