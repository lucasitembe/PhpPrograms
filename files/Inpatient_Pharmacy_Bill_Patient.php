<?php
include("./includes/connection.php");
session_start();
if(isset($_GET['post_payment']) && $_GET['post_payment']=='true'){
    $post_payment = isset($_GET['post_payment']);
} else {
    $post_payment = 'false';
}

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

//get supervisor id
if (isset($_SESSION['Pharmacy_Supervisor'])) {
    $Supervisor_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Supervisor_ID = 0;
}

//get registration id
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

//get claim form number
if (isset($_GET['Claim_Form_Number'])) {
    $Claim_Form_Number = $_GET['Claim_Form_Number'];
} else {
    $Claim_Form_Number = 0;
}

//get Consultant_ID
if (isset($_GET['Consultant_ID'])) {
    $Consultant_ID = $_GET['Consultant_ID'];
} else {
    $Consultant_ID = 0;
}
if (isset($_GET['from_billing_work'])) {
    $from_billing_work = $_GET['from_billing_work'];
} else {
    $from_billing_work = 0;
}

//get Folio Number
if (isset($_GET['Folio_Number'])) {
    $Folio_Number = $_GET['Folio_Number'];
} else {
    $Folio_Number = 0;
}

if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}

if(empty($Folio_Number) || $Folio_Number==0){
  include("./includes/Folio_Number_Generator_Emergency.php");
  
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



//include("./includes/Get_Patient_Check_In_Id.php");
include("./includes/Get_Patient_Transaction_Number.php");
////get the check_in_id of patient (ad.Admission_Status = 'pending' or ad.Admission_Status = 'Admitted') and
$sql_select_check_id_result=mysqli_query($conn,"select ad.Admission_Status,cd.Check_In_ID
                                from tbl_admission ad, tbl_check_in_details cd where
                                cd.Admission_ID = ad.Admision_ID and
                                ad.Registration_ID=cd.Registration_ID and
                                cd.Registration_ID='$Registration_ID' and
                                (ad.Admission_Status = 'pending' or ad.Admission_Status = 'Admitted') and
                                ad.Discharge_Clearance_Status = 'not cleared' ORDER BY cd.Check_In_ID DESC limit 1") or die(mysqli_error($conn));

if(mysqli_num_rows($sql_select_check_id_result)>0){
    $Check_In_ID=mysqli_fetch_assoc($sql_select_check_id_result)['Check_In_ID'];
}else{
    $sql_select_check_id_result2=mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_check_id_result2)>0){
      $Check_In_ID=mysqli_fetch_assoc($sql_select_check_id_result2)['Check_In_ID'];  
    }
    
}

