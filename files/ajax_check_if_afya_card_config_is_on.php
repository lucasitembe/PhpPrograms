<?php
include("./includes/connection.php");
$function_module=$_POST['function_module'];
$check_if_requested_module_is_on_result=mysqli_query($conn,"SELECT new_payment_method_config_btn_name FROM tbl_new_payment_method_config_btn WHERE new_payment_method_config_btn_name='$function_module' AND visibility_status='show'") or die(mysqli_error($conn));
if(mysqli_num_rows($check_if_requested_module_is_on_result)>0){
    echo "enabled";
}else{
    echo "disabled";
}