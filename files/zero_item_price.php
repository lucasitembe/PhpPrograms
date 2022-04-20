<?php
session_start();
include("./includes/connection.php");
require_once './functions/functions.php';
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
include('audittrail.php');
    // $MAC = exec('getmac');   
    // // Storing 'getmac' value in $MAC 
    // $MAC = strtok($MAC, ' ');
$MAc_Address=get_mac_address();
if (isset($_POST['action']) && $_POST['action'] == 'zeroprice') {
    $wherPP = '';
    if (isset($_POST['dataInfos'])) {
        $ppils = explode('^$*^%$', $_POST['dataInfos']);
        $wherPP = "Patient_Payment_Item_List_ID IN (" . implode(',', $ppils) . ")";
    } else {
        die('Error');
    }

    $audit_zeroprice = mysqli_query($conn, "INSERT INTO tbl_zeroprice_audit(EMP_ID_ZERO, Zero_date, MAc_Address )VALUES('$Employee_ID',NOW(), '$MAc_Address' ) ") or die(mysqli_error($conn));
    if($audit_zeroprice){
        $zero_price_ID = mysqli_insert_id();
        $zero_qr = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list ppl SET Price = 0, EMP_ID_ZERO='$zero_price_ID' WHERE $wherPP ") or die(mysqli_error($conn));
        if ($zero_qr) {
            echo "1";
        } else {
            echo "failed to zero price ";
        }
    }
    
} elseif (isset($_POST['action']) && $_POST['action'] == 'unzeroprice') {
    $ppil=$_POST['ppil'];
    
    $query_pr=  mysqli_query($conn,"SELECT Item_ID,Billing_Type,Guarantor_Name FROM tbl_patient_payment_item_list ppil JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID=ppil.Patient_Payment_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID  WHERE Patient_Payment_Item_List_ID='$ppil'") or die(mysqli_error($conn));
    $getD=  mysqli_fetch_array($query_pr);
    $Item_ID = $getD['Item_ID'];
    $Billing_Type = $getD['Billing_Type'];
    $Guarantor_Name = $getD['Guarantor_Name'];
    
    $price=get_Item_Price($Item_ID,$Billing_Type,$Guarantor_Name);
    
    $Unzero_qr = "UPDATE tbl_patient_payment_item_list ppl SET Price = '$price' WHERE Patient_Payment_Item_List_ID='$ppil' ";

    if (mysqli_query($conn,$Unzero_qr)) {
        echo "1";
    } else {
        die(mysqli_error($conn));
    }
}
?>