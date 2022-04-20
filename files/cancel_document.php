<?php 
    $function_name = "";
    if($_POST['request'] == "cancel_purchase_requisition"){
        $function_name = "cancelPr";
    }else if($_GET['request'] == "to_cancel_lpo"){
        $function_name = "cancelLpo";
    }
?>

<textarea placeholder="Enter reason for cancellation" id="reason_for_cancellation" cols="30" rows="5"></textarea>
<br><br>
<a href="#" onclick="<?=$function_name?>()" class="art-button-green">SUBMIT</a>