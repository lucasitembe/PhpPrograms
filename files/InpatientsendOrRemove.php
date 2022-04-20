<?php

session_start();
include("./includes/connection.php");
require_once './includes/ehms.function.inc.php';

$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

//select the current Patient_Payment_ID to use as a foreign key
$Registration_ID = $_GET['Registration_ID'];
$qr = "SELECT * from tbl_patient_payments pp
					    where pp.registration_id = '$Registration_ID' ORDER BY pp.Patient_Payment_ID DESC LIMIT 1";

$consultation_ID = $_GET['consultation_ID'];
$Admision_ID = $_GET['Admision_ID'];

$Check_In_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Check_In_ID FROM tbl_check_in_details WHERE Admission_ID = '$Admision_ID'"))['Check_In_ID'];

$sql_Select_Current_Patient = mysqli_query($conn,$qr) or die(mysqli_error($conn));
$row = mysqli_fetch_array($sql_Select_Current_Patient);
$Patient_Payment_ID = $row['Patient_Payment_ID'];
$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
$Folio_Number = $row['Folio_Number'];
$Claim_Form_Number = $row['Claim_Form_Number'];
// $Billing_Type = $row['Billing_Type'];


// echo $Folio_Number;exit;


$inserted = TRUE;
// $bill_type = $_GET['bill_type'];
$action = $_GET['action'];
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Registration_ID = $_GET['Registration_ID'];
$Round_ID = $_GET['Round_ID'];
$Guarantor_Name = $_GET['Guarantor_Name'];
$Sponsor_ID = $_GET['Sponsor_ID'];
$branch_id = $_SESSION['userinfo']['Branch_ID'];
$Item_ID = $_GET['Item_ID'];
$Consultation_Type = $_GET['Consultation_Type'];
$Check_In_Type = $Consultation_Type;
$must_pay = $_GET['must_pay'];
$Payment_Date_And_Time = '(SELECT NOW())';
$Receipt_Date = Date('Y-m-d');
$Transaction_status = 'pending';
$Transaction_type = 'indirect cash';
$Sponsor_Name = $Guarantor_Name;

if($Sponsor_ID == 0 || $Sponsor_ID == ''){
    $Sponsor_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name = '$Guarantor_Name'"))['Sponsor_ID'];
}

$bill_type = '';
$Billing_Type = '';

$sql_select_payment_method_result=mysqli_query($conn,"SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_payment_method_result)>0){
    $payment_method=mysqli_fetch_assoc($sql_select_payment_method_result)['payment_method']; 

    $Billing_Type = "Inpatient ".ucwords($payment_method);
    $bill_type = ucwords($payment_method);


    // echo $Billing_Type."+".$bill_type;
    // exit();


    $payment_type = 'post';
    if ($must_pay == 'yes') {
        $payment_type = 'pre';
    }if ((strtolower($Sponsor_Name) != 'cash' && strtolower(getPaymentMethod($Sponsor_ID)) !== 'cash') && strtolower($bill_type) == 'cash') {
        $payment_type = 'pre';
    }if (strtolower($bill_type) == 'cash') {
        $payment_type = 'pre';
    }
}

$payment_cache_ID = 0;
// die("SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Round_ID = '$Round_ID' AND Billing_Type='$Billing_Type' AND Sponsor_ID = '$Sponsor_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1");
//checking if payment_cache_ID is available for this consultation..
$select_payment_cache_ID = "SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Round_ID = '$Round_ID' AND Billing_Type='$Billing_Type' AND Sponsor_ID = '$Sponsor_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1";
$cache_result = mysqli_query($conn,$select_payment_cache_ID);

if (mysqli_num_rows($cache_result) > 0) {
    $payment_cache_ID = mysqli_fetch_assoc($cache_result)['Payment_Cache_ID'];
}

