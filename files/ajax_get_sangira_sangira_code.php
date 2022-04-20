<?php
include("./includes/connection.php");
if(isset($_POST['Registration_ID'])&&isset($_POST['Payment_Cache_ID'])){
   $Registration_ID=$_POST['Registration_ID'];
   $Payment_Cache_ID=$_POST['Payment_Cache_ID'];
   session_start();
   $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
   $sql_get_sangira_sangira_code_result=mysqli_query($conn,"SELECT bill_payment_code FROM tbl_card_and_mobile_payment_transaction WHERE Registration_ID='$Registration_ID' AND Payment_Cache_ID='$Payment_Cache_ID' AND Employee_ID='$Employee_ID' ORDER BY card_and_mobile_payment_transaction_id DESC LIMIT 1") or die(mysqli_error($conn));
   if(mysqli_num_rows($sql_get_sangira_sangira_code_result)>0){
       $bill_payment_code=mysqli_fetch_assoc($sql_get_sangira_sangira_code_result)['bill_payment_code'];
   }
}
echo $bill_payment_code;