if ($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0 && $Sub_Department_ID != null && $Sub_Department_ID != 0) {
    //select all data from the pharmacy cache table
    $select = mysqli_query($conn,"select * from tbl_pharmacy_inpatient_items_list_cache where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
    $num_rows = mysqli_num_rows($select);
    if ($num_rows > 0) {
        while ($data = mysqli_fetch_array($select)) {
            //$Folio_Number = $data['Folio_Number'];
            $Sponsor_ID = $data['Sponsor_ID'];
            $Sponsor_Name = $data['Sponsor_Name'];
            $Billing_Type = $data['Billing_Type'];
            //$Claim_Form_Number = $data['Claim_Form_Number'];
            $Sponsor_Name = $data['Sponsor_Name'];
            $Fast_Track = $data['Fast_Track'];
            $working_department = $dt['working_department'];
            $clinic_location_id = $dt['clinic_location_id'];
        }

        //generate transaction type

        if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
            $Transaction_Type = 'Cash';
        } else if (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit') {
            $Transaction_Type = 'Credit';
        }

        //generate payment_type
        if((strtolower($Sponsor_Name) != 'cash' && strtolower($Billing_Type) == 'inpatient cash') || (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes' && strtolower($Billing_Type) != 'inpatient credit')){
            if(strtolower($Sponsor_Name) == 'cash' && strtolower($Billing_Type) == 'inpatient cash' && $post_payment=='true'){
                $payment_type = 'post';
            } else {
                //$payment_type = 'pre';
                $payment_type = 'post';
            }
            
        }else{
            $payment_type = 'post';
        }

        //get current consultation_id
        $slct = mysqli_query($conn,"select consultation_id from tbl_check_in_details where 
        						Registration_ID = '$Registration_ID' and
        						Admit_Status = 'admitted'
        						order by Check_In_Details_ID desc limit 1") or die(mysqli_error($conn));
        $no_slct = mysqli_num_rows($slct);
        if($no_slct > 0){
        	while ($dtz = mysqli_fetch_assoc($slct)) {
                    $consultation_id = $dtz['consultation_id'];
        if($consultation_id==""||$consultation_id==NULL){
                        $sql_select_consultation_id_result=mysqli_query($conn,"SELECT consultation_id FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_id DESC LIMIT 1") or die(mysqli_error($conn));
                
                            if(mysqli_num_rows($sql_select_consultation_id_result)>0){
                                $consultation_id=mysqli_fetch_assoc($sql_select_consultation_id_result)['consultation_id'];
                            }else{
                        $sql_create_consultation_id_result=mysqli_query($conn,"INSERT INTO tbl_consultation(employee_ID,Registration_ID,Consultation_Date_And_Time,Clinic_ID,working_department,clinic_location_id) VALUES('$Employee_ID','$Registration_ID',(select now()),'$Clinic_ID','$working_department','$clinic_location_id')") or die(mysqli_error($conn));

                        if(mysqli_num_rows($sql_create_consultation_id_result)>0){
                            $sql_select_consultation_id_result=mysqli_query($conn,"SELECT consultation_id FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_id DESC LIMIT 1") or die(mysqli_error($conn));

                            if(mysqli_num_rows($sql_select_consultation_id_result)>0){
                                $consultation_id=mysqli_fetch_assoc($sql_select_consultation_id_result)['consultation_id'];
                            }
                        }
                         }
                    }  
        	}
        }else{
        	
             $sql_select_consultation_id_result=mysqli_query($conn,"SELECT consultation_id FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_id DESC LIMIT 1") or die(mysqli_error($conn));
                
                    if(mysqli_num_rows($sql_select_consultation_id_result)>0){
                        $consultation_id=mysqli_fetch_assoc($sql_select_consultation_id_result)['consultation_id'];
                    }else{
                $sql_create_consultation_id_result=mysqli_query($conn,"INSERT INTO tbl_consultation(employee_ID,Registration_ID,Consultation_Date_And_Time,Clinic_ID) VALUES('$Employee_ID','$Registration_ID',(select now()),'$Clinic_ID')") or die(mysqli_error($conn));
        
                if(mysqli_num_rows($sql_create_consultation_id_result)>0){
                    $sql_select_consultation_id_result=mysqli_query($conn,"SELECT consultation_id FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_id DESC LIMIT 1") or die(mysqli_error($conn));
                
                    if(mysqli_num_rows($sql_select_consultation_id_result)>0){
                        $consultation_id=mysqli_fetch_assoc($sql_select_consultation_id_result)['consultation_id'];
                    }
                }
        }
        
        }
        
        //insert data to payment cache
        $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id,consultation_id,Fast_Track)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID','$consultation_id','$Fast_Track')") or die(mysqli_error($conn));
        if ($insert_data) {
            //get the last Payment_Cache_ID (foreign key)
            $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if ($no > 0) {
                while ($row = mysqli_fetch_array($select)) {
                    $Payment_Cache_ID = $row['Payment_Cache_ID'];
                }
                //insert data
                $select_details = mysqli_query($conn,"select * from tbl_pharmacy_inpatient_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                while ($dt = mysqli_fetch_array($select_details)) {
                    $Item_ID = $dt['Item_ID'];
                    $Price = $dt['Price'];
                    $Discount = $dt['Discount'];
                    $Quantity = $dt['Quantity'];
                    $Consultant_ID = $dt['Consultant_ID'];
                    $Dosage = $dt['Dosage'];
                    $Clinic_ID = $dt['Clinic_ID'];
                    $working_department = $dt['working_department'];
                    $clinic_location_id = $dt['clinic_location_id'];
                    $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                            Check_In_Type, Item_ID,Price,Discount,
                                            Quantity, Patient_Direction, Consultant_ID,
                                            Status, Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                            Sub_Department_ID, Transaction_Type, Service_Date_And_Time,
                                            Employee_Created, Created_Date_Time,Clinic_ID,finance_department_id,clinic_location_id) values(
                                                'Pharmacy','$Item_ID','$Price','$Discount',
                                                '$Quantity','others','$Consultant_ID',
                                                'paid','$Payment_Cache_ID',(select now()),
                                                '$Dosage','$Sub_Department_ID','$Transaction_Type',(select now()),
                                                '$Employee_ID',(select now()),'$Clinic_ID','$working_department','$clinic_location_id')") or die(mysqli_error($conn));
                }

                if ($insert) {
                    $has_no_folio = false;
                    if (empty($Folio_Number) || $Folio_Number == 0) {
                        $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
                        $rows_info = mysqli_fetch_array($select);

                        $Claim_Form_Number = $rows_info['Claim_Form_Number'];

//                        if (strtolower($Sponsor_Name) == 'cash') {
//                            $Billing_Type = "Inpatient Cash";
//                        } else {
//                            $Billing_Type = "Inpatient Credit";
//                        }

                        $has_no_folio = true;
                    }
                    //insert details to tbl_patient_payments
                    $insert_details = mysqli_query($conn,"INSERT INTO tbl_patient_payments(
                                                        Registration_ID, Supervisor_ID, Employee_ID,
                                                        Payment_Date_And_Time, Folio_Number,
                                                        Claim_Form_Number, Sponsor_ID, Sponsor_Name,
                                                        Billing_Type, Receipt_Date, branch_id,Check_In_ID,Patient_Bill_ID,payment_type,Fast_Track,terminal_id,auth_code,manual_offline)
                                                        
                                                        VALUES ('$Registration_ID','$Consultant_ID','$Employee_ID',
                                                            (select now()),'$Folio_Number',
                                                            '$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
                                                            '$Billing_Type',(select now()),'$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$payment_type','$Fast_Track','$terminal_id','$auth_code','$manual_offline')") or die(mysqli_error($conn));
                    if ($insert_details) {
                        //get the last Patient Payment ID
                        $select = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date from tbl_patient_payments
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if ($num > 0) {
                            while ($row = mysqli_fetch_array($select)) {
                                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                                $Receipt_Date = $row['Receipt_Date'];
                            }

                            //insert data to tbl_patient_payment_item_list
                            $select_details = mysqli_query($conn,"select * from tbl_pharmacy_inpatient_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));

                            while ($dt = mysqli_fetch_array($select_details)) {
                                $Item_ID = $dt['Item_ID'];
                                $Price = $dt['Price'];
                                $Discount = $dt['Discount'];
                                $Quantity = $dt['Quantity'];
                                $Consultant_ID = $dt['Consultant_ID'];
                                $Dosage = $dt['Dosage'];
                                $Clinic_ID = $dt['Clinic_ID'];
                                $working_department = $dt['working_department'];
                                $clinic_location_id = $dt['clinic_location_id'];
                                
                                $sql_select_consultant_name_result=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_consultant_name_result)){
                                    $Employee_Name=mysqli_fetch_assoc($sql_select_consultant_name_result)['Employee_Name'];
                                }else{
                                   $Employee_Name=""; 
                                }
                                $insert = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(
                                                            Check_In_Type, Item_ID, Price, Discount,
                                                            Quantity, Patient_Direction, Consultant,
                                                            Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,Sub_Department_ID,finance_department_id,clinic_location_id)
                                                            
                                                            VALUES ('Pharmacy','$Item_ID','$Price','$Discount',
                                                            '$Quantity','others','$Employee_Name',
                                                            '$Consultant_ID', '$Patient_Payment_ID', (select now()),'$Clinic_ID','$Sub_Department_ID','$working_department','$clinic_location_id')") or die(mysqli_error($conn));
                            }
                            if ($insert) {
                                //update tbl_item_list_cache
                                mysqli_query($conn,"update tbl_item_list_cache set
                                                    Patient_Payment_ID = '$Patient_Payment_ID',
                                                        Payment_Date_And_Time = '$Receipt_Date' where
                                                            Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));

                                //check if this user has folio 

                                if ($has_no_folio) {
                                    $result_cd = mysqli_query($conn,"SELECT Check_In_Details_ID FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND consultation_ID IS NOT NULL ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));
                                    $Check_In_Details_ID = mysqli_fetch_assoc($result_cd)['Check_In_Details_ID'];
                                    $update_checkin_details = "UPDATE tbl_check_in_details SET Folio_Number='$Folio_Number'
                                                    WHERE Check_In_Details_ID='$Check_In_Details_ID' ";
                                    mysqli_query($conn,$update_checkin_details) or die(mysqli_error($conn));
                                }

                                //delete all data from cache
                                mysqli_query($conn,"delete from tbl_pharmacy_inpatient_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                //header("Location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&PharmacyWorks=PharmacyWorksThisPage");
                                header("Location: ./Dispense_Medication.php?Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&Registration_ID=$Registration_ID&Check_In_Type=Pharmacy&from_billing_work=$from_billing_work&Check_In_ID=$Check_In_ID");
                            }
                        }
                    }
                }
            }
        }
    }
}
?>