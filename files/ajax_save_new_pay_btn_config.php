<?php
include("./includes/connection.php");
$clicked_btn=$_POST['clicked_btn'];
//$sql_update_button_status_result=mysqli_query($conn,"UPDATE tbl_new_payment_method_config_btn
//SET visibility_status = IF (visibility_status='visible', 'hidden', 'hidden')
//WHERE new_payment_method_config_btn_name='$sql_update_button_status_result'") or die(mysqli_error($conn));

$sql_update_button_status_result=mysqli_query($conn,"UPDATE  tbl_new_payment_method_config_btn
SET     visibility_status = CASE WHEN visibility_status = 'show' THEN 'hide'  ELSE 'show' END
WHERE new_payment_method_config_btn_name='$clicked_btn'
") or die(mysqli_error($conn));



if($sql_update_button_status_result){
    echo "success";
}else{
    echo "fail";
}