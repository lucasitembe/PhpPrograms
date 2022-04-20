<?php
    @session_start();
    include("./includes/connection.php");

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
        $Supervisor_ID = $_SESSION['Pharmacy_Supervisor']['Employee_ID'];
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

    if(isset($_GET['Currency_ID'])){
        $Currency_ID = $_GET['Currency_ID'];
    }else{
        $Currency_ID = NULL;
    }

    if(empty($Folio_Number) || $Folio_Number==0){
      include("./includes/Folio_Number_Generator_Emergency.php");
      
    }


    include("./includes/Get_Patient_Check_In_Id.php");
    include("./includes/Get_Patient_Transaction_Number.php");

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
            }

            //generate transaction type

            if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
                $Transaction_Type = 'Cash';
            } else if (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit') {
                $Transaction_Type = 'Credit';
            }

            //generate payment_type
            if((strtolower($Sponsor_Name) != 'cash' && strtolower($Billing_Type) == 'inpatient cash') || (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes' && strtolower($Billing_Type) != 'inpatient credit')){
                $payment_type = 'pre';
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
            	}
            }else{
            	$consultation_id = null;
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
                                                Employee_Created, Created_Date_Time,Clinic_ID,clinic_location_id,finance_department_id) values(
                                                    'Pharmacy','$Item_ID','$Price','$Discount',
                                                    '$Quantity','others','$Consultant_ID',
                                                    'active','$Payment_Cache_ID',(select now()),
                                                    '$Dosage','$Sub_Department_ID','$Transaction_Type',(select now()),
                                                    '$Employee_ID',(select now()),'$Clinic_ID','$clinic_location_id','$working_department')") or die(mysqli_error($conn));
                    }
                    if ($insert) {
                         
                        //delete all data from cache
                        mysqli_query($conn,"delete from tbl_pharmacy_inpatient_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                        header("Location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&PharmacyWorks=PharmacyWorksThisPage");
                        //header("Location: ./Dispense_Medication.php?Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&Registration_ID=$Registration_ID");
                    }
                }
            }
        }
    }
?>