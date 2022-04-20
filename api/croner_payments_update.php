<?php
 header("Content-type: text/xml");
require_once realpath(dirname(__FILE__) . "/../") . '/includes/connect_epay.php';

$status_code = encrypt('200');

//echo $status_code;exit;

$select = mysql_query("select * from tbl_payments where Status_Code = '$status_code' limit 50") or die(mysql_error());
$num = mysql_num_rows($select);
if ($num > 0) {
  
    ?>
    <DATASET>
        <?php
        while ($data = mysql_fetch_array($select)) {
            ?>
            <DATA>
                <REGISTRATION_ID><?php echo decrypt(trim($data['Registration_ID'])); ?></REGISTRATION_ID>
                <PATIENT_NAME><?php echo decrypt(trim($data['Patient_Name'])); ?></PATIENT_NAME>
                <AMOUNT_PAID><?php echo decrypt($data['Amount_Paid']); ?></AMOUNT_PAID>
                <PAYMENT_CODE><?php echo decrypt($data['Payment_Code']); ?></PAYMENT_CODE>
                <PAYMENT_RECEIPT><?php echo decrypt($data['Payment_Receipt']); ?></PAYMENT_RECEIPT>
                <TRANSACTION_REF><?php echo decrypt($data['Transaction_Ref']); ?></TRANSACTION_REF>
                <TRANSACTION_DATE><?php echo decrypt($data['Transaction_Date']); ?></TRANSACTION_DATE>
                <TERMINAL_ID><?php echo decrypt($data['Terminal_ID']); ?></TERMINAL_ID>
                <MERCHANT_ID><?php echo decrypt($data['Merchant_ID']); ?></MERCHANT_ID>
                <BATCH_NO><?php echo decrypt($data['Batch_No']); ?></BATCH_NO>
                <AUTH_NO><?php echo decrypt($data['Auth_No']); ?></AUTH_NO>
                <TRANS_TYPE><?php echo decrypt($data['trans_type']); ?></TRANS_TYPE>
                <PAYMENT_ID><?php echo $data['Payment_ID']; ?></PAYMENT_ID>
                <CONTROLER>200</CONTROLER>
            </DATA>
            <?php
        }
        ?>	
    </DATASET>
    <?php
} else {
    header("Content-type: text/xml");
    ?>
    <DATASET>
        <DATA>
            <REGISTRATION_ID>NOT FOUND</REGISTRATION_ID>
            <PATIENT_NAME>NOT FOUND</PATIENT_NAME>
            <AMOUNT_PAID>NOT FOUND</AMOUNT_PAID>
            <PAYMENT_CODE>NOT FOUND</PAYMENT_CODE>
            <PAYMENT_RECEIPT>NOT FOUND</PAYMENT_RECEIPT>
            <TRANSACTION_REF>NOT FOUND</TRANSACTION_REF>
            <TRANSACTION_DATE>NOT FOUND</TRANSACTION_DATE>
            <TERMINAL_ID>NOT FOUND</TERMINAL_ID>
            <MERCHANT_ID>NOT FOUND</MERCHANT_ID>
            <BATCH_NO>NOT FOUND</BATCH_NO>
            <AUTH_NO>NOT FOUND</AUTH_NO>
            <TRANS_TYPE>NOT FOUND</TRANS_TYPE>
            <PAYMENT_ID>NOT FOUND</PAYMENT_ID>
            <CONTROLER>300</CONTROLER>
        </DATA>
    </DATASET>
    <?php
}
?>
