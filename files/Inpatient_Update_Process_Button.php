<?php
@session_start();
include("./includes/connection.php");
require_once './includes/ehms.function.inc.php';

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if (isset($_GET['Exemption'])) {
    $Exemption = strtolower($_GET['Exemption']);
} else {
    $Exemption = 'no';
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

$select_Transaction_Items = mysqli_query($conn,
        "select Billing_Type
				    from tbl_inpatient_items_list_cache alc
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
        if (strtolower($Billing_Type) == 'inpatient cash') {
            if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
                if (isset($_SESSION['systeminfo']['Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments']) && strtolower($_SESSION['systeminfo']['Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments']) == 'yes') {
                     if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                    ?>
                    <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;
                    <button class="art-button-green" type="button" onclick="Create_Patient_Bill_Verify();">CREATE PATIENT BILL</button>
                    <?php
                } }
                ?>
                <?php
                if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                    if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                    echo '<button class="art-button-green" type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" onclick="Pay_Via_Mobile_Function();">CREATE ePayment BILL</button>';
                        ?>
                        <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;    
                        <?php
                } }
                ?>    

                <button class="art-button-green" type="button" onclick="Save_Information_Confirm();">SAVE INFORMATION</button>
                <?php 
                 if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                ?>
                <button class="art-button-green" type="button" onclick="Make_Payment();">MAKE PAYMENT</button>
                <?php
            } } else {
                ?>                  <?php
                if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {

//                    echo '<button class="art-button-green" type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" onclick="Pay_Via_Mobile_Function();">CREATE ePayment BILL</button>';
                }
                ?>  
                <!--<button class="art-button-green" type="button" onclick="Make_Payment();">MAKE PAYMENT</button>-->
                <!-- <button class="art-button-green" type="button" onclick="Save_Information_Confirm();">SAVE INFORMATION</button> -->
                <button class="art-button-green" type="button" onclick="Save_Information_Confirm();">SAVE INFORMATION</button>
                <?php
            }
        } else {
            if ($Exemption == 'yes' && strtolower($_SESSION['systeminfo']['Allow_Cashier_To_Approve_Pharmaceutical']) == 'yes') {
                ?>
                <button class="art-button-green" type="button" onclick="Send_To_Approval_Confirm();">SEND TO APPROVAL</button>
                <?php
            } else {
                ?>   <button class="art-button-green" type="button" onclick="Save_Information_Confirm();">SAVE INFORMATION</button>
                <?php
            }
        }
    }
    ?>
    <button class="art-button-green" type="button" onclick="Validate_Type_Of_Check_In();">ADD ITEM</button>
</td>