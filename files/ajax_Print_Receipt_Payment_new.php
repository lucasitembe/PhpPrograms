<?php
include("./includes/connection.php");
if(isset($_POST['Payment_Code'])){
    $Payment_Code=$_POST['Payment_Code'];
    //GET payment_id
    $sql_get_payment_id_result=mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Payment_Code='$Payment_Code'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_get_payment_id_result)>0){
        echo $Patient_Payment_ID=mysqli_fetch_assoc($sql_get_payment_id_result)['Patient_Payment_ID'];
    }else{
       echo "not_paid"; 
    }
}