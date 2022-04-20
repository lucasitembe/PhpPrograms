<?php
@session_start();
include("./includes/connection.php");


if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

//check if exemption sponsor
$select = mysqli_query($conn,"select Exemption from tbl_sponsor sp, tbl_patient_registration pr where
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$nm = mysqli_num_rows($select);
if ($nm > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Exemption = strtolower($data['Exemption']);
    }
} else {
    $Exemption = 'no';
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

$select_Transaction_Items = mysqli_query($conn, "SELECT Billing_Type FROM tbl_payment_cache WHERE Employee_ID = '$Employee_ID' and  Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));

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
        if (strtolower($Billing_Type) == 'inpatient cash') {
            if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
                if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                    if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                    ?>
                    <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                    <input type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" value="Create ePayment Bill" class="art-button-green" onclick="Pay_Via_Mobile_Function('')">&nbsp;&nbsp;
                    <?php
                } }
                if (strtolower($_SESSION['systeminfo']['Display_Send_To_Cashier_Button']) == 'yes') { //this setting allows system to display both (send to cashier) and (make payment) buttons
                   if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                    echo '<button class="art-button-green" type="button" onclick="Make_Payment(); clearContent();">MAKE PAYMENT & DISPENSE SERVICE</button>';
                   }
                    
                   }
                ?>
                <button class="art-button-green hide" type="button" onclick="Check_Patients_Bill('true');
                                            clearContent();">ADD SERVICES TO THE BILL</button>
                <button class="art-button-green" type="button" onclick="Send_To_Cashier(); clearContent();">ADD SERVICES TO THE BILL</button>
                <?php
            } else {
                ?>
                <button class="art-button-green hide" type="button" onclick="Check_Patients_Bill(); clearContent();">ADD SERVICES TO THE BILL</button>
                <?php
            }
        } else {
            if ($Exemption == 'yes') {
                ?>
                <input type="button" name="Send_To_Approval" id="Send_To_Approval" class="art-button-green hide" value="SEND TO APPROVAL" onclick="Approval_Medication()">
                <?php
            } else {
                ?>
                <button class="art-button-green" type="button" onclick="Check_Patients_Bill(); clearContent();">ADD SERVICES TO THE BILL</button>
                <?php
            }
        }
    }
    ?>
    <button class="art-button-green" type="button" onclick="openItemDialog(); clearContent();">ADD SERVICE</button>
</td>