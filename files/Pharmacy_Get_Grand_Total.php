<?php
	session_start();
	include("./includes/connection.php");
	
	$Grand_Total = 0;
	$Sub_Total = 0;

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = 0;
	}
	
	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}

	if(isset($_SESSION['Pharmacy_ID'])){
		$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	}else{
		$Sub_Department_ID = 0;
	}

	if($Transaction_Type != '' && $Transaction_Type != null && $Payment_Cache_ID != null && $Payment_Cache_ID != 0 && $Sub_Department_ID != 0){
		$select_Transaction_Items_Active = mysqli_query($conn,"
					select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
					from tbl_item_list_cache ilc, tbl_items its
					where ilc.item_id = its.item_id and
					ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
					ilc.Transaction_Type = '$Transaction_Type' and
					
					ilc.status = 'active' and ilc.Check_In_Type='Pharmacy'") or die(mysqli_query($conn,));
		$num = mysqli_num_rows($select_Transaction_Items_Active);
		if($num > 0){
                    //echo "jjjjj";
			while ($data = mysqli_fetch_array($select_Transaction_Items_Active)) {
				$Price = $data['Price'];
				$Discount = $data['Discount'];
				$Edited_Quantity = $data['Edited_Quantity'];
				$Quantity = $data['Quantity'];

				if($Edited_Quantity != 0){
					$Temp_Quantity = $Edited_Quantity;
				}else{
					$Temp_Quantity = $Quantity;
				}

				$Sub_Total = ($Price - $Discount) * $Temp_Quantity;
				$Grand_Total = $Grand_Total + $Sub_Total;
				$Sub_Total = 0;

				//echo number_format($Grand_Total);
				
			}
		}else{
			$Grand_Total = 0;
		}
	}else{
		$Grand_Total = 0;
	}
	echo $dataAmount= "<td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($Grand_Total)."</b></td>";
?>
<input type="text"hidden="hidden" id="total_txt" value="<?php echo $Grand_Total; ?>"/>