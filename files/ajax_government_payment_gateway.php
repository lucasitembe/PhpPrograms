<?php		    
ini_set('display_errors', true);
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
}else{
    $Registration_ID=""; 
}
if(isset($_POST['Patient_Name'])){
   $Patient_Name=$_POST['Patient_Name']; 
}else{
   $Patient_Name=""; 
}
if(isset($_POST['Amount_Required'])){
   $Amount_Required=$_POST['Amount_Required']; 
}else{
   $Amount_Required=""; 
}
if(isset($_POST['Check_In_Type'])){
   $Check_In_Type=$_POST['Check_In_Type']; 
}else{
   $Check_In_Type=""; 
}
if(isset($_POST['Payment_Cache_ID'])){
   $Payment_Cache_ID=$_POST['Payment_Cache_ID']; 
}else{
   $Payment_Cache_ID=""; 
}
//$xml_data="<gepgBillSubRespAck>
//	<BillTrxInfo>
//		<BillId>$Payment_Cache_ID</BillId>
//		<BillAmount>$Amount_Required</BillAmount>
//		<PayerId>$Registration_ID</PayerId>
//		<PayerName>$Patient_Name</PayerName>
//		<BillDesc>Paying for $Check_In_Type</BillDesc>
//		<GeneratedBy>Operator</GeneratedBy>
//		<ApprovedBy>Supervisor</ApprovedBy>
//		<PayerCellNumber>255767028849</PayerCellNumber>
//		<PayerEmail>email@email.com</PayerEmail>
//		<Currency>TZS</Currency>
//		<GfsCode>140206</GfsCode>
//	</BillTrxInfo>
//</gepgBillSubRespAck>";
//$xml_data="
//           <gepgBillSubReq>
//                <BillHdr>
//                    <SpCode>SP607</SpCode>
//                    <RtrRespFlg>true</RtrRespFlg>
//                </BillHdr>
//                <BillTrxInf>
//                    <BillId>7885</BillId>
//                    <SubSpCode>1001</SubSpCode>
//                    <SpSysId>tjv47</SpSysId>
//                    <BillAmt>7885</BillAmt>
//                    <MiscAmt>0</MiscAmt>
//                    <BillExprDt>2017-05-30T10:00:01</BillExprDt>
//                    <PyrId>Palapala</PyrId>
//                    <PyrName>Charles Palapala</PyrName>
//                    <BillDesc>Bill Number 7885</BillDesc>
//                    <BillGenDt>2017-02-22T10:00:10</BillGenDt>
//                    <BillGenBy>100</BillGenBy>
//                    <BillApprBy>Hashim</BillApprBy>
//                    <PyrCellNum>0699210053</PyrCellNum>
//                    <PyrEmail>charlestp@yahoo.com</PyrEmail>
//                    <Ccy>TZS</Ccy>
//                    <BillEqvAmt>7885</BillEqvAmt>
//                    <RemFlag>true</RemFlag>
//                    <BillPayOpt>1</BillPayOpt>
//                    <BillItems>
//                        <BillItem>
//                            <BillItemRef>788578851</BillItemRef>
//                            <UseItemRefOnPay>N</UseItemRefOnPay>
//                            <BillItemAmt>7885</BillItemAmt>
//                            <BillItemEqvAmt>7885</BillItemEqvAmt>
//                            <BillItemMiscAmt>0</BillItemMiscAmt>
//                            <GfsCode>140206</GfsCode>
//                        </BillItem>
//                        <BillItem>
//                            <BillItemRef>788578852</BillItemRef>
//                            <UseItemRefOnPay>N</UseItemRefOnPay>
//                            <BillItemAmt>7885</BillItemAmt>
//                            <BillItemEqvAmt>7885</BillItemEqvAmt>
//                            <BillItemMiscAmt>0</BillItemMiscAmt>
//                            <GfsCode>140301</GfsCode>
//                        </BillItem>
//                    </BillItems>
//                </BillTrxInf>
//</gepgBillSubReq>";
//
//$header="
//        'Content-Type': 'application/xml',
//        'Gepg-Com': 'default.sp.in',
//        'Gepg-Code': SP607',
//        'Accept': 'application/xml'";
////  $url = 'http://178.128.34.230:8088/bill/post/';
//  $url = 'http://154.118.230.18:80/api/bill/sigqrequest';
//  $ch = curl_init();
//  curl_setopt( $ch, CURLOPT_URL, $url );
//  curl_setopt( $ch, CURLOPT_POST, true );
//  curl_setopt( $ch, CURLOPT_HTTPHEADER, array($header));
//  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//  curl_setopt( $ch, CURLOPT_POSTFIELDS, "$xml_data" );
// echo $result = curl_exec($ch);
//  curl_close($ch);
//

