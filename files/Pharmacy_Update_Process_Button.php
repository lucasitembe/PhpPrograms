<?php
@session_start();
include("./includes/connection.php");


if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

//check if approval needed
$check = mysqli_query($conn,"select Require_Document_To_Sign_At_receiption from tbl_sponsor sp, tbl_patient_registration pr where
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($check);
if ($num > 0) {
    while ($row = mysqli_fetch_array($check)) {
        $Require_Document_To_Sign_At_receiption = strtolower($row['Require_Document_To_Sign_At_receiption']);
    }
} else {
    $Require_Document_To_Sign_At_receiption = 'not mandatory';
}


if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

$select_Transaction_Items = mysqli_query($conn,
        "select Billing_Type
				    from tbl_pharmacy_items_list_cache alc
				    where alc.Employee_ID = '$Employee_ID' and
				    Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));

$no_of_items = mysqli_num_rows($select_Transaction_Items);
///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////
?>
<td style='text-align: right;'>
    <?php
    if ($no_of_items > 0) {
        while ($data = mysqli_fetch_array($select_Transaction_Items)) {
            $Billing_Type = $data['Billing_Type'];
        }
        if(isset($_GET['Billing_Type'])){
            $Billing_Type=$_GET['Billing_Type'];
        }
        if (isset($_SESSION['systeminfo']['Departmental_Collection']) && strtolower($_SESSION['systeminfo']['Departmental_Collection']) == 'yes') {
            if (strtolower($Billing_Type) == 'outpatient cash') {
                if (strtolower($_SESSION['systeminfo']['Display_Send_To_Cashier_Button']) == 'yes') { //this setting allows system to display both (send to cashier) and (make payment) buttons
                    echo '<button class="art-button-green" type="button" onclick="Send_To_Cashier(); clearContent();">SEND TO CASHIER</button>';
                }
                if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                    if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                    ?>
                    <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                    <input type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" value="Create ePayment Bill" class="art-button-green" onclick="Pay_Via_Mobile_Function('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                    <?php
                } }
                if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                echo '<button class="art-button-green" type="button" onclick="Make_Payment(); clearContent();">MAKE PAYMENT</button>';
                }
            } else {
                if (strtolower($Require_Document_To_Sign_At_receiption) == 'mandatory' && strtolower($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical']) == 'yes') {
                    echo '<input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="SEND TO APPROVAL" onclick="Send_To_Approval()">';
                } else {
                    echo '<button class="art-button-green" type="button" onclick="Bill_Patient(); clearContent();">BILL PATIENT</button>';
                }
            }
        } else {
            if (strtolower($Billing_Type) == 'outpatient cash') {
                echo '<button class="art-button-green" type="button" onclick="Send_To_Cashier(); clearContent();">SEND TO CASHIER</button>';
            } else {
                if (strtolower($Require_Document_To_Sign_At_receiption) == 'mandatory' && strtolower($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical']) == 'yes') {
                    echo '<input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="SEND TO APPROVAL" onclick="Send_To_Approval()">';
                } else {
                    echo '<button class="art-button-green" type="button" onclick="Bill_Patient(); clearContent();">BILL PATIENT</button>';
                }
            }
        }
    }
    ?>
    <button class="art-button-green" type="button" onclick="openItemDialog(); clearContent();">ADD MEDICATION</button>
</td>