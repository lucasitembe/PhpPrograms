<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}

	$Laboratory = '';
	$Pharmacy = '';

	$select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'post operative'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Payment_Cache_ID = $data['Payment_Cache_ID'];
		}
	}else{
		$Payment_Cache_ID = '';
	}

	if($Payment_Cache_ID != 0 && $Payment_Cache_ID != null && $Payment_Cache_ID != ''){
		$select = mysqli_query($conn,"select i.Product_Name, ilc.Check_In_Type, ilc.Doctor_Comment from tbl_item_list_cache ilc, tbl_items i where
								i.Item_ID = ilc.Item_ID and
								ilc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));

		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
				if(strtolower($row['Check_In_Type']) == 'pharmacy'){
					$Doctor_Comment =$row['Doctor_Comment'];
					$Pharmacy .= $row['Product_Name'].' ('.$Doctor_Comment.');  ';
				}else{
					$Laboratory .= $row['Product_Name'].';  ';
				}
			}
			if($Pharmacy != ''){
				echo "PHARMACEUTICAL : (".$Pharmacy.')                ';
			}else{
				echo "NO MEDICATION SELECTED";
			}
			
			if($Laboratory != ''){
				echo "INVESTIGATION : (".$Laboratory.')';
			}else{
				echo "NO INVESTIGATIONS SELECTED";
			}
		}else{
			echo "NO MEDICATION & INVESTIGATIONS SELECTED";
		}
	}else{
		echo "NO MEDICATION & INVESTIGATIONS SELECTED";
	}
?>