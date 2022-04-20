<?php
include("./includes/connection.php");
if(isset($_POST['card_no'])){
$card_no=$_POST['card_no'];
$card_no = str_replace(' ','',$card_no);
$ch = curl_init();
$curl_url="http://192.168.43.4/cardService/Transact?instruct=cardTransact&param=cardEnquiry&operand=enquiry&cardReferenceNumber=".$card_no;
// Set query data here with the URL
curl_setopt($ch, CURLOPT_URL,$curl_url ); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
$content = trim(curl_exec($ch));
curl_close($ch);
echo $content;
}