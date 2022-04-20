<?php
    include("./includes/connection.php");
      $card_and_mobile_payment_transaction_id=$_POST['card_and_mobile_payment_transaction_id'];
      $bill_payment_code=$_POST['bill_payment_code'];

      if(!empty($card_and_mobile_payment_transaction_id) && !empty($bill_payment_code)){
          
            $update_sangira_number=mysqli_query($conn,"UPDATE tbl_item_list_cache SET card_and_mobile_payment_transaction_id='$bill_payment_code' WHERE card_and_mobile_payment_transaction_id='$card_and_mobile_payment_transaction_id' AND card_and_mobile_payment_transaction_id <>'' AND card_and_mobile_payment_transaction_id <> '0' AND card_and_mobile_payment_transaction_id IS NOT NULL");
            if($update_sangira_number){
                echo "Duplicate Sangira Number Removed Successfully";
            }else{
                echo "Duplicate Sangira Number Not Removed Try Again";
            }
      }

?>