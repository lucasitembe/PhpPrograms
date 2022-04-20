<?php
include("./includes/connection.php");
include './epay_recon/requestConfiguration.php';

$reference_number_filter="";
if($_POST['reference_number']){
   $reference_number=$_POST['reference_number'];
}

if($_POST['Date_From']){
   $Date_From=$_POST['Date_From'];
}

if($_POST['banck']){
   $banck=$_POST['banck'];
}

$in = false;
if($banck == "nmb" && !empty($Date_From) && !empty($reference_number)){
    $obj = new Banck_Request();
    $response = $obj->makeReconciliationRequest($Date_From);
    foreach ($response as $rows){
        if($rows['reference'] = "SAS999".$reference_number){
            $receipt = $rows['receipt'];
            $amount = $rows['amount'];
            $timestamp = $rows['timestamp'];
            $operator = $rows['channel'];
            $query = mysqli_query($conn,"INSERT INTO `tbl_mobile_payemts`(`Payment_Number`, `Operator_name`,`Amount`, `Payment_Date`,`receiptNumber`)
            VALUES ('$reference_number','$operator','$amount','$timestamp','$reference_number')") or die(mysqli_error($conn));
            if($query){
                $in = true;
                echo $reference_number;
            }else{
                echo 'false';
            }
            break;
        }
    }
    if(!$in){
       echo 'false'; 
    }
}

else if($banck == "crdb" && !empty($Date_From) && !empty($reference_number)){
        $data = json_encode(array(
                "reference" => $reference_number
        ));
        $url = "http://41.188.172.204/epayRecon/index.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Accept: application/json'
        ));			
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // set curl without result echo
        $api_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
        curl_close($ch);
        $json_array = (json_decode($api_response, true));
        $response = $json_array['transactions'];
        
        foreach ($response as $rows){
            $receipt = $rows['RECEIPT_NUMBER'];
            $amount = $rows['AMOUNT'];
            $timestamp = $rows['PAYMENT_DATE'];
            $operator = $rows['CHANNEL'];
            $query = mysqli_query($conn,"INSERT INTO `tbl_mobile_payemts`(`Payment_Number`, `Operator_name`,`Amount`, `Payment_Date`,`receiptNumber`)
            VALUES ('$reference_number','$operator','$amount','$timestamp','$reference_number')") or die(mysqli_error($conn));
            if($query){
                $in = true;
                echo $reference_number;
            }else{
                echo 'false';
            }
            break;
        }
        if(!$in){
           echo 'false'; 
        }
}else{
    echo 'false';
}
mysqli_close($conn);
