<?php
	session_start();
	include("./includes/connection.php");
	$Control = 'yes';

	if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
            if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            } else {
                @session_start();
                if (!isset($_SESSION['supervisor'])) {
                    header("Location: ./departmentalsupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                }
            }
        } else {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = 0;
	}

	$select = mysqli_query($conn,"select ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount
                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                ilc.Transaction_Type = 'Cash' and
                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                pc.Billing_Type = 'Outpatient Cash' and
                                ilc.ePayment_Status = 'pending' order by ilc.Check_In_Type") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	$Qty = 0;
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			if($data['Edited_Quantity']){
				$Qty = $data['Edited_Quantity'];
			}else{
				$Qty = $data['Quantity'];
			}

			if($Qty < 1){
				$Control = 'no';
			}

			if($data['Price'] < 1){
				$Control = 'no';
			}
		}
	}
	echo $Control;
?>