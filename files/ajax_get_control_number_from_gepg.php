<?php		    
ini_set('display_errors', true);
//include_once("./includes/connection.php");

if(isset($_POST['Payment_Cache_ID'])){
    $Payment_Cache_ID=$_POST['Payment_Cache_ID'];
}else{
    $Payment_Cache_ID=""; 
}




//$url = "http://178.128.34.230:8088/response/billresponse/$Payment_Cache_ID/";
//$url = "http://154.118.230.18:80/api/sp/paymentRequest";
//$curl = curl_init();
//// Set some options - we are passing in a useragent too here
//curl_setopt_array($curl, array(
//    CURLOPT_RETURNTRANSFER => 1,
//    CURLOPT_URL => "$url",
//    CURLOPT_USERAGENT => 'cURL Request'
//));
//// Send the request & save response to $resp
//echo $resp = curl_exec($curl);
//// Close request to clear up some resources
//curl_close($curl);

$xml_data="
           <gepgBillSubReq>
                <BillHdr>
                    <SpCode>SP607</SpCode>
                    <RtrRespFlg>true</RtrRespFlg>
                </BillHdr>
                <BillTrxInf>
                    <BillId>7885</BillId>
                    <SubSpCode>1001</SubSpCode>
                    <SpSysId>tjv47</SpSysId>
                    <BillAmt>7885</BillAmt>
                    <MiscAmt>0</MiscAmt>
                    <BillExprDt>2017-05-30T10:00:01</BillExprDt>
                    <PyrId>Palapala</PyrId>
                    <PyrName>Charles Palapala</PyrName>
                    <BillDesc>Bill Number 7885</BillDesc>
                    <BillGenDt>2017-02-22T10:00:10</BillGenDt>
                    <BillGenBy>100</BillGenBy>
                    <BillApprBy>Hashim</BillApprBy>
                    <PyrCellNum>0699210053</PyrCellNum>
                    <PyrEmail>charlestp@yahoo.com</PyrEmail>
                    <Ccy>TZS</Ccy>
                    <BillEqvAmt>7885</BillEqvAmt>
                    <RemFlag>true</RemFlag>
                    <BillPayOpt>1</BillPayOpt>
                    <BillItems>
                        <BillItem>
                            <BillItemRef>788578851</BillItemRef>
                            <UseItemRefOnPay>N</UseItemRefOnPay>
                            <BillItemAmt>7885</BillItemAmt>
                            <BillItemEqvAmt>7885</BillItemEqvAmt>
                            <BillItemMiscAmt>0</BillItemMiscAmt>
                            <GfsCode>140206</GfsCode>
                        </BillItem>
                        <BillItem>
                            <BillItemRef>788578852</BillItemRef>
                            <UseItemRefOnPay>N</UseItemRefOnPay>
                            <BillItemAmt>7885</BillItemAmt>
                            <BillItemEqvAmt>7885</BillItemEqvAmt>
                            <BillItemMiscAmt>0</BillItemMiscAmt>
                            <GfsCode>140301</GfsCode>
                        </BillItem>
                    </BillItems>
                </BillTrxInf>
</gepgBillSubReq>";

$header="
        'Content-Type': 'application/xml',
        'Gepg-Com': 'default.sp.in',
        'Gepg-Code': SP607',
        'Accept': 'application/xml'";
//  $url = 'http://178.128.34.230:8088/bill/post/';
  $url = "http://154.118.230.18:80/api/sp/paymentRequest";
  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $url );
  curl_setopt( $ch, CURLOPT_POST, true );
  curl_setopt( $ch, CURLOPT_HTTPHEADER, array($header));
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $ch, CURLOPT_POSTFIELDS, "$xml_data" );
 echo $result = curl_exec($ch);
  curl_close($ch);


?>
