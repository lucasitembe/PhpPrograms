<?php
 include("./includes/connection.php");
 session_start();
ini_set('display_errors', true);
if(isset($_POST['selected_Payment_Item_Cache_List_ID'])){
    $selected_Payment_Item_Cache_List_ID=$_POST['selected_Payment_Item_Cache_List_ID'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    
    $sql_get_the_last_inserted_id_result=mysqli_query($conn,"SELECT card_and_mobile_payment_transaction_id,transaction_date_time FROM tbl_card_and_mobile_payment_transaction WHERE Employee_ID='$Employee_ID' ORDER BY card_and_mobile_payment_transaction_id DESC LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_get_the_last_inserted_id_result)>0){
        while($trans_id_rows=mysqli_fetch_assoc($sql_get_the_last_inserted_id_result)){
            $card_and_mobile_payment_transaction_id=$trans_id_rows['card_and_mobile_payment_transaction_id'];
            $transaction_date_time=$trans_id_rows['transaction_date_time'];
        }
    }    
    $count_succes=1;
    ///update card_and_mobile_payment_transaction_id for the selected items
    foreach($selected_Payment_Item_Cache_List_ID as $Payment_Item_Cache_List_ID){
         $sql_update_selected_items_result=mysqli_query($conn,"UPDATE tbl_item_list_cache SET card_and_mobile_payment_status='unprocessed',card_and_mobile_payment_transaction_id='0' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
         if($sql_update_selected_items_result){
             $count_succes++;
         }
    }
   // echo $count_succes;
}