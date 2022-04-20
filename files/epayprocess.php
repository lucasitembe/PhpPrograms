<?php
include("./includes/connection_epayment.php");
if(isset($_GET['checkifpay']) && $_GET['checkifpay']=='true'){
    $sql="SELECT Transaction_Status FROM tbl_bank_transaction_cache WHERE Transaction_Status='Completed' AND Payment_Code='".$_GET['paymentcd']."'";
    if(getRowCount($sql) > 0){
        echo 1;
    }else{
        echo 0;
    }
}
