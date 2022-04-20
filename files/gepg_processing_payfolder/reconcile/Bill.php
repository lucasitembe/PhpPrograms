<?php
include_once 'Signature.php';

class Bill
{
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

    header("Location: http://localhost/amanahosptal/view_bill.php?billId=".$billId);
    exit();

    }

    function createBill($formRequest)
    {
        $host = "localhost";
        $username = "root";
        $db = "gepg";
        $password = "ehms2gpitg2014";
        $conn = new mysqli($host, $username, $password, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO bill  (BillDescription,BilledAmount,BillExpiryDate,BillGeneratedDate,CustomerEmailAddress,CustomerPayerName,CustomerPhoneNumber,BillPayOption,SubServiceProviderCode,ServiceProviderCode,GeneratedBy,BillEquivalentAmount,CollectionCenterCode,BillControlNumber,CurrencyCode,ExchangeRateAmount,MiscellaneousAmount,StatusCode) VALUES ('" . $formRequest['BillDescription'] . "'," . $formRequest['BilledAmount'] . ",'" . $formRequest['BillExpiryDate'] . "','" . $formRequest['BillGeneratedDate'] . "','" . $formRequest['CustomerEmailAddress'] . "','" . $formRequest['CustomerPhoneNumber'] . "','" . $formRequest['CustomerPayerName'] . "'," . $formRequest['BillPayOption'] . ",'" . $formRequest['SubServiceProviderCode'] . "','" . $formRequest['ServiceProviderCode'] . "','" . $formRequest['GeneratedBy'] . "'," . $formRequest['BillEquivalentAmount'] . ",'" . $formRequest['CollectionCenterCode'] . "','" . $formRequest['BillControlNumber'] . "','" . $formRequest['CurrencyCode'] . "'," . $formRequest['ExchangeRateAmount'] . ",0.00,'')";
        if ($conn->query($sql) === TRUE) {
            $formRequest['BillId'] = $conn->insert_id;
        }
        $conn->close();
        return $formRequest['BillId'];

    }
    function gepgBillSubReq($formRequest)
    {
        $xmlGepgReqContent = $this->xmlGepgBillReqContent($formRequest);
        $generatedSignature = $this->signatureHandler->createSignature($xmlGepgReqContent, 'passpass', 'gepgclient', '/var/www/html/amanahosptal/keys/gepgclientprivatekey.pfx');
        $requestToGepg = "<Gepg>" .$xmlGepgReqContent."<gepgSignature>".$generatedSignature."</gepgSignature></Gepg>";
        $ch = curl_init("http://154.118.230.18/api/bill/sigqrequest");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestToGepg);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/xml',
                'Gepg-Com: default.sp.in',
                'Gepg-Code:SP607',
                'Content-Length: ' . strlen($requestToGepg))
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
        $host = "localhost";
        $username = "root";
        $db = "gepg";
        $password = "ehms2gpitg2014";
        $conn = new mysqli($host, $username, $password, $db);
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
        $billId = $this->getStringValueFromXmlTag($responseBody, 'BillId');
        $payCntrNum = $this->getStringValueFromXmlTag($responseBody, 'PayCntrNum');
        $trxStsCode = $this->getStringValueFromXmlTag($responseBody, 'TrxStsCode');
        $extractedSignature = $this->getStringValueFromXmlTag($responseBody, "gepgSignature");
        $extractedString =  $this->getDataStringFromXml($responseBody, "gepgBillSubResp");
        echo $this->updateBillByBillControlNumber($billId, $payCntrNum, $trxStsCode);

    //    if ($this->signatureHandler->verifySignature($extractedSignature, $extractedString,'passpass','gepg', '/var/www/html/amanahosptal/keys/gepgclientpubliccertificate.pfx')) {
    //        echo 'Ok';
    //    } else {
    //        echo "Invalid Signature";
    //    }

    }

    function getStringValueFromXmlTag($inputContent, $tagValue)
    {
        $sigStartPosition = strpos($inputContent, $tagValue);
        $sigEndPosition = strrpos($inputContent, $tagValue);
        $gatValueData = substr($inputContent, $sigStartPosition + strlen($tagValue) + 1, $sigEndPosition - $sigStartPosition - strlen($tagValue) - 3);

        return $gatValueData;
    }

    function updateBillByBillControlNumber($billId, $payCntrNum, $trxStsCode)
    {
        $responseContent = null;
        $host = "localhost";
        $username = "root";
        $db = "gepg";
        $password = "ehms2gpitg2014";
        $conn = new mysqli($host, $username, $password, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "update bill set BillControlNumber=" . $payCntrNum . ",StatusCode=" . $trxStsCode . " where BillId=" . $billId . "";
        if ($conn->query($sql) === TRUE) {
            $responseContent = "<gepgBillSubRespAck><TrxStsCode>7101</TrxStsCode></gepgBillSubRespAck>";
            $generatedSignature = $this->signatureHandler->createSignature($responseContent, 'passpass', 'gepgclient', '/var/www/html/amanahosptal/keys/gepgclientprivatekey.pfx');
            $response = "<Gepg>" . $responseContent . "<gepgSignature>" . $generatedSignature . "</gepgSignature></Gepg>";
            return $response;
        }
        $conn->close();
        return $responseContent;

    }
    function getDataStringFromXml($inputContent,$xmlTag){
        $data ="";
        $startPosition = strpos($inputContent, $xmlTag);
        $endPosition = strrpos($inputContent, $xmlTag);
        $data=substr($inputContent,$startPosition - 1,$endPosition + strlen($xmlTag)+2 - $startPosition);
        return $data;
    }
}
