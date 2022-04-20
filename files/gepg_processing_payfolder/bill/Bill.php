<?php
include_once 'Signature.php';

class Bill
{
    
    //__construct
    public $host = "localhost";
    public $username = "root";
    public $db = "bugando_new";
    public $password = "gpitg2014";

    //__construct
    protected $signatureHandler=null;
    public function __construct()
    {
        $this->signatureHandler = new Signature();


    }

    function outGoingBillRequest($formRequest)
    {
        $billId = $this->createBill($formRequest);
        if ($billId != 0) {
            $formRequest['BillId'] = $billId;
        }

        $billId=$this->gepgBillSubReq($formRequest);

        return $billId;
//    header("Location: http://localhost/amanahosptal/view_bill.php?billId=".$billId);
//     exit();
    }

    function createBill($formRequest)
    {
        $conn = new mysqli($this->host, $this->username, $this->password, $this->db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO bill  (BillDescription,BilledAmount,BillExpiryDate,BillGeneratedDate,CustomerEmailAddress,CustomerPayerName,CustomerPhoneNumber,BillPayOption,SubServiceProviderCode,ServiceProviderCode,GeneratedBy,BillEquivalentAmount,CollectionCenterCode,BillControlNumber,CurrencyCode,ExchangeRateAmount,MiscellaneousAmount,StatusCode) VALUES ('" . $formRequest['BillDescription'] . "'," . $formRequest['BilledAmount'] . ",'" . $formRequest['BillExpiryDate'] . "','" . $formRequest['BillGeneratedDate'] . "','" . $formRequest['CustomerEmailAddress'] . "','" . $formRequest['CustomerPayerName'] . "','" . $formRequest['CustomerPhoneNumber'] . "'," . $formRequest['BillPayOption'] . ",'" . $formRequest['SubServiceProviderCode'] . "','" . $formRequest['ServiceProviderCode'] . "','" . $formRequest['GeneratedBy'] . "'," . $formRequest['BillEquivalentAmount'] . ",'" . $formRequest['CollectionCenterCode'] . "','" . $formRequest['BillControlNumber'] . "','" . $formRequest['CurrencyCode'] . "'," . $formRequest['ExchangeRateAmount'] . ",0.00,'')";
        if ($conn->query($sql) === TRUE) {
            $formRequest['BillId'] = $conn->insert_id;
        }
        $conn->close();
        return $formRequest['BillId'];

    }

    function creategepgSpReconcReq($value1,$value2,$value3,$value4)
    {
        $SpReconcReqId = null;
        $conn = new mysqli($this->host, $this->username, $this->password, $this->db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO tbl_gepgSpReconcReq (SpReconcReqId, SpCode, SpSysId, ReconcOpt) VALUES ('$value1','$value2','$value3','$value4')";
        if ($conn->query($sql) === TRUE) {
            $SpReconcReqId = $conn->SpReconcReqId;
        }
        $conn->close();
        return $SpReconcReqId;

    }

    function gepgBillSubReq($formRequest)
    {
        $xmlGepgReqContent = $this->xmlGepgBillReqContent($formRequest);
        $generatedSignature = $this->signatureHandler->createSignature($xmlGepgReqContent, 'amana1234', 'amana', '/var/www/html/Final_One/files/gepg_processing_payfolder/keys/amanaprivate.pfx');
        $requestToGepg = "<Gepg>" .$xmlGepgReqContent."<gepgSignature>".$generatedSignature."</gepgSignature></Gepg>";
        $ch = curl_init("http://154.118.230.202/api/bill/sigqrequest");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestToGepg);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/xml',
                'Gepg-Com:default.sp.in',
                'Gepg-Code:SP186',
                'Content-Length:'.strlen($requestToGepg))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $resultCurlPost = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $this->updateBillByStatusCode($formRequest['BillId'], $this->getStringValueFromXmlTags($resultCurlPost, 'TrxStsCode'));
        $this->getStringValueFromXmlTags($resultCurlPost, 'TrxStsCode');
        return $formRequest['BillId'];

    }

