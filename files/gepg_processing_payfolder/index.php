<?php
require"../includes/connection.php";
include_once 'bill/Bill.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Africa/Dar_es_Salaam");
$bill=new Bill();
if(isset($_POST['grand_total'])){
   $grand_total=$_POST['grand_total'];
}
if(isset($_POST['Payment_Cache_ID'])){
   $Payment_Cache_ID=$_POST['Payment_Cache_ID'];
}
if(isset($_POST['Registration_ID'])){
   $Registration_ID=$_POST['Registration_ID'];
}
 if(isset($_POST['Employee_id'])){
    $Employee_id=$_POST['Employee_id'];
 }
 if(isset($_POST['patient_phone_number'])){
    $Phone_Number=mysqli_real_escape_string($conn,$_POST['patient_phone_number']);
 }
if(isset($_POST['selected_Payment_Item_Cache_List_ID'])){
   $selected_Payment_Item_Cache_List_ID=$_POST['selected_Payment_Item_Cache_List_ID'];
}

//select customer detail
$sql_select_customer_detail_result=mysqli_query($conn,"SELECT Patient_Name,Phone_Number,Email_Address FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_customer_detail_result)>0){
    while($patient_info_rows=mysqli_fetch_assoc($sql_select_customer_detail_result)){
        $Patient_Name=$patient_info_rows['Patient_Name'];
        //$Phone_Number=$patient_info_rows['Phone_Number'];
        $Email_Address=$patient_info_rows['Email_Address'];
    }
}
if($Phone_Number==""){
    $Phone_Number=0;
}else{
   $Phone_Number=(int)$Phone_Number; 
}
if($Email_Address==""){
    $Email_Address="ehmsemail@email.com";
}
session_start();
$Employee_Name=$_SESSION['userinfo']['Employee_ID'];
$expire_date=date("y-m-d");
$formRequest=[];
$formRequest['BillId']=0;
$formRequest['BillDescription']='Bill Desc';
$formRequest['BilledAmount']="$grand_total";
$date_to = strtotime(date("Y-m-d"));
$formRequest['BillExpiryDate']=date("Y-m-d", (86400*3 + $date_to)).'T00:00:00';
//$formRequest['BillGeneratedDate']=date_default_timezone_get();
$formRequest['BillGeneratedDate']=str_replace('EA','',date("Y-m-d h:i:s"));
$formRequest['CustomerPayerName']="$Patient_Name";
$formRequest['CustomerEmailAddress']="$Email_Address";
//$formRequest['CustomerPhoneNumber']='255763143290';
$formRequest['CustomerPhoneNumber']="255$Phone_Number";
$formRequest['BillPayOption']=1;
$formRequest['ServiceProviderCode']='SP186';
$formRequest['SubServiceProviderCode']='1002';
$formRequest['GeneratedBy']="$Employee_Name";
//for currencyCode TZS
$formRequest['ExchangeRateAmount']=1.0;

$formRequest['BillEquivalentAmount']=$formRequest['BilledAmount']*$formRequest['ExchangeRateAmount'];
$formRequest['BillControlNumber']='0';

$formRequest['CurrencyCode']='TZS';
$formRequest['CollectionCenterCode']='HQ';

$formRequest['GfsCode']='140391';

$bill_id=$bill->outGoingBillRequest($formRequest);
if(is_numeric($bill_id) && $bill_id > 0 && $bill_id == round($bill_id, 0)){
    $an_error_occured=FALSE;
     foreach($selected_Payment_Item_Cache_List_ID as $Payment_Item_Cache_List_ID){
         $sql_update_selected_items_result=mysqli_query($conn,"UPDATE tbl_item_list_cache SET card_and_mobile_payment_status='pending',gepg_bill_id='$bill_id' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
         if(!$sql_update_selected_items_result){
             $an_error_occured=TRUE;
         }
    }
    if(!$an_error_occured){
       echo $bill_id; 
    }else{
        echo "fail";
    }
}else{
    echo "fail";
}
/*GFS CODES
--------------------------------
140301
140339
140353
140370
140379
*/
?>