if ($action == 'ADD') {
    if ($payment_cache_ID > 0) {
        //paymentcache already exists, so add item
        // echo ($Billing_Type);exit();
    } else {
        // echo ($Billing_Type);exit();

        //paymentcache is not set


        $insert_query = "INSERT INTO tbl_payment_cache(Registration_ID, Employee_ID,consultation_id, Round_ID, Payment_Date_And_Time,
			Folio_Number, Sponsor_ID, Sponsor_Name, Billing_Type, Receipt_Date, Transaction_status, Transaction_type, branch_id, Check_In_ID)
			VALUES ('$Registration_ID', '$Employee_ID','$consultation_ID', '$Round_ID', $Payment_Date_And_Time,
			'$Folio_Number', '$Sponsor_ID', '$Sponsor_Name', '$Billing_Type', '$Receipt_Date',
			'$Transaction_status', '$Transaction_type','$branch_id', '$Check_In_ID')";

        if (!mysqli_query($conn,$insert_query)) {
            die(mysqli_error($conn));
            exit;
            $inserted = FALSE;
        }
        $payment_cache_ID = mysqli_insert_id($conn);
    }
    if ($inserted) {

        $Select_Price = "SELECT Items_Price as price from tbl_item_price
                                    where Item_ID = '$Item_ID' AND Sponsor_ID = '$Sponsor_ID'";
        $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

        if (mysqli_num_rows($itemSpecResult) > 0) {
            $rowpr = mysqli_fetch_assoc($itemSpecResult);
            $Price = $rowpr['price'];

            $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID='$Item_ID'";
            $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));

            if (mysqli_num_rows($check_if_covered2) > 0) {
                
            } else {

                if ($Price == 0) {
                    $Select_Price = "SELECT Price from tbl_general_item_price
                                    where Item_ID = '$Item_ID'";
                    $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                    if (mysqli_num_rows($itemGenResult) > 0) {
                        $row = mysqli_fetch_assoc($itemGenResult);
                        $Price = $row['price'];
                    } else {
                        $Price = 0;
                    }
                }
            }
        }

        $Sub_Department_ID = $_GET['Sub_Department_ID'];

        if ($Sub_Department_ID == 'undefined') {
            $Sub_Department_ID = 'NULL';
        }

        $Quantity = $_GET['quantity'];
        $Patient_Direction = "others";
        $Consultant = $_SESSION['userinfo']['Employee_Name'];
        $Consultant_ID = $_SESSION['userinfo']['Employee_ID'];
        $Status = 'notsaved';
        $Transaction_Date_And_Time = '(SELECT NOW())';
        $Process_Status = 'inactive';
        $Doctor_Comment = $_GET['comments'];
        $Transaction_Type = $bill_type;
        $Service_Date_And_Time = $_GET['Service_Date_And_Time'];
        $Priority = $_GET['Priority'];
        $Discount = $_GET['Discount'];
        $Procedure_Location = $_GET['Procedure_Location'];
        $service_hour = (isset($_GET['service_hour'])) ? $_GET['service_hour'] : null;
        $service_min = (isset($_GET['service_min'])) ? $_GET['service_min'] : null;
        $doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
        $finance_department_id=$_SESSION['finance_department_id'];

        if($Price > 0){
            if($Check_In_Type != 'Pharmacy' && $Check_In_Type != 'others'){
                for($i=0; $i<$Quantity; $i++){
                    $Quantitys=1;
                    $insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,
                        Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,Transaction_Type,payment_type,Service_Date_And_Time,Priority,Surgery_hour,Surgery_min,Procedure_Location,Clinic_ID,finance_department_id)
                        VALUES ('$Check_In_Type', '$Item_ID', $Discount, $Price, '$Quantitys', '$Patient_Direction', '$Consultant', '$Consultant_ID',
                        '$Status','$payment_cache_ID', $Transaction_Date_And_Time,
                        '$Process_Status', '$Doctor_Comment',$Sub_Department_ID,'$Transaction_Type','$payment_type','$Service_Date_And_Time','$Priority','$service_hour','$service_min','$Procedure_Location','$doctors_selected_clinic','$finance_department_id')";
                    if (!mysqli_query($conn,$insert_query2)) {
                        die(mysqli_error($conn));
                        exit;
                    } else {
                        $Payment_Item_Cache_List_ID = mysqli_insert_id($conn);
                        if(($Priority == 'Urgent' || $Priority == 'urgent') && $Check_In_Type == 'Surgery'){

                            $Insert = mysqli_query($conn, "INSERT INTO tbl_surgery_appointment(Payment_Item_Cache_List_ID, Final_Decision, Surgery_Status, Date_Time, Employee_ID, Remarks) VALUES('$Payment_Item_Cache_List_ID', 'Accepted', 'Active', NOW(), '$Consultant_ID', NULL)") or die(mysqli_error($conn));
                        }
                        echo "added";
                    }
                }
            }else{
                $insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,
                Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,Transaction_Type,payment_type,Service_Date_And_Time,Priority,Surgery_hour,Surgery_min,Procedure_Location,Clinic_ID,finance_department_id)
                VALUES ('$Check_In_Type', '$Item_ID', $Discount, $Price, '$Quantity', '$Patient_Direction', '$Consultant', '$Consultant_ID',
                '$Status','$payment_cache_ID', $Transaction_Date_And_Time,
                '$Process_Status', '$Doctor_Comment',$Sub_Department_ID,'$Transaction_Type','$payment_type','$Service_Date_And_Time','$Priority','$service_hour','$service_min','$Procedure_Location','$doctors_selected_clinic','$finance_department_id')";
                if (!mysqli_query($conn,$insert_query2)) {
                    die(mysqli_error($conn));
                    exit;
                } else {
                    echo "added";
                }
            }
        }else{
            echo "failed";

        }
    }
} else {
    //Remove Item Here
//	$Consultant_ID = $_SESSION['userinfo']['Employee_ID'];
//	$delete_qr = "DELETE FROM tbl_item_list_cache WHERE Item_ID = $Item_ID AND Consultant_ID=$Consultant_ID AND payment_cache_ID=$payment_cache_ID";
//	if(!mysqli_query($conn,$delete_qr)){
//	    die(mysqli_error($conn));exit;
//	}else{
//	    echo "removed";
//	}
}

mysqli_close($conn);
?>