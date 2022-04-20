<?php
    @session_start();
    include("./includes/connection.php");
       
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    $select_Transaction_Items = mysqli_query($conn,
                 "select Billing_Type
                    from tbl_departmental_items_list_cache alc
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
        if($no_of_items > 0){
           while($data = mysqli_fetch_array($select_Transaction_Items)){
          $Billing_Type = $data['Billing_Type'];
           }
           if(strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash'){
            if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'){
                 if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
?>              <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;
                <button class="art-button-green" type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" onclick="Pay_Via_Mobile_Function();">CREATE ePayment BILL</button>
            <?php } } if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){ ?>
                <button class="art-button-green" type="button" onclick="Make_Payment();">MAKE PAYMENT</button>
<?php }//end of check make payment button visibility
           }else{
?>
                <button class="art-button-green" type="button" onclick="Save_Information();">SAVE INFORMATION</button>
<?php
               }
        }
?>
    <button class="art-button-green" type="button" onclick="Validate_Type_Of_Check_In();">ADD ITEM</button>
    </td>