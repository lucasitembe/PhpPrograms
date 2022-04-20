<?php
include("./includes/connection.php");
if(isset($_POST['selected_Payment_Item_Cache_List_ID'])){
    $selected_Payment_Item_Cache_List_ID=$_POST['selected_Payment_Item_Cache_List_ID'];
    $Registration_ID=$_POST['Registration_ID'];
    $Payment_Cache_ID=$_POST['Payment_Cache_ID'];
    $grand_total_price=$_POST['grand_total_price'];
    $card_no1=$_POST['card_no'];
    $card_no = str_replace(' ','',$card_no1);
    $patient_phone_number_=$card_no;

    $hospital_id="1101";
     
    $patient_name =mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'"))['Patient_Name'];

    function Start_Transaction(){
        mysqli_query($conn,"START TRANSACTION");
    }

    function Commit_Transaction(){
        mysqli_query($conn,"COMMIT");
    }

    function Rollback_Transaction(){
        mysqli_query($conn,"ROLLBACK");
    }
    $Employee_ID=$_POST['Employee_ID'];
    Start_Transaction();
    $an_error_occured=FALSE;
    //create card_and_mobile_payment_transaction
    $sql_insert_result=mysqli_query($conn,"INSERT INTO tbl_card_and_mobile_payment_transaction (Registration_ID,payment_amount,patient_phone,transaction_status,transaction_date_time,Employee_ID,Payment_Cache_ID) VALUES('$Registration_ID','$grand_total_price','$patient_phone_number_','pending',NOW(),'$Employee_ID','$Payment_Cache_ID')") or die(mysqli_error($conn));
    if(!$sql_insert_result){
       $an_error_occured=TRUE;
    }
    $sql_get_the_last_inserted_id_result=mysqli_query($conn,"SELECT card_and_mobile_payment_transaction_id,transaction_date_time FROM tbl_card_and_mobile_payment_transaction WHERE Employee_ID='$Employee_ID' ORDER BY card_and_mobile_payment_transaction_id DESC LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_get_the_last_inserted_id_result)>0){
        while($trans_id_rows=mysqli_fetch_assoc($sql_get_the_last_inserted_id_result)){
            $card_and_mobile_payment_transaction_id=$trans_id_rows['card_and_mobile_payment_transaction_id'];
            $transaction_date_time=$trans_id_rows['transaction_date_time'];
        }
    }
    ///update card_and_mobile_payment_transaction_id for the selected items
    foreach($selected_Payment_Item_Cache_List_ID as $Payment_Item_Cache_List_ID){
         $sql_update_selected_items_result=mysqli_query($conn,"UPDATE tbl_item_list_cache SET card_and_mobile_payment_status='pending',card_and_mobile_payment_transaction_id='$hospital_id$card_and_mobile_payment_transaction_id' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
         if(!$sql_update_selected_items_result){
             $an_error_occured=TRUE;
         }
    } 
    //update generate payment code
    $sql_update_generated_payment_code_result=mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET bill_payment_code='$hospital_id$card_and_mobile_payment_transaction_id' WHERE card_and_mobile_payment_transaction_id='$card_and_mobile_payment_transaction_id'") or die(mysqli_error($conn));
    if(!$sql_update_generated_payment_code_result){ 
        $an_error_occured=TRUE;
    }
    if(!$an_error_occured){
        Commit_Transaction();
    }else{
        Rollback_Transaction();
    }
    $payment_ref="$hospital_id$card_and_mobile_payment_transaction_id";
if(!$an_error_occured){
$xml_data="<?xml version='1.0' encoding='UTF-8'?>
<requestPaymentDeposit>
	<cmsReference>$payment_ref</cmsReference>
	<paymentChannel>NMB Mobile</paymentChannel>
	<hospital_id>TMJ</hospital_id>
	<pay_reference_number>$payment_ref</pay_reference_number>
	<card_reference_number>$card_no</card_reference_number>
	<amountPaid>$grand_total_price</amountPaid>
	<currency>TZS</currency>
	<narration/>
</requestPaymentDeposit>";

$header = [
    'Content-Type: application/xml',
    'Accept: application/xml'
];
$url = 'http://192.168.43.4/cardService/Transact?instruct=cardTransact&param=cardUsage&operand=usage';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url );
curl_setopt($ch, CURLOPT_POST, true );
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data );
curl_setopt($ch, CURLOPT_POSTREDIR, 3);
echo $result = curl_exec($ch);
curl_close($ch);
    }
}