    function xmlGepgBillReqContent($request)
    {
        $content = "<gepgBillSubReq>
        <BillHdr>
            <SpCode>" . $request['ServiceProviderCode'] . "</SpCode>
            <RtrRespFlg>true</RtrRespFlg>
        </BillHdr>
        <BillTrxInf>
            <BillId>" . $request['BillId'] . "</BillId>
            <SubSpCode>" . $request['SubServiceProviderCode'] . "</SubSpCode>
            <SpSysId>TAMANA001</SpSysId>
            <BillAmt>" . round($request['BilledAmount'], 2) . "</BillAmt>
            <MiscAmt>0</MiscAmt>
            <BillExprDt>" . $request['BillExpiryDate'] . "</BillExprDt>
            <PyrId>" . $request['CustomerPayerName'] . "</PyrId>
            <PyrName>" . $request['CustomerPayerName'] . "</PyrName>
            <BillDesc>" . $request['BillDescription'] . "</BillDesc>
            <BillGenDt>" . str_replace('EA', '', date("Y-m-dTh:i:s")) . "</BillGenDt>
            <BillGenBy>" . $request['GeneratedBy'] . "</BillGenBy>
            <BillApprBy>" . $request['GeneratedBy'] . "</BillApprBy>
            <PyrCellNum>" . $request['CustomerPhoneNumber'] . "</PyrCellNum>
            <PyrEmail>" . $request['CustomerEmailAddress'] . "</PyrEmail>
            <Ccy>" . $request['CurrencyCode'] . "</Ccy>
            <BillEqvAmt>" . $request['BillEquivalentAmount'] . "</BillEqvAmt>
            <RemFlag>false</RemFlag>
            <BillPayOpt>" . $request['BillPayOption'] . "</BillPayOpt>
            <BillItems>
                <BillItem>
                    <BillItemRef>" . uniqid() . "</BillItemRef>
                    <UseItemRefOnPay>N</UseItemRefOnPay>
                    <BillItemAmt>" . round($request['BilledAmount'], 2) . "</BillItemAmt>
                    <BillItemEqvAmt>" . round($request['BillEquivalentAmount'], 2) . "</BillItemEqvAmt>
                    <BillItemMiscAmt>0</BillItemMiscAmt>
                    <GfsCode>" . trim($request['GfsCode']) . "</GfsCode>
                </BillItem>
            </BillItems>
        </BillTrxInf>
    </gepgBillSubReq>";
        return $content;
    }
    function updateBillByStatusCode($billId, $statusCode)
    {
        $conn = new mysqli($this->host, $this->username, $this->password, $this->db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "update bill set StatusCode='" . $statusCode . "' where BillId='" . $billId . "'";
        $conn->query($sql);
        $conn->close();
    }

    function getStringValueFromXmlTags($inputContent, $tagValue)
    {
        $gatValueData = "";
        try {
            $sigStartPosition = strpos($inputContent, $tagValue);
            $sigEndPosition = strrpos($inputContent, $tagValue);
            $gatValueData = substr($inputContent, $sigStartPosition + strlen($tagValue) + 1, $sigEndPosition - $sigStartPosition - strlen($tagValue) - 3);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        return $gatValueData;
    }

    function incomingBillResponse()
    {
        $responseBody = file_get_contents('php://input');
        $extractedString =  $this->getDataStringFromXml($responseBody, "gepgBillSubResp");
        $extractedSignature = $this->getStringValueFromXmlTag($responseBody, "gepgSignature");
        $billId = $this->getStringValueFromXmlTag($responseBody, 'BillId');
        $payCntrNum = $this->getStringValueFromXmlTag($responseBody, 'PayCntrNum');
        $trxStsCode = $this->getStringValueFromXmlTag($responseBody, 'TrxStsCode');
        echo $this->updateBillByBillControlNumber($billId, $payCntrNum, $trxStsCode);
        
       /* if ($this->signatureHandler->verifySignature($extractedSignature, $extractedString,'passpass','gepg', '/var/www/html/amanahosptal/keys/gepgpubliccertificate.pfx')) {
            echo $this->updateBillByBillControlNumber($billId, $payCntrNum, $trxStsCode);
        } else {
            echo "Invalid Signature";
       } */
       
    }

    function Start_Transaction(){
        mysql_query("START TRANSACTION");
    }

    function Commit_Transaction(){
        mysql_query("COMMIT");
    }

    function Rollback_Transaction(){
        mysql_query("ROLLBACK");
    }
    function incomingBillpmtResponse()
    {
        $responseBody = file_get_contents('php://input');
        $extractedString =  $this->getDataStringFromXml($responseBody, "gepgPmtSpInfo");
        $extractedSignature = $this->getStringValueFromXmlTag($responseBody, "gepgSignature");
        $TrxId = $this->getStringValueFromXmlTag($responseBody, 'TrxId');
        $SpCode = $this->getStringValueFromXmlTag($responseBody, 'SpCode');
        $PayRefId = $this->getStringValueFromXmlTag($responseBody, 'PayRefId');
        $BillId = $this->getStringValueFromXmlTag($responseBody, "BillId");
        $PayCtrNum = $this->getStringValueFromXmlTag($responseBody, 'PayCtrNum');
        $BillAmt = $this->getStringValueFromXmlTag($responseBody, 'BillAmt');
        $PaidAmt = $this->getStringValueFromXmlTag($responseBody, 'PaidAmt');
        $BillPayOpt = $this->getStringValueFromXmlTag($responseBody, "BillPayOpt");
        $CCy = $this->getStringValueFromXmlTag($responseBody, 'CCy');
        $TrxDtTm = $this->getStringValueFromXmlTag($responseBody, 'TrxDtTm');
        $UsdPayChnl = $this->getStringValueFromXmlTag($responseBody, 'UsdPayChnl');
        $PyrCellNum = $this->getStringValueFromXmlTag($responseBody, "PyrCellNum");
        $PyrName = $this->getStringValueFromXmlTag($responseBody, 'PyrName');
        $PyrEmail = $this->getStringValueFromXmlTag($responseBody, 'PyrEmail');
        $PspReceiptNumber = $this->getStringValueFromXmlTag($responseBody, 'PspReceiptNumber');
        $PspName = $this->getStringValueFromXmlTag($responseBody, "PspName");
        $CtrAccNum = $this->getStringValueFromXmlTag($responseBody, "CtrAccNum");
        echo $this->updateBillPaymentInfo($TrxId, $SpCode, $PayRefId, $BillId, $PayCtrNum, $BillAmt, $PaidAmt, $BillPayOpt, $CCy, $TrxDtTm, $UsdPayChn, $PyrCellNum, $PyrName, $PyrEmail, $PspReceiptNumber, $PspName, $CtrAccNum); 
    
//      if($PaidAmt>=$BillAmt){
// 	$connection = mysql_connect("localhost","root","ehms2gpitg2014");
// 	if (!$connection) {
// 		die("Database connection failed: " . mysql_error());
// 	}
	
// 	// 2. Select a database to use  acc_fresh
	
//         $db_select = mysql_select_db("ehms_database",$connection);
//         //$db_select = mysql_select_db("ehms",$connection);
         
// 	if (!$db_select) {
// 	   die("Database selection failed: " . mysql_error());
// 	}
//         //if payment is completed then create ehms receipt and update all paid item to paid
//         ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//         ///if payment is completed create payment receipt in ehms and change item status to paid
//        // Start_Transaction();
//         $an_error_occured=FALSE;
//             //select payment details 
//             $sql_select_employee_detail=mysql_query("SELECT GeneratedBy FROM bill WHERE BillId ='$BillId'") or die(mysql_error());
//             if(mysql_num_rows($sql_select_employee_detail)>0){
//                while($employee_details_rows=mysql_fetch_assoc($sql_select_employee_detail)){
//                 $Employee_ID=$employee_details_rows['GeneratedBy'];
//                } 
//             }
//             $sql_select_patient_detail_result=mysql_query("SELECT Payment_Cache_ID,Registration_ID FROM tbl_payment_cache WHERE Payment_Cache_ID IN (SELECT Payment_Cache_ID FROM tbl_item_list_cache WHERE gepg_bill_id='$BillId')") or die(mysql_error());
//             if(mysql_num_rows($sql_select_patient_detail_result)>0){
//                while($patient_details_rows=mysql_fetch_assoc($sql_select_patient_detail_result)){
//                   $Payment_Cache_ID=$patient_details_rows['Payment_Cache_ID'];
//                   $Registration_ID=$patient_details_rows['Registration_ID'];
//                } 
//             }
//             //select detail for creating receipt
//             $sql_select_receipt_details_result=mysql_query("SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysql_error());
//             if(mysql_num_rows($sql_select_receipt_details_result)>0){
//                 $check_in_id_rows=mysql_fetch_assoc($sql_select_receipt_details_result);
//                 $Check_In_ID=$check_in_id_rows['Check_In_ID'];
// //                echo "===>1";
//                 $sql_other_payment_detail_result=mysql_query("SELECT Folio_Number,Sponsor_ID,Billing_Type FROM tbl_payment_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'") or die(mysql_error());
//                 if(mysql_num_rows($sql_other_payment_detail_result)>0){
//                    $other_payment_deta_rows=mysql_fetch_assoc($sql_other_payment_detail_result);
//                    $Folio_Number=$other_payment_deta_rows['Folio_Number'];
//                    $Sponsor_ID=$other_payment_deta_rows['Sponsor_ID'];
//                    $Billing_Type=$other_payment_deta_rows['Billing_Type'];
                   
// //                   echo "===>2";
//                    //get bill id
//                    $sql_get_bill_id_result=mysql_query("SELECT Patient_Bill_ID FROM tbl_patient_bill WHERE Registration_ID='$Registration_ID' AND Status='active'") or die(mysql_error());
//                    if(mysql_num_rows($sql_get_bill_id_result)>0){
//                        $Patient_Bill_ID=mysql_fetch_assoc($sql_get_bill_id_result)['Patient_Bill_ID'];
//                         ///create receipt id
//                        $sql_create_receipt_result=mysql_query("INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$receiptNumber','Mobile Online',NOW(),CURDATE())") or die(mysql_error());
                   
//                        if(!$sql_create_receipt_result){
//                            $an_error_occured=TRUE;
//                        }
// //                       echo "===>3";
//                        $Patient_Payment_ID = mysql_insert_id();
// //                       echo "pay_id=>$Patient_Payment_ID";
// //                       if($BillId=="55"){echo "breaking_point";break;}
//                        //select receipt item
//                        $sql_select_receipt_items_result=mysql_query("SELECT Edited_Quantity,Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Sub_Department_ID,Clinic_ID,finance_department_id,clinic_location_id FROM tbl_item_list_cache WHERE gepg_bill_id='$BillId'") or die(mysql_error());
//                        if(mysql_num_rows($sql_select_receipt_items_result)>0){
//                            while($receipt_item_rows=mysql_fetch_assoc($sql_select_receipt_items_result)){
//                                $Check_In_Type=$receipt_item_rows['Check_In_Type'];
//                                $Category=$receipt_item_rows['Category'];
//                                $Item_ID=$receipt_item_rows['Item_ID'];
//                                $Discount=$receipt_item_rows['Discount'];
//                                $Price=$receipt_item_rows['Price'];
//                                $Quantity=$receipt_item_rows['Quantity'];
//                                $Edited_Quantity=$receipt_item_rows['Edited_Quantity'];
//                                $Patient_Direction=$receipt_item_rows['Patient_Direction'];
//                                $Consultant=$receipt_item_rows['Consultant'];
//                                $Consultant_ID=$receipt_item_rows['Consultant_ID'];
//                                $Sub_Department_ID=$receipt_item_rows['Sub_Department_ID'];
//                                $Clinic_ID=$receipt_item_rows['Clinic_ID'];
//                                $finance_department_id=$receipt_item_rows['finance_department_id'];
//                                $clinic_location_id=$receipt_item_rows['clinic_location_id'];
//                                echo "===>4";
//                                if($Quantity<=0){
//                                   $Quantity=$Edited_Quantity;
//                                }
                               
//                                //create receipt item
//                                $sql_select_receipt_item_result=mysql_query("INSERT INTO tbl_patient_payment_item_list(Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Clinic_ID,Patient_Payment_ID,Sub_Department_ID,finance_department_id,clinic_location_id,Transaction_Date_And_Time) VALUES('$Check_In_Type','$Category','$Item_ID','$Discount','$Price','$Quantity','$Patient_Direction','$Consultant','$Consultant_ID','$Clinic_ID','$Patient_Payment_ID','$Sub_Department_ID','$finance_department_id','$clinic_location_id',NOW())") or die(mysql_error());
//                                if(!$sql_select_receipt_item_result){
//                                    $an_error_occured=TRUE;echo "===>5";
//                                }
                               
//                                //update all paid item under this transaction
//                                $sql_update_transaction_result=mysql_query("UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE gepg_bill_id='$BillId'") or die(mysql_error());
//                                if(!$sql_update_transaction_result){
//                                    $an_error_occured=TRUE;echo "===>8";
//                                }
//                                //update paid item
//                                $sql_update_paid_item_result=mysql_query("UPDATE tbl_item_list_cache SET Patient_Payment_ID='$Patient_Payment_ID',card_and_mobile_payment_status='completed',Status='paid' WHERE gepg_bill_id='$BillId'") or die(mysql_error());
//                                if(!$sql_update_paid_item_result){
//                                   $an_error_occured=TRUE; echo "===>7";
//                                }
//                            }
//                        }
//                    }
//                 }
//             }
//             //comit or rol back transaction
//             if(!$an_error_occured){
//                 //Commit_Transaction();
// //                echo "success";
//             }else{
//                // Rollback_Transaction(); 
// //                echo "faild";
//             }
//         }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //    if ($this->signatureHandler->verifySignature($extractedSignature, $content,'passpass','gepgclient', '/var/www/html/amana/keys/gepgclientpubliccertificate.pfx')) {
    //        return 'Ok';
    //    } else {
    //        return "Invalid Signature";
    //    }
       
    }


    function getStringValueFromXmlTag($inputContent, $tagValue)
    {
        $sigStartPosition = strpos($inputContent, $tagValue);
        $sigEndPosition = strrpos($inputContent, $tagValue);
        $gatValueData = substr($inputContent, $sigStartPosition + strlen($tagValue) + 1, $sigEndPosition - $sigStartPosition - strlen($tagValue) - 3);

        return $gatValueData;
    }

    function updateBillPaymentInfo($value1,$value2,$value3,$value4,$value5,$value6,$value7,$value8,$value9,$value10,$value11,$value12,$value13,$value14,$value15,$value16,$value17)
    {
        $responseContent = null;
        $conn = new mysqli($this->host, $this->username, $this->password, $this->db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO tbl_billpymentInfo ( TrxId, SpCode, PayRefId, BillId, PayCtrNum, BillAmt, PaidAmt, BillPayOpt, CCy, TrxDtTm, UsdPayChn, PyrCellNum, PyrName, PyrEmail, PspReceiptNumber, PspName, CtrAccNum) VALUES ('$value1','$value2','$value3','$value4','$value5','$value6','$value7','$value8','$value9','$value10','$value11','$value12','$value13','$value14','$value15','$value16','$value17')";
        if ($conn->query($sql) === TRUE) {
            $responseContent = "<gepgPmtSpInfoAck><TrxStsCode>7101</TrxStsCode></gepgPmtSpInfoAck>";
            $generatedSignature = $this->signatureHandler->createSignature($responseContent, 'amana1234', 'amana', '/var/www/html/Final_One/files/gepg_processing_payfolder/keys/amanaprivate.pfx');
            $response = "<Gepg>" . $responseContent . "<gepgSignature>" . $generatedSignature . "</gepgSignature></Gepg>";
            return $response;
        }
        $conn->close();
        return $responseContent;

    }

    function updateBillByBillControlNumber($billId, $payCntrNum, $trxStsCode)
    {
        $responseContent = null;
	$status = str_replace(";","",$trxStsCode);
        $conn = new mysqli($this->host, $this->username, $this->password, $this->db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "update bill set BillControlNumber=" . $payCntrNum . ",StatusCode=" . $status . " where BillId=" . $billId . "";
        if ($conn->query($sql) === TRUE) {
            $responseContent = "<gepgBillSubRespAck><TrxStsCode>7101</TrxStsCode></gepgBillSubRespAck>";
            $generatedSignature = $this->signatureHandler->createSignature($responseContent, 'amana1234', 'amana', '/var/www/html/Final_One/files/gepg_processing_payfolder/keys/amanaprivate.pfx');
            $response = "<Gepg>" . $responseContent . "<gepgSignature>" . $generatedSignature . "</gepgSignature></Gepg>";
            return $response;
        }
        $conn->close();
        return $responseContent ;

    }

    function getDataStringFromXml($inputContent,$xmlTag){
        $data ="";
        $startPosition = strpos($inputContent, $xmlTag);
        $endPosition = strrpos($inputContent, $xmlTag);
        $data=substr($inputContent,$startPosition - 1,$endPosition + strlen($xmlTag)+2 - $startPosition);
        return $data;
    }
    
    function incomingSpReconcRespResponse(){
        $responseBody = file_get_contents('php://input');
        $extractedString =  $this->getDataStringFromXml($responseBody, "gepgSpReconcResp");
        $extractedSignature = $this->getStringValueFromXmlTag($responseBody, "gepgSignature");
        $SpReconcReqId = $this->getStringValueFromXmlTag($responseBody, 'SpReconcReqId');
        $SpCode = $this->getStringValueFromXmlTag($responseBody, 'SpCode');
        $SpName = $this->getStringValueFromXmlTag($responseBody, 'SpName');
        $ReconcStsCode = $this->getStringValueFromXmlTag($responseBody, 'ReconcStsCode');

        $data_code = $this->getStringValueFromXmlTag($responseBody, 'ReconcTrans');
        $array_data = explode("</ReconcTrxInf>", $data_code);
        $index = 0;
        $status = false;
        while ($index < (sizeof($array_data)-1)){
            $SpBillId = $this->getStringValueFromXmlTag($array_data[$index], 'SpBillId');
            $BillCtrNum = $this->getStringValueFromXmlTag($array_data[$index], 'BillCtrNum');
            $pspTrxId = $this->getStringValueFromXmlTag($array_data[$index], "pspTrxId");
            $PaidAmt = $this->getStringValueFromXmlTag($array_data[$index], 'PaidAmt');
            $CCy = $this->getStringValueFromXmlTag($array_data[$index], 'CCy');
            $PayRefId = $this->getStringValueFromXmlTag($array_data[$index], 'PayRefId');
            $TrxDtTm = $this->getStringValueFromXmlTag($array_data[$index], "TrxDtTm");
            $CtrAccNum = $this->getStringValueFromXmlTag($array_data[$index], 'CtrAccNum');
            $UsdPayChnl = $this->getStringValueFromXmlTag($array_data[$index], 'UsdPayChnl');
            $PspName = $this->getStringValueFromXmlTag($array_data[$index], 'PspName');
            $PspCode = $this->getStringValueFromXmlTag($array_data[$index], "PspCode");
            $DptCellNum = $this->getStringValueFromXmlTag($array_data[$index], 'DptCellNum');
            $DptName = $this->getStringValueFromXmlTag($array_data[$index], 'DptName');
            $DptEmailAddr = $this->getStringValueFromXmlTag($array_data[$index], 'DptEmailAddr');
            $Remarks = $this->getStringValueFromXmlTag($array_data[$index], "Remarks");
            $ReconcRsv1 = $this->getStringValueFromXmlTag($array_data[$index], "ReconcRsv1");
            $ReconcRsv2 = $this->getStringValueFromXmlTag($array_data[$index], "ReconcRsv2");
            $ReconcRsv3 = $this->getStringValueFromXmlTag($array_data[$index], "ReconcRsv3");
            $status = $this->updateSpReconcResp($SpReconcReqId, $SpCode, $SpName,$ReconcStsCode, $SpBillId, $PayRefId,$BillCtrNum, $PaidAmt, $CCy,$TrxDtTm, $CtrAccNum, $UsdPayChnl,$DptCellNum, $DptName, $DptNameEmailAddr,$Remarks, $ReconcRsv1, $ReconcRsv2,$ReconcRsv3);
            $index = $index + 1;
        }
        if ($status){
            $responseContent = "<gepgSpReconcRespAck><ReconcStsCode>7101</ReconcStsCode></gepgSpReconcRespAck>";
            $generatedSignature = $this->signatureHandler->createSignature($responseContent, 'amana1234', 'amana', '/var/www/html/Final_One/files/gepg_processing_payfolder/keys/amanaprivate.pfx');
            $response = "<Gepg>" . $responseContent . "<gepgSignature>" . $generatedSignature . "</gepgSignature></Gepg>";
            echo $response;
        }
            
    //     if ($this->signatureHandler->verifySignature($extractedSignature, $extractedString,'passpass','gepg', '/var/www/html/amana/keys/gepgpubliccertificate.pfx')) {
    //     echo 'Ok';
    //     } else {
    //     echo "Invalid Signature";
    //     }
       
    }

    function updateSpReconcResp($value1,$value2,$value3,$value4,$value5,$value6,$value7,$value8,$value9,$value10,$value11,$value12,$value13,$value14,$value15,$value16,$value17,$value18,$value19){
         $conn = mysql_connect($this->host, $this->username, $this->password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($this->db);
        $sql = "INSERT INTO tbl_gepgSpReconcRespo ( SpReconcReqId, SpCode, SpName, ReconcStsCode, SpBillId, PayRefId, BillCtrNum, PaidAmt, CCy, TrxDtTm, CtrAccNum, UsdPayChnl, DptCellNum, DptName, DptNameEmailAddr, Remarks, ReconcRsv1,ReconcRsv2,ReconcRsv3) VALUES ('$value1','$value2','$value3','$value4','$value5','$value6','$value7','$value8','$value9','$value10','$value11','$value12','$value13','$value14','$value15','$value16','$value17','$value18','$value19')";
        mysql_query($sql);
        return true;

    }

    
}
