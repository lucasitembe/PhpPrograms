<?php
    @session_start();
    include("./includes/connection.php");
    require_once './functions/items.php';
    $location='';
    if(isset($_GET['location']) && $_GET['location']=='otherdepartment'){
        $location='location=otherdepartment&';
    }
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
	$Employee_ID = 0;
    }
    
    //---get supervisor id 
    if(isset($_SESSION['supervisor'])) {
        $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
    }else{
        $Supervisor_ID = $Employee_ID;
    }
    //end of fetching supervisor id

    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
        
    if(isset($_GET['Consultant_ID'])){
        $Consultant_ID = $_GET['Consultant_ID'];
    }else{
        $Consultant_ID = '';
    }

    //get registration id
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
    }else{
        $Section = 'Reception';
    }

    //get sponsor id && name
    $get_spo = mysqli_query($conn,"select sp.Sponsor_ID, sp.Guarantor_Name, Require_Document_To_Sign_At_receiption from tbl_sponsor sp, tbl_patient_registration pr where
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($get_spo);
    if($nm > 0){
        while ($dtails = mysqli_fetch_array($get_spo)) {
            $Sponsor_ID = $dtails['Sponsor_ID'];
            $Guarantor_Name = $dtails['Guarantor_Name'];
            $Require_Document_To_Sign_At_receiption = $dtails['Require_Document_To_Sign_At_receiption'];
        }
    }else{
        $Sponsor_ID = '';
        $Guarantor_Name = '';
        $Require_Document_To_Sign_At_receiption = '';
    }

    //get claim form number
    if(isset($_GET['Claim_Form_Number'])){
        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    }else{
        $Claim_Form_Number = 0;
    }
    
    //get visit type
    if(isset($_GET['Visit_Type'])){
        $Visit_Type = $_GET['Visit_Type'];
    }else{
        $Visit_Type = '';
    }

    //get visit controler to control folio number generation
    if(isset($_GET['Visit_Controler'])){
        $Visit_Controler = $_GET['Visit_Controler'];
    }else{
        $Visit_Controler = '';
    }


    //get folio number
    if($Visit_Controler == 'new' || $Visit_Type == 'CT SCAN' || $Visit_Type == 'PATIENT FROM OUTSIDE'){
        
        //Create New Check in
        $Create = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date,Employee_ID, 
                                    Check_In_Date_And_Time, Check_In_Status,Branch_ID, 
                                    Saved_Date_And_Time, Check_In_Date,Type_Of_Check_In, Folio_Status) 
                                VALUES ('$Registration_ID',(select now()),'$Employee_ID',
                                    (select now()),'saved','$Branch_ID',
                                    (select now()),(select now()),'Afresh','generated')") or die(mysqli_error($conn));
        if($Create){
            //select Check In ID
            $slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
            $slct_num = mysqli_num_rows($slct);
            if($slct_num > 0){
                while ($dt = mysqli_fetch_array($slct)) {
                    $Check_In_ID = $dt['Check_In_ID'];
                }
            }else{
                $Check_In_ID = '';
            }
        }else{
            $Check_In_ID = '';
        }

        //Create Patient Bill ID
        $Create_PBI = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID, Date_Time) values('$Registration_ID',(select now()))") or die(mysqli_error($conn));
        if($Create_PBI){
            $slct_bill_id = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
            $num_Create = mysqli_num_rows($slct_bill_id);
            if($num_Create > 0){
                while ($td = mysqli_fetch_array($slct_bill_id)) {
                    $Patient_Bill_ID = $td['Patient_Bill_ID'];
                }
            }else{
                $Patient_Bill_ID = '';
            }
        }else{
            $Patient_Bill_ID = '';
        }

        include("./includes/Folio_Number_Generator_temp.php");


        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    }else{
        //get last folio number, Claim form number
        $get_folio = mysqli_query($conn,"select Claim_Form_Number, Folio_Number from tbl_patient_payments where
                                    Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
        $num_get = mysqli_num_rows($get_folio);
        if($num_get > 0){
            while ($dd = mysqli_fetch_array($get_folio)) {
                $Claim_Form_Number = $dd['Claim_Form_Number'];
                $Folio_Number = $dd['Folio_Number'];
            }
        }else{
            //create folio number
            include("./includes/Folio_Number_Generator_temp.php");
            $Claim_Form_Number = $_GET['Claim_Form_Number'];
        }

        //Get last Patient bill ID
        $slct_bill_id = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
        $num_Create = mysqli_num_rows($slct_bill_id);
        if($num_Create > 0){
            while ($td = mysqli_fetch_array($slct_bill_id)) {
                $Patient_Bill_ID = $td['Patient_Bill_ID'];
            }
        }else{
            //Create Patient Bill ID
            $Create_PBI = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID, Date_Time) values('$Registration_ID',(select now()))") or die(mysqli_error($conn));
            if($Create_PBI){
                $slct_bill_id = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                $num_Create = mysqli_num_rows($slct_bill_id);
                if($num_Create > 0){
                    while ($td = mysqli_fetch_array($slct_bill_id)) {
                        $Patient_Bill_ID = $td['Patient_Bill_ID'];
                    }
                }else{
                    $Patient_Bill_ID = '';
                }
            }else{
                $Patient_Bill_ID = '';
            }
        }

        //Get Check in id
        //select Check In ID
        $slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
        $slct_num = mysqli_num_rows($slct);
        if($slct_num > 0){
            while ($dt = mysqli_fetch_array($slct)) {
                $Check_In_ID = $dt['Check_In_ID'];
            }
        }else{
            //Create New Check in
	        $Create = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date,Employee_ID, 
	                                    Check_In_Date_And_Time, Check_In_Status,Branch_ID, 
	                                    Saved_Date_And_Time, Check_In_Date,Type_Of_Check_In, Folio_Status) 
	                                VALUES ('$Registration_ID',(select now()),'$Employee_ID',
	                                    (select now()),'saved','$Branch_ID',
	                                    (select now()),(select now()),'Afresh','generated')") or die(mysqli_error($conn));
	        if($Create){
	            //select Check In ID
	            $slct = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
	            $slct_num = mysqli_num_rows($slct);
	            if($slct_num > 0){
	                while ($dt = mysqli_fetch_array($slct)) {
	                    $Check_In_ID = $dt['Check_In_ID'];
	                }
	            }else{
	                $Check_In_ID = '';
	            }
	        }else{
	            $Check_In_ID = '';
	        }
        }
    }
    
    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;

    if($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0){
        //select all data from the departmental cache table
        $select = mysqli_query($conn,"select * from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and
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
            
            //Create consultation if new visit
            if($Visit_Controler == 'new' || $Visit_Type == 'CT SCAN'){
                
     
                $create_consultation = mysqli_query($conn,"insert into tbl_consultation(Employee_ID, Registration_ID, Consultation_Date_And_Time)
                                                    values('$Consultant_ID','$Registration_ID',(select now()))") or die(mysqli_error($conn));
                if($create_consultation){
                    $slct_con = mysqli_query($conn,"select consultation_ID from tbl_consultation where Registration_ID = '$Registration_ID' order by consultation_ID desc limit 1") or die(mysqli_error($conn));
                    $num_cons = mysqli_num_rows($slct_con);
                    if($num_cons > 0){
                        while ($dtcon = mysqli_fetch_array($slct_con)) {
                            $consultation_ID = $dtcon['consultation_ID'];
                        }
                    }else{
                        $consultation_ID = null;
                    }
                }else{
                    $consultation_ID = null;
                }
            }else{
                $consultation_ID = null;
            }
            
            if(is_null($consultation_ID) || empty($consultation_ID)){
                $consultation_ID=NULL;
            }
           
            //insert data to payment cache
//            if($Visit_Controler == 'continue'){
//                $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
//                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
//                                        Folio_Number, Sponsor_ID, Sponsor_Name,
//                                        Billing_Type, Receipt_Date, branch_id)
//                                            
//                                        values('$Registration_ID','$Employee_ID',(select now()),
//                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
//                                                '$Billing_Type',(select now()),'$Branch_ID')") or die(mysqli_error($conn));

//            }else{

            if ($consultation_ID == null) {
                // insert consultation id

                    $insert_consultation_id = "INSERT  INTO tbl_consultation(Registration_ID,Consultation_Date_And_Time) VALUES('$Registration_ID',NOW())";
                    $consultation_result = mysqli_query($conn,$insert_consultation_id);

                        $select_consult = mysqli_query($conn,"SELECT consultation_ID from tbl_consultation where Registration_ID = '$Registration_ID' order by consultation_ID desc limit 1") or 
                        die(mysqli_error($conn));
                            $num_consltation = mysqli_num_rows($select_consult);
                            if ($num_consltation > 0) {
                                while ($dtconsu = mysqli_fetch_array($select_consult)) {
                                    $consultation_ID = $dtconsu['consultation_ID'];
                                }
                            }
    // end consultation id
            }
                $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id, consultation_id)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID',$consultation_ID)") or die(mysqli_error($conn));
           // }

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
                    $select_details = mysqli_query($conn,"select * from tbl_departmental_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    $numrows = mysqli_num_rows($select_details);
                    if($numrows > 0){
                        while($dt = mysqli_fetch_array($select_details)){
                            $Item_ID = $dt['Item_ID'];
                            $Price = $dt['Price'];
							$Discount = $dt['Discount'];
							
                            $Quantity = $dt['Quantity'];
                            //$Consultant_ID = $Employee_ID;
                            $Comment = $dt['Comment'];
                            $Sub_Department_ID = $dt['Sub_Department_ID'];
                            $Type_Of_Check_In = $dt['Type_Of_Check_In'];
                            $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                                Check_In_Type, Item_ID,Price,Discount,
                                                Quantity, Patient_Direction, Consultant_ID,
                                                Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time
                                                ) values(
                                                    '$Type_Of_Check_In','$Item_ID','$Price','$Discount',
                                                    '$Quantity','others','$Consultant_ID',
                                                    '$Payment_Cache_ID',(select now()),
                                                    '$Comment','$Sub_Department_ID','$Transaction_Type',(select now()))");
                        }
                    }
                    
                    if($insert){

                        //insert details to tbl_patient_payments
                        $insert_details = mysqli_query($conn,"INSERT INTO tbl_patient_payments(
                                                        Registration_ID, Supervisor_ID, Employee_ID,
                                                        Payment_Date_And_Time, Folio_Number,
                                                        Claim_Form_Number, Sponsor_ID, Sponsor_Name,
                                                        Billing_Type, Receipt_Date, branch_id,Check_In_ID,Patient_Bill_ID)
                                                        
                                                        VALUES ('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                                            (select now()),'$Folio_Number',
                                                            '$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
                                                            '$Billing_Type',(select now()),'$Branch_ID','$Check_In_ID','$Patient_Bill_ID')") or die(mysqli_error($conn));


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
                                $select_details = mysqli_query($conn,"select * from tbl_departmental_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                    Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                
                                while($dt = mysqli_fetch_array($select_details)){
                                    $Item_ID = $dt['Item_ID'];
                                    $Price = $dt['Price'];
									$Discount= $dt['Discount'];
                                    $Quantity = $dt['Quantity'];
                                    $Consultant_ID = $Employee_ID;
                                    $Comment = $dt['Comment'];
                                    $Type_Of_Check_In = $dt['Type_Of_Check_In'];
                                    $insert = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(
                                                            Check_In_Type, Item_ID,Price,Discount,
                                                            Quantity, Patient_Direction, Consultant,
                                                            Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time)
                                                            
                                                            VALUES ('$Type_Of_Check_In','$Item_ID','$Price','$Discount',
                                                            '$Quantity','others','others',
                                                            '$Consultant_ID', '$Patient_Payment_ID', (select now()))") or die(mysqli_error($conn));
                                    
                                }
                                if($insert){
                                    //update tbl_item_list_cache
                                    mysqli_query($conn,"update tbl_item_list_cache set
                                                    Status = 'paid',
                                                    Patient_Payment_ID = '$Patient_Payment_ID',
                                                        Payment_Date_And_Time = '$Receipt_Date' where
                                                            Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
                                    
                                    //delete all data from cache
                                    mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                }
   
$payDetails = getPaymentsDetailsByReceiptNumber($Patient_Payment_ID);
$Product_Array = array();
$Product_Name_Array = array(
    'source_name' => 'ehms_sales_credit',
    'comment' => 'Receipt # ' . $Patient_Payment_ID . ", Date:" . $payDetails['Payment_Date_And_Time'] . ", Trans Type:" . $payDetails['Payment_Mode'] . " Amount:  " . $payDetails['TotalAmount'] . " Tsh.",
    'debit_entry_ledger' =>$Sponsor_Name,
    'credit_entry_ledger' => 'SALES',
    'sub_total' => $payDetails['TotalAmount'],
    'source_id' => $Patient_Payment_ID,
    'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
    'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
);

array_push($Product_Array, $Product_Name_Array);
$endata = json_encode($Product_Array);
$acc = gAccJournalEntry($endata);
//    echo $acc;
//    exit();
if ($acc != "success") {
    $HAS_ERROR = true;
}         
                                
                                header("Location: ./previewapprovedtransaction.php?Section=$Section&Patient_Payment_ID=$Patient_Payment_ID&Registration_ID=$Registration_ID&PreviewApprovedTransaction=PreviewApprovedTransactionThisPage");
                            }
                        }
                        mysqli_query($conn,"delete from tbl_departmental_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        header("Location: ./previewapprovedtransaction.php?Section=$Section&Patient_Payment_ID=$Patient_Payment_ID&Registration_ID=$Registration_ID&PreviewApprovedTransaction=PreviewApprovedTransactionThisPage");
                    }else{
                        header("Location: ./laboratoryothersworkspage.php?".$location."Registration_ID=$Registration_ID&LaboratoryPatientBilling=LaboratoryPatientBillingThisForm");
                    }
                }
            }
        }
    }
}

?>