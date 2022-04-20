<?php
	session_start();
	include("./includes/connection.php");

	if (!isset($_SESSION['userinfo'])) {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}
	if (isset($_SESSION['userinfo'])) {
	    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
	        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
	            header("Location: ./index.php?InvalidPrivilege=yes");
	        } else {
	            //@session_start();
	            if (!isset($_SESSION['supervisor'])) {
	                header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
	            }
	        }
	    } else {
	        header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	} else {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	//get employee id
	if (isset($_SESSION['userinfo']['Employee_ID'])) {
	    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	} else {
	    $Employee_ID = '0';
	}
///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////
	$select = mysqli_query($conn,"select Amount from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
        echo '<div style="width:100%;text-align:center;">';
	if($num > 0){
            if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
         if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
             echo '<input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;';
              echo " <div style='float:left;width: 40%'><input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'></div>";  
             
            } }
            if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
            	echo '<div style="float:right;width: 40%"><input type="button" name="Make_Payment" id="Make_Payment" value="MAKE PAYMENTS" class="art-button-green" onclick="Make_Payments();"></div>';
        	}
	  
        } else {
            if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                    echo '<input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="Make_Payment_Warning()">&nbsp;&nbsp;';
            echo "<div style='float:left;width: 40%'><input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Make_Payment_Warning()'></div>";
            } }
            if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
           	 	echo '<div style="float:right;width: 40%"><input type="button" name="Make_Payment" id="Make_Payment" value="MAKE PAYMENTS" class="art-button-green" onclick="Make_Payment_Warning();"></div>';
       		}
	}
        echo '</div>';
        
        ?>