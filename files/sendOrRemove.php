<?php

session_start();
include("./includes/connection.php");
if (isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])) {
    //select the current Patient_Payment_ID to use as a foreign key

    $Registration_ID = $_GET['Registration_ID'];
    $qr = "SELECT * from tbl_patient_payments pp     where pp.Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . "   and pp.registration_id = '$Registration_ID'";

    $sql_Select_Current_Patient = mysqli_query($conn,$qr);
    $row = mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    $Check_In_ID = $row['Check_In_ID'];
    $Folio_Number = $row['Folio_Number'];
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Billing_Type = $row['Billing_Type'];
    //$Patient_Direction = $row['Patient_Direction'];
    //$Consultant = $row['Consultant'];
} else {
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    $Check_In_ID = $row['Check_In_ID'];
    $Folio_Number = '';
    $Claim_Form_Number = '';
    $Billing_Type = '';
    //$Patient_Direction = '';
    //$Consultant ='';
}

// START @mfoy dn

$reason = $_GET['reason'];
$reason_id = $_GET['reason_id'];

// END @mfoy dn



    $inserted = TRUE;
    $bill_type = $_GET['bill_type'];
    $action = $_GET['action'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Registration_ID = $_GET['Registration_ID'];
    $consultation_id = $_GET['consultation_id'];
    $Guarantor_Name = $_GET['Guarantor_Name'];
    $Sponsor_ID = $_GET['Sponsor_ID'];
    $branch_id = $_SESSION['userinfo']['Branch_ID'];
    $Item_ID = $_GET['Item_ID'];
    $Consultation_Type = $_GET['Consultation_Type'];
    $Check_In_Type = $Consultation_Type;

    $Payment_Date_And_Time = '(SELECT NOW())';
    $Receipt_Date = Date('Y-m-d');
    $Transaction_status = 'pending';
    $Transaction_type = 'indirect cash';
    $Sponsor_Name = $Guarantor_Name;

//echo $consultation_id;exit;

if ($_GET['bill_type'] == 'Cash') {
    $Billing_Type = 'Outpatient Cash';
} else if ($_GET['bill_type'] == 'Credit') {
    $Billing_Type = 'Outpatient Credit';
}

    //overide bill type 
    //select payment method
    $sql_select_payment_method_result=mysqli_query($conn,"SELECT payment_method, Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_payment_method_result)>0){
        while($rw = mysqli_fetch_assoc($sql_select_payment_method_result)){
            $payment_method=$rw['payment_method']; 
            $Sponsor_Name = $rw['Guarantor_Name'];
            if (strtolower($payment_method) == 'cash') {
                $Billing_Type = 'Outpatient Cash';
                $bill_type="Cash";
            } else if ($_GET['bill_type'] == 'Credit') {
                $Billing_Type = 'Outpatient Credit';
                $bill_type="Credit";
            }
        }
       
       
    }
    $payment_cache_ID = 0;
    //checking if payment_cache_ID is available for this consultation..
    $select_payment_cache_ID = "SELECT payment_cache_ID FROM tbl_payment_cache WHERE consultation_id = $consultation_id AND Billing_Type='$Billing_Type' AND Check_In_ID='$Check_In_ID' LIMIT 1";
    $cache_result = mysqli_query($conn,$select_payment_cache_ID);

    if (mysqli_num_rows($cache_result) > 0) {
        $payment_cache_ID = mysqli_fetch_assoc($cache_result)['payment_cache_ID'];
    }else{

    }
if ($action == 'ADD') {
    if ($payment_cache_ID > 0) {
        //paymentcache already exists, so add item
    } else {

        //paymentcache is not set
        $insert_query = "INSERT INTO tbl_payment_cache(Registration_ID, Employee_ID, consultation_id,Check_In_ID, Payment_Date_And_Time,   Folio_Number, Sponsor_ID, Sponsor_Name, Billing_Type, Receipt_Date, Transaction_status, Transaction_type, branch_id)    VALUES ('$Registration_ID', '$Employee_ID', $consultation_id,'$Check_In_ID', $Payment_Date_And_Time,    '$Folio_Number', '$Sponsor_ID', '$Sponsor_Name', '$Billing_Type', '$Receipt_Date',
        '$Transaction_status', '$Transaction_type','$branch_id')";

        if (!mysqli_query($conn,$insert_query)) {
            die(mysqli_error($conn));
            exit;
            $inserted = FALSE;
        }
        $payment_cache_ID = mysqli_insert_id($conn);
    }

    $Price = '';

    if ($inserted) {

        $Select_Price = "SELECT Items_Price as price from tbl_item_price ip     where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
        $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

        if (mysqli_num_rows($itemSpecResult) > 0) {

            $row = mysqli_fetch_assoc($itemSpecResult);
            $Price = $row['price'];

            $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Item_ID . "";
            $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));

            if (mysqli_num_rows($check_if_covered2) > 0) {
                
            } else {

                if ($Price == 0) {
                    $Select_Price = "SELECT Items_Price as price from tbl_general_item_price ig   where ig.Item_ID = '$Item_ID'";
                    $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                    if (mysqli_num_rows($itemGenResult) > 0) {
                        $row = mysqli_fetch_assoc($itemGenResult);
                        $Price = $row['price'];
                    } else {
                        $Price = 001;
                    }
                }
            }
            // echo $Select_Price;
        } else {
            $Select_Price = "SELECT Items_Price as price from tbl_general_item_price ig     where ig.Item_ID = '$Item_ID'";
            $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

            if (mysqli_num_rows($itemGenResult) > 0) {

                $row = mysqli_fetch_assoc($itemGenResult);
                $Price = $row['price'];
                //die($Billing_Type." == Sponsor_ID=".$Sponsor_ID.' Price='.$Price);
            } else {
                $Price = 001;
            }
            //echo $Select_Price;
        }

        $Sub_Department_ID = $_GET['Sub_Department_ID'];

        if ($Sub_Department_ID == 'undefined') {
            $Sub_Department_ID = 'NULL';
        }

        $Quantity = $_GET['quantity'];
        $Patient_Direction = "others";
        $Consultant = $_SESSION['userinfo']['Employee_Name'];
        $Consultant=mysqli_real_escape_string($conn,$Consultant);
        $Consultant_ID = $_SESSION['userinfo']['Employee_ID'];
        $Status = 'active';
        $Transaction_Date_And_Time = '(SELECT NOW())';
        $Process_Status = 'inactive';
        $Doctor_Comment =mysqli_real_escape_string($conn,$_GET['comments']);
        $Transaction_Type = $bill_type;
        $Service_Date_And_Time = $_GET['Service_Date_And_Time'];
        $Priority = $_GET['Priority'];
        $Discount = $_GET['Discount'];
        $Procedure_Location = $_GET['Procedure_Location'];
        $service_hour = (isset($_GET['service_hour'])) ? $_GET['service_hour'] : null;
        $service_min = (isset($_GET['service_min'])) ? $_GET['service_min'] : null;
        $doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
        $finance_department_id=$_SESSION['finance_department_id'];

        if($Check_In_Type != 'Pharmacy' && $Check_In_Type != 'others'){
            for($i=0; $i<$Quantity; $i++){
                $Quantitys=1;
                $insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,    Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,Transaction_Type,Service_Date_And_Time,Priority,Surgery_hour,Surgery_min,Procedure_Location,Clinic_ID,finance_department_id,reason_id,reason)    VALUES ('$Check_In_Type', '$Item_ID', $Discount, $Price, '$Quantitys', '$Patient_Direction', '$Consultant', '$Consultant_ID', '$Status','$payment_cache_ID', $Transaction_Date_And_Time, '$Process_Status', '$Doctor_Comment',$Sub_Department_ID,'$Transaction_Type','$Service_Date_And_Time','$Priority','$service_hour','$service_min','$Procedure_Location','$doctors_selected_clinic','$finance_department_id','$reason_id','$reason')";
                if (!mysqli_query($conn,$insert_query2)) {
                    die(mysqli_error($conn));
                    exit;
                } else {
                    if(($Priority == 'Urgent' || $Priority == 'urgent') && $Check_In_Type == 'Surgery'){

                        $Insert = mysqli_query($conn, "INSERT INTO tbl_surgery_appointment(Payment_Item_Cache_List_ID, Final_Decision, Surgery_Status, Date_Time, Employee_ID, Remarks) VALUES('$Payment_Item_Cache_List_ID', 'Accepted', 'Active', NOW(), '$Consultant_ID', NULL)") or die(mysqli_error($conn));
                    }
                    echo "added";
                }
            }
        }else{
       
            $insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,    Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,Transaction_Type,Service_Date_And_Time,Priority,Surgery_hour,Surgery_min,Procedure_Location,Clinic_ID,finance_department_id,reason_id,reason)    VALUES ('$Check_In_Type', '$Item_ID', $Discount, $Price, '$Quantity', '$Patient_Direction', '$Consultant', '$Consultant_ID', '$Status','$payment_cache_ID', $Transaction_Date_And_Time, '$Process_Status', '$Doctor_Comment',$Sub_Department_ID,'$Transaction_Type','$Service_Date_And_Time','$Priority','$service_hour','$service_min','$Procedure_Location','$doctors_selected_clinic','$finance_department_id','$reason_id','$reason')";
            if (!mysqli_query($conn,$insert_query2)) {
                die(mysqli_error($conn));
                exit;
            } else {
                echo "added";
            }
         }
        // die($insert_query2); 
       
    } else {
        die("Not inserted");
    }
} else {
    //Remove Item Here
    $Consultant_ID = $_SESSION['userinfo']['Employee_ID'];
    $delete_qr = "DELETE FROM tbl_item_list_cache WHERE Item_ID = $Item_ID AND Consultant_ID=$Consultant_ID AND payment_cache_ID=$payment_cache_ID";
    if (!mysqli_query($conn,$delete_qr)) {
        die(mysqli_error($conn));
        exit;
    } else {
        echo "removed";
    }
}
?>