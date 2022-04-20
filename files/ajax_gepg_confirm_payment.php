<?php		    
ini_set('display_errors', true);
if(isset($_POST['Payment_Cache_ID'])){
    $Payment_Cache_ID=$_POST['Payment_Cache_ID'];
}else{
    $Payment_Cache_ID=""; 
}




$url = "http://178.128.34.230:8088/payment/billpayment/$Payment_Cache_ID/";
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "$url",
    CURLOPT_USERAGENT => 'cURL Request'
));
// Send the request & save response to $resp
echo $resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);


?>
