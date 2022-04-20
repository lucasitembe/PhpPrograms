<?php
include("./includes/connection.php");
session_start();

ini_set('display_errors', true);
$sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
while($date = mysqli_fetch_array($sql_date_time)){
    $Current_Date_Time = $date['Date_Time'];
}

$time =str_replace(" ","", date("Y-m-d"));
$value = explode("-",$time);
$cntrl = str_replace("20","",$value[0]).$value[1].$value[2];

if(isset($_POST['selected_Payment_Item_Cache_List_ID'])){
    $selected_Payment_Item_Cache_List_ID=$_POST['selected_Payment_Item_Cache_List_ID'];
    $Registration_ID=$_POST['Registration_ID'];

    $Payment_Cache_ID=$_POST['Payment_Cache_ID'];
    $grand_total_price=$_POST['grand_total_price'];
    $patient_phone_number_=(int)$_POST['patient_phone_number_'];

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
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
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
         $sql_update_selected_items_result=mysqli_query($conn,"UPDATE tbl_item_list_cache SET card_and_mobile_payment_status='pending',card_and_mobile_payment_transaction_id='BUH100$card_and_mobile_payment_transaction_id$cntrl' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
         if(!$sql_update_selected_items_result){
             $an_error_occured=TRUE;
         }
    }
    //update generate payment code
    $sql_update_generated_payment_code_result=mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET bill_payment_code='BUH100$card_and_mobile_payment_transaction_id$cntrl' WHERE card_and_mobile_payment_transaction_id='$card_and_mobile_payment_transaction_id'") or die(mysqli_error($conn));
    if(!$sql_update_generated_payment_code_result){
        $an_error_occured=TRUE;
    }
    if(!$an_error_occured){
        Commit_Transaction();
    }else{
        Rollback_Transaction();
    }
if(!$an_error_occured){
//$xml_data='<proxyGateway version="1.0" xmlns="https://nitume.co.tz/gateway/">
//                <header>
//                    <clientId>100400</clientId>
//                    <clientPassword>6768768587</clientPassword>
//                    <timestamp>201806121324</timestamp>
//                </header>
//                <body>
//                    <request>
//                        <command>Customer Paybill</command>
//                        <businessName>eHMS</businessName>
//                        <thirdPartyId>10001'.$card_and_mobile_payment_transaction_id.'</thirdPartyId>
//                        <paymentDate>'.$transaction_date_time.'</paymentDate>
//                        <msisdn>255'.$patient_phone_number_.'</msisdn>
//                        <currency>TZS</currency>
//                        <amount>'.$grand_total_price.'.00</amount>
//                        <invoiceNumber>10001'.$card_and_mobile_payment_transaction_id.'</invoiceNumber>
//                        <resultUrl>http://gpitgpsc.pconnects.com</resultUrl>
//                    </request>
//                </body>
//            </proxyGateway>';
//$header="Content-Type:application/xml";
//  $url = 'http://155.12.13.34:5165/nitumegateway/services/ussdpush';
//  $ch = curl_init();
//  curl_setopt( $ch, CURLOPT_URL, $url );
//  curl_setopt( $ch, CURLOPT_POST, true );
//  curl_setopt( $ch, CURLOPT_HTTPHEADER, array($header));
//  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//  curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_data );
// echo $result = curl_exec($ch);
//  curl_close($ch);


    function getStringValueFromXmlTag($inputContent, $tagValue)
{
    $sigStartPosition = strpos($inputContent, $tagValue);
    $sigEndPosition = strrpos($inputContent, $tagValue);
    $gatValueData = substr($inputContent, $sigStartPosition + strlen($tagValue) + 1, $sigEndPosition - $sigStartPosition - strlen($tagValue) - 3);

    return $gatValueData;
}

    $check_patient_status = mysqli_query($conn,"SELECT * FROM `tbl_patient_bill` WHERE `Patient_Status` in ('Inpatient','Outpatient') AND `Status`='active' and `Registration_ID`=$Registration_ID");

     $check_number_of_rows = mysqli_num_rows($check_patient_status);
     $amount_type ="";
     if($check_number_of_rows > 0){
       $amount_type ="FLEXIBLE";

     }else{

        $amount_type ="FIXED";
     }

    function varify($phone){
        if(substr($phone, 0,1) == '0'){
           $data = ['074','075','076'];
           $split = str_split($phone, 3);
        }else{
            $split = str_split(str_replace('+', '', $phone), 5);
            $data = ['25574','25575','25576'];
        }

        foreach ($data as $value) {
          if ($split[0] == $value) {
            return true;
          }
      }
       return false;
    }

    $clientId = "888800";
    $timestamp = "20181228143755";
    $passdata = $clientId."Crdb@2019".$timestamp;
    $password = base64_encode(hash("sha256", $passdata,True));

    $xml_data='<epsGateway xmlns="http://www.thehub.co.tz/" version="2.0">
            <header>
                <username>888800</username>
                <password>'.$password.'</password>
                <timestamp>20181228143755</timestamp>
            </header>
            <body>
                <request>
                    <command>CustomerPaybill</command>
                    <businessName>Eps App</businessName>
                    <transactionNumber>100'.$card_and_mobile_payment_transaction_id.$cntrl.'</transactionNumber>
                    <paymentDate>'.$transaction_date_time.'</paymentDate>
                    <name>'.$patient_name.'</name>
                    <paidFor>'.$Payment_Cache_ID.'</paidFor>
                    <msisdn>255'.$patient_phone_number_.'</msisdn>
                    <currency>TZS</currency>
                    <amount>'.$grand_total_price.'.00</amount>
                    <amountType>'.$amount_type.'</amountType>
                    <reference>BUH100'.$card_and_mobile_payment_transaction_id.$cntrl.'</reference>
                </request>
            </body>
        </epsGateway>';
    $newfile = "/var/www/html/Final_One/logs/access-".date("Y-m-d").".log";
    error_log("\n[".$Current_Date_Time."]:=> ".$xml_data, 3, $newfile);
$header = [
    'Content-Type: application/xml',
    'Accept: application/xml'
];

$url = "";
if (varify("255".$patient_phone_number_) == 1) {
  $url = 'http://157.230.84.237:8080/eps/services/v1/collection';
}else {
  $url = 'http://157.230.84.237:8080/eps/services/v1/ussdpush';
}

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_data );
$result = curl_exec($ch);
error_log("\n[".$Current_Date_Time."]Response ussdpush:=> ".$result, 3, $newfile);
//echo  $result;
curl_close($ch);

$statusCode = getStringValueFromXmlTag($result,"responseStatus");
$requestId = getStringValueFromXmlTag($result,"requestId");
if ($statusCode == "Accepted Successfully"){

    $sql_update_generated_payment_code_result=mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET response_code='$requestId' WHERE card_and_mobile_payment_transaction_id='BUH100$card_and_mobile_payment_transaction_id$cntrl'") or die(mysqli_error($conn));
    $api_key = '35C1F61E1C08F0';
    $contacts = '255'.$patient_phone_number_;
    $from = 'BUGANDO';
    $sms_text = urlencode("Unaweza kutumia SANGIRA namba BUH100$card_and_mobile_payment_transaction_id$cntrl kulipia kiasi cha Tsh. $grand_total_price.00 kwa huduma hii kupitia CRDB Wakala, USSD, ATM au Benki");

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, "http://www.siltechtz.com/app/smsapi/index.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api_key."&campaign=88&routeid=10&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text);
    $response = curl_exec($ch);
    curl_close($ch);
    echo "Accepted Successfully";
}else{
    echo "Fail";
}

    }
}
//  date_default_timezone_set('Africa/Dar_es_salaam');
//
////define date and time
//$date = date("d M Y H:i:s");
//
//// output
//echo strtotime($date);
