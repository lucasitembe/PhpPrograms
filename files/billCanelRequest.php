<?php
include_once 'Signature.php';

class BillReconc
{
    //__construct
    public $host = "localhost";
    public $username = "root";
    public $db = "ehms_database";
    public $password = "ehms2gpitg2014";

    protected $signatureHandler=null;
    public function __construct()
    {
        $this->signatureHandler = new Signature();
    }

    function outGoingReconsileRequest()
    {
        if(isset($_POST['billID'])){
            $BillId=$_POST['billID'];
         }
         if(isset($_POST['employee'])){
            $employee=$_POST['employee'];
         }
        $formRequest=[];
        $formRequest['BillId']=$BillId;
        $formRequest['SpCode']='SP607';
        $formRequest['SpSysId']="TAMANA001";
        $formRequest['employee']=$employee;
        $BillId = $this->createBillCancelReq($formRequest);
        if ($SpReconcReqId != 0) {
            $formRequest['BillId'] = $BillId;
        }
        $BillId=$this->BillCancelReq($formRequest);
        return $BillId;
    }

    function createBillCancelReq($formRequest)
    {
        $conn = mysql_connect($this->host, $this->username, $this->password);
        if (!$conn) {
            die('Could not connect: ' . mysqli_error($conn));
        }
        mysql_select_db($this->db);
        $sql = "INSERT INTO tbl_gepgBillCanclReq (employee, SpCode,SpSysId, BillId) VALUES ('".$formRequest['employee']."','" . $formRequest['SpCode'] . "','" . $formRequest['SpSysId'] . "','" . $formRequest['BillId'] . "')";
        mysqli_query($conn,$sql);
        $formRequest['BillId'] = mysql_insert_id();
        return $formRequest['BillId'];

    }


    function BillCancelReq($formRequest)
    {
        $xmlGepgReqContent = $this->xmlBillCancelReqContent($formRequest);
        $generatedSignature = $this->signatureHandler->createSignature($xmlGepgReqContent, 'amana1234', 'amana', '/var/www/html/testehms/files/gepg_processing_payfolder/keys/amanaprivate.pfx');
        $requestToGepg = "<Gepg>" .$xmlGepgReqContent."<gepgSignature>".$generatedSignature."</gepgSignature></Gepg>";
        $ch = curl_init("http://154.118.230.18/api/bill/sigcancel_request");
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
        $this->updateBillCancelReqByStatusCode($formRequest['BillId'], $this->getStringValueFromXmlTags($resultCurlPost, 'TrxStsCode'),$this->getStringValueFromXmlTags($resultCurlPost, 'TrxSts'));
        return $this->getStringValueFromXmlTags($resultCurlPost, 'TrxStsCode');
    }

    function xmlBillCancelReqContent($request)
    {
        $content = "<gepgBillCanclReq>
            <SpCode>".$request['SpCode']."</SpCode>
            <SpSysId>".$request['SpSysId']."</SpSysId>
            <BillId>".$request['BillId']."</BillId>
        </gepgBillCanclReq>";
        return $content;
    }

    function updateBillCancelReqByStatusCode($BillId, $statusCode, $TrxSts)
    {
        $conn = mysql_connect($this->host, $this->username, $this->password);
        if (!$conn) {
            die('Could not connect: ' . mysqli_error($conn));
        }
        mysql_select_db($this->db);
        $sql = "update tbl_gepgBillCanclReq set TrxStsCode ='".$statusCode."',TrxSts='".$TrxSts."' where BillId='".$BillId."'";
        mysqli_query($conn,$sql);
    }

    function getStringValueFromXmlTags($inputContent, $tagValue){
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

    function getDataStringFromXml($inputContent,$xmlTag){
        $data ="";
        $startPosition = strpos($inputContent, $xmlTag);
        $endPosition = strrpos($inputContent, $xmlTag);
        $data=substr($inputContent,$startPosition - 1,$endPosition + strlen($xmlTag)+2 - $startPosition);
        return $data;
    }
}
$BillReconc = new BillReconc();
echo($BillReconc->outGoingReconsileRequest());