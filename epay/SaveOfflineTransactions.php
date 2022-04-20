<?php
include("../includes/connection.php");


    $amount=$_POST['amount'];
    $registration_id=$_POST['registration_id'];
    $auth_code=$_POST['auth_code'];
    $terminal_id=$_POST['terminal_id'];

$sql_save_offline_payment="INSERT INTO tbl_offline_payment (terminal_id,amount_required,authorization_code,Registration_ID,Transaction_Date)"
        . "VALUES('$terminal_id','$amount','$auth_code','$registration_id',(select now()))";
$sql_save_offline_payment_result=mysqli_query($conn,$sql_save_offline_payment) or die(mysqli_error($conn));
if($sql_save_offline_payment_result){
    echo "saved";
}else{
    echo "fail";
}

?>

