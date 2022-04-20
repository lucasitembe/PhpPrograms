<?php
//require"../includes/connection.php";
include_once 'Signature.php';

class BillReconc
{
    //__construct
    public $host = "localhost";
    public $username = "root";
    public $db = "ehms_database_new";
    public $password = "ehms2gpitg2014";

    protected $signatureHandler=null;
    public function __construct()
    {
        $this->signatureHandler = new Signature();
    }

    function outGoingReconsileRequest()
    {
        if(isset($_POST['TnxDt'])){
            $TnxDt=$_POST['TnxDt'];
         }
         if(isset($_POST['ReconcOpt'])){
            $ReconcOpt=$_POST['ReconcOpt'];
         }
        $formRequest=[];
        $formRequest['SpReconcReqId']=0;
        $formRequest['SpCode']='SP186';
        $formRequest['SpSysId']="LAMANA001";
        $formRequest['TnxDt']=$TnxDt;
        $formRequest['ReconcOpt']=$ReconcOpt;
        $SpReconcReqId = $this->creategepgSpReconcReq($formRequest);
        if ($SpReconcReqId != 0) {
            $formRequest['SpReconcReqId'] = $SpReconcReqId;
        }
        $ReconcStsCode=$this->gepgSpReconcReq($formRequest);
        return $SpReconcReqId;
    }

    function creategepgSpReconcReq($formRequest)
    {
        $conn = mysql_connect($this->host, $this->username, $this->password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($this->db);
        $sql = "INSERT INTO tbl_gepgSpReconcReq (SpCode, SpSysId,TnxDt, ReconcOpt) VALUES ('".$formRequest['SpCode']."','" . $formRequest['SpSysId'] . "','" . $formRequest['TnxDt'] . "','" . $formRequest['ReconcOpt'] . "')";
        mysql_query($sql);
        $formRequest['SpReconcReqId'] = mysql_insert_id();
        return $formRequest['SpReconcReqId'];
    }

    function gepgSpReconcReq($formRequest)
    {
        $xmlGepgReqContent = $this->xmlgepgSpReconcReqContent($formRequest);
        $generatedSignature = $this->signatureHandler->createSignature($xmlGepgReqContent, 'amana1234', 'amana', '/var/www/html/ehms/files/gepg_processing_payfolder/keys/amanaprivate.pfx');
        $requestToGepg = "<Gepg>" .$xmlGepgReqContent."<gepgSignature>".$generatedSignature."</gepgSignature></Gepg>";
        $ch = curl_init("http://154.118.230.202/api/reconciliations/sig_sp_qrequest");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestToGepg);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/xml',
                'Gepg-Com: default.sp.in',
                'Gepg-Code:SP186',
                'Content-Length: ' . strlen($requestToGepg))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $resultCurlPost = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $this->updategepgSpReconcReqByStatusCode($formRequest['SpReconcReqId'], $this->getStringValueFromXmlTags($resultCurlPost, 'ReconcStsCode'));
        return $this->getStringValueFromXmlTags($resultCurlPost, 'ReconcStsCode');
        //return $formRequest['SpReconcReqId'];
    }

    function xmlgepgSpReconcReqContent($request)
    {
        $content = "<gepgSpReconcReq>
            <SpReconcReqId>".$request['SpReconcReqId']."</SpReconcReqId>
            <SpCode>".$request['SpCode']."</SpCode>
            <SpSysId>".$request['SpSysId']."</SpSysId>
            <TnxDt>".$request['TnxDt']."</TnxDt>
            <ReconcOpt>".$request['ReconcOpt']."</ReconcOpt>
        </gepgSpReconcReq>";
        return $content;
    }

    function updategepgSpReconcReqByStatusCode($SpReconcReqId, $ReconcStsCode)
    {
        $conn = mysql_connect($this->host, $this->username, $this->password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($this->db);
        $sql = "update tbl_gepgSpReconcReq set ReconcStsCode='" . $ReconcStsCode . "' where SpReconcReqId='" . $SpReconcReqId . "'";
        mysql_query($sql);
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
