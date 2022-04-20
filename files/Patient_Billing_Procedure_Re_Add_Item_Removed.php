<?php
	session_start();
	include("./includes/connection.php");
	$temp = 1;
	$total = 0;

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = 0;
	}

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = '';
	}


	if(isset($_GET['Sub_Department_ID'])){
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}else{
		$Sub_Department_ID = '';
	}


	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}

	if($Payment_Item_Cache_List_ID != 0){
		$update = mysqli_query($conn,"update tbl_item_list_cache set status = 'active' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	}
?>
    <table width="100%">
        <tr>
            <td style="text-align: center;" width="5%"><b>SN</b></td>
            <td><b>ITEM NAME</b></td>
            <td style="text-align:right;" width=8%><b>PRICE</b></td>
            <td style="text-align: center;" width=8%><b>QUANTITY</b></td>
            <td style="text-align: right  ;" width=8%><b>SUB TOTAL</b></td>
        </tr>
<?php
    $select_Transaction_Items_Active = mysqli_query($conn,"select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, 
                                                    ilc.Payment_Cache_ID, ilc.Edited_Quantity
                                                    from tbl_item_list_cache ilc, tbl_Items its
                                                    where ilc.item_id = its.item_id and
                                                    ilc.status = 'removed' and
                                                    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                                    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                                    ilc.Transaction_Type = '$Transaction_Type' and
                                                    ilc.Check_In_Type = 'Procedure'") or die(mysqli_error($conn));

    $no = mysqli_num_rows($select_Transaction_Items_Active);

    if($no > 0){
        while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
            if($row['Edited_Quantity'] == 0){  
                $Quantity = $row['Quantity'];
            }else{
                $Quantity = $row['Edited_Quantity'];
            }
            if($Quantity < 1){ $Control = 'no'; }
            if($row['Price'] <= 0){ $Control = 'no'; }

            $status = $row['status'];
            echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
            echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
            echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
            //echo "<td><input type='hidden' name='item_list_id[]' value='".$row['Payment_Item_Cache_List_ID']."' /><input type='text' name='discount[]' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";
        
            echo "<td style='text-align:right;'>";
            echo "<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(".$row['Payment_Item_Cache_List_ID'].",this.value)' style='text-align: center;' size=13>";            
            echo'</td>';
            echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";    
            if($no > 0){
        ?>
            <td style="text-align: center;" width="4%"><input type="button" value="Re-Add" type="button" onclick="Re_Add_Item('<?php echo $row['Product_Name']; ?>',<?php echo $row["Payment_Item_Cache_List_ID"]; ?>)"></td>
        <?php
            }
            $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
            $temp++;
            echo "</tr>";
        }
    }
?>
    <tr>
        <td colspan="4" style="text-align: right;"><b>GRAND TOTAL</b></td>
        <td><input type="text" readonly="readonly" style="text-align: right;" value="<?php echo number_format($total); ?>"></td>
    </tr>
</table>