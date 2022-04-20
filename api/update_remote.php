<?php
require_once realpath(dirname(__FILE__) . "/../") . '\includes\connect_epay.php';

if (isset($_GET['Payment_Code'])) {
    $Payment_Code = $_GET['Payment_Code'];
} else {
    $Payment_Code = '';
}

$updateTransStatus="";
if (isset($_GET['src']) && $_GET['src']=='manual') {
   $updateTransStatus=",Transaction_Status = 'Completed'";
}
$update = mysql_query("update tbl_bank_transaction_cache set Process_Status = 'Completed' $updateTransStatus where Payment_Code = '$Payment_Code'") or die(mysql_error());
if ($update) {
    header("Content-type: text/xml");
    ?>
    <COMMAND>
        <STATUS>300</STATUS>
    </COMMAND>
    <?php
} else {
    header("Content-type: text/xml");
    ?>
    <COMMAND>
        <STATUS>301</STATUS>
    </COMMAND>        
    <?php
}
?>