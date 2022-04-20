<?php
 header("Content-type: text/xml");
     require_once realpath(dirname(__FILE__)."/../").'/includes/connect_epay.php';
/**
     * senging transactions to main server 
     * STATUS CODE
     * 200 -> data found
     * 300 -> data not found
     * */
        
    //get datails
	
	$filter='';
	if(isset($_GET['trans_id']) && !empty($_GET['trans_id']) && is_numeric(trim($_GET['trans_id']))){
		$filter=" AND Transaction_ID='".trim($_GET['trans_id'])."'";
	}
	
	//echo "select * from tbl_bank_transaction_cache where Transaction_Status = 'Completed' and Process_Status = 'pending' $filter ORDER BY Transaction_ID DESC limit 50";exit;
	
    $select = mysql_query("select * from tbl_bank_transaction_cache where Transaction_Status = 'Completed' and Process_Status = 'pending' $filter ORDER BY Transaction_ID DESC limit 50") or die(mysql_error());
    $num = mysql_num_rows($select);
    if($num > 0){
?>
        <DATASET>
<?php
            while($data = mysql_fetch_array($select)){
                $Payment_Code = $data['Payment_Code'];
                //get Transaction_Ref
                $slct = mysql_query("select Transaction_Ref, Transaction_Date from tbl_payments where Payment_Code = '$Payment_Code'") or die(mysql_error());
                $no = mysql_num_rows($slct);
                if($no > 0){
                    while ($row = mysql_fetch_array($slct)) {
                        $Transaction_Ref = decrypt($row['Transaction_Ref']);
                        $Transaction_Date = decrypt($row['Transaction_Date']);
                    }
                }else{
                    $Transaction_Ref = 'Missed-Trans';
                    $Transaction_Date = '0000-00-00 00:00';
                }
?>              <DATA>
                    <PATIENTNAME><?php echo $data['P_Name']; ?></PATIENTNAME>
                    <AMOUNTPAID><?php echo $data['Amount_Required']; ?></AMOUNTPAID>
                    <PAYMENTCODE><?php echo $data['Payment_Code']; ?></PAYMENTCODE>
                    <REGISTRATION_ID><?php echo $data['Registration_ID']; ?></REGISTRATION_ID>
                    <TRANSACTION_REF><?php echo $Transaction_Ref; ?></TRANSACTION_REF>
                    <TRANSACTION_DATE><?php echo $Transaction_Date; ?></TRANSACTION_DATE>
                    <EMPLOYEE_ID><?php echo $data['Employee_ID']; ?></EMPLOYEE_ID>
    				<STATUS>200</STATUS>
                </DATA>
<?php
            }
?>
        </DATASET>
<?php 
    }else{
		header("Content-type: text/xml");
?>
        <DATASET>
            <DATA>
                <PATIENTNAME>NOT FOUND</PATIENTNAME>
                <AMOUNTPAID>NOT FOUND</AMOUNTPAID>
                <PAYMENTCODE>NOT FOUND</PAYMENTCODE>
                <REGISTRATION_ID>NOT FOUND</REGISTRATION_ID>
                <TRANSACTION_REF>NOT FOUND</TRANSACTION_REF>
                <TRANSACTION_DATE>NOT FOUND</TRANSACTION_DATE>
                <EMPLOYEE_ID>NOT FOUND</EMPLOYEE_ID>
				<STATUS>300</STATUS>
            </DATA>
        </DATASET>
<?php
    }
?>
