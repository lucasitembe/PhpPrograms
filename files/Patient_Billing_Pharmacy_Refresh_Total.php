<?php
	session_start();
	include("./includes/connection.php");
	$total = 0;

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = '';
	}

	if(isset($_SESSION['Pharmacy_ID'])){
		$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	}else{
		$Sub_Department_ID = 0;
	}

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}
    
    $select = mysqli_query($conn,"select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
    						from tbl_item_list_cache ilc, tbl_items its
                            where ilc.item_id = its.item_id and
                            ilc.status = 'active' and
                            ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                            ilc.Sub_Department_ID = '$Sub_Department_ID' and
                            ilc.Transaction_Type = '$Transaction_Type' and
                            ilc.Check_In_Type = 'Pharmacy'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
    if($no > 0){
        while($row = mysqli_fetch_array($select)){
        	if($row['Edited_Quantity'] == 0){  
                $Quantity = $row['Quantity'];
            }else{
                $Quantity = $row['Edited_Quantity'];
            }
            $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
        }
    }
        ?>
            <input type="text"hidden="hidden" id="total_txt" value="<?php echo $total; ?>"/>
            <?php
	echo "<td colspan=8 style='text-align: right;'><b> TOTAL : ".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)).'  '.$_SESSION['hospcurrency']['currency_code'].'&nbsp;&nbsp;'."</b>";
	//check items removed
	$removed = mysqli_query($conn,"select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
    						from tbl_item_list_cache ilc, tbl_items its
                            where ilc.item_id = its.item_id and
                            ilc.status = 'removed' and
                            ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                            ilc.Sub_Department_ID = '$Sub_Department_ID' and
                            ilc.Transaction_Type = '$Transaction_Type' and
                            ilc.Check_In_Type = 'Pharmacy'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($removed);
	if($num > 0){
		echo "<button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items</button>";
	}
	echo "</td>";
?>