?>
<?php
if (!$cert_store = file_get_contents("gepcertificate/gepgclientprivatekey.pfx")) {
    echo "Error: Unable to read the cert file\n";
    exit;
}
else
{
	if (openssl_pkcs12_read($cert_store, $cert_info, "passpass"))   
	{
	
	echo "Certificate Information\n";
    print_r($cert_info['pkey']);

	
//Bill Request 

 $content ="<gepgBillSubReq>
    <BillHdr>
        <SpCode>SP108</SpCode>
        <RtrRespFlg>true</RtrRespFlg>
    </BillHdr>
    <BillTrxInf>
        <BillId>310001483660</BillId>
        <SubSpCode>1002</SubSpCode>
        <SpSysId>DUWASA001</SpSysId>
        <BillAmt>76937.39</BillAmt>
        <MiscAmt>0</MiscAmt>
        <BillExprDt>2018-06-30T00:00:00</BillExprDt>
        <PyrId>201900157221</PyrId>
        <PyrName>VIMAL RAWAL  AND H. RAWAL</PyrName>
        <BillDesc>Property Rate</BillDesc>
        <BillGenDt>2017-12-18T00:00:00</BillGenDt>
        <BillGenBy>Frank     Mumbara  Christopher </BillGenBy>
        <BillApprBy>TRA Admin</BillApprBy>
        <PyrCellNum>255784785548</PyrCellNum>
        <PyrEmail/>
        <Ccy>TZS</Ccy>
        <BillEqvAmt>76937.39</BillEqvAmt>
        <RemFlag>false</RemFlag>
        <BillPayOpt>1</BillPayOpt>
        <BillItems>
            <BillItem>
                <BillItemRef>310001483660</BillItemRef>
                <UseItemRefOnPay>N</UseItemRefOnPay>
                <BillItemAmt>76937.39</BillItemAmt>
                <BillItemEqvAmt>76937.39</BillItemEqvAmt>
                <BillItemMiscAmt>0</BillItemMiscAmt>
                <GfsCode>11310102</GfsCode>
            </BillItem>
        </BillItems>
    </BillTrxInf>
</gepgBillSubReq>";


		//create signature
		openssl_sign($content, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");

		//output crypted data base64 encoded
	    $signature = base64_encode($signature);         
	    echo "Signature of Signed Content"."\n".$signature."\n";

		//Combine signature and content signed
		$data = "<Gepg>".$content." <gepgSignature>".$signature."</gepgSignature></Gepg>";
		

	    $resultCurlPost = "";
	    $serverIp = "http://154.118.230.18";

		$uri = "/api/bill/sigqrequest";
		

		$data_string = $data;
		echo "Message details"."\n".$data_string."\n";
		echo "\n";
		echo "Request Lenght:\n";
		echo strlen($data_string);
		echo "\n";

		$ch = curl_init($serverIp.$uri);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);  
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						    'Content-Type:application/xml',
						    'Gepg-Com:default.sp.in',
						    'Gepg-Code:SP108',
						    'Content-Length:'.strlen($data_string))
		);

		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);

		$resultCurlPost = curl_exec($ch);
		curl_close($ch);
		
		if(!empty($resultCurlPost)){

			echo "Received Response\n";
    		print_r($resultCurlPost);
    		echo "\n";
    		echo "Response Length:\n";
			echo strlen($resultCurlPost);			
			
			//Tags used in substring response content
			$datatag = "gepgBillSubReqAck";
			$sigtag = "gepgSignature";
			
			//Get data and signature from response
			$vdata = getDataString($resultCurlPost,$datatag);
			$vsignature = getSignatureString($resultCurlPost,$sigtag);
			
			echo "\n";
			echo "Data Received:\n";
			echo $vdata;
			echo "\n";
			echo "Data Length:\n";
			echo strlen($vdata);
			echo "\n";
			echo "Signature Received:\n";
			echo $vsignature;
			echo "\n";

			//Get Certificate contents
			if (!$pcert_store = file_get_contents("gepgpubliccertificate.pfx")) {
    			echo "Error: Unable to read the cert file\n";
    			exit;
			}else{

				//Read Certificate
				if (openssl_pkcs12_read($pcert_store, $pcert_info, "passpass")) {
					print_r($pcert_info);
					//print_r($pcert_info['extracerts']);
					//print_r($pcert_info['extracerts']['0']);

					//Decode Received Signature String
					$rawsignature = base64_decode($vsignature);

					//Verify Signature and state whether signature is okay or not
					$ok = openssl_verify($vdata, $rawsignature, $pcert_info['extracerts']['0']);
					if ($ok == 1) {
						echo "Signature Status:";
					    echo "GOOD";
					} elseif ($ok == 0) {
						echo "Signature Status:";
					    echo "BAD";
					} else {
						echo "Signature Status:";
					    echo "UGLY, Error checking signature";
					}
				}  
			}
		}
		else
		{
			echo "No result Returned"."\n";
		}
	    
	} 
	else
	{

    echo "Error: Unable to read the cert store.\n";
    exit;
	}

}
//Function to get Data string
function getDataString($inputstr,$datatag){
	$datastartpos = strpos($inputstr, $datatag);
	$dataendpos = strrpos($inputstr, $datatag);
	$data=substr($inputstr,$datastartpos - 1,$dataendpos + strlen($datatag)+2 - $datastartpos);
	return $data;
}
//Function to get Signature string
function getSignatureString($inputstr,$sigtag){
	$sigstartpos = strpos($inputstr, $sigtag);
	$sigendpos = strrpos($inputstr, $sigtag);
	$signature=substr($inputstr,$sigstartpos + strlen($sigtag)+1,$sigendpos - $sigstartpos -strlen($sigtag)-3);
	return $signature;
}

 
?>

