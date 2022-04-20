<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = '';
	}

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_SESSION['Pharmacy_ID'])){
		$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	}else{
		$Sub_Department_ID = 0;
	}

	//delete all data from tbl_change_medication_location_cache table
	mysqli_query($conn,"delete from tbl_change_medication_location_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	//prepare pharmacy list
	$str = '<select name="Location" id="Location">';
	$prepare = mysqli_query($conn,"select Sub_Department_Name, sdep.Sub_Department_ID from
							tbl_department dep, tbl_sub_department sdep
							where dep.department_id = sdep.department_id and
							Department_Location = 'Pharmacy'") or die(mysqli_error($conn));
	$no_pre = mysqli_num_rows($prepare);
	if($no_pre > 0){
		while ($data = mysqli_fetch_array($prepare)) {
			if($Sub_Department_ID == $data['Sub_Department_ID']){
				$str .= "<option value='".$data['Sub_Department_ID']."' selected='selected'>".$data['Sub_Department_Name']."</option>";
			}else{
				$str .= "<option value='".$data['Sub_Department_ID']."'>".$data['Sub_Department_Name']."</option>";
			}
		}
	}else{

	}
	$str .= '</select>';
?>
<fieldset style='overflow-y: scroll; height: 180px; background-color: white;' id='Items_Fieldset'>
<?php
	$select = mysqli_query($conn,"select ilc.Item_ID, ilc.Doctor_Comment, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Quantity, ilc.Edited_Quantity
							from tbl_item_list_cache ilc, tbl_items its
							where ilc.item_id = its.item_id and
							ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
							ilc.Transaction_Type = '$Transaction_Type' and
							ilc.Check_In_Type = 'Pharmacy' and
							ilc.status = 'active'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		echo "<table width='100%'>";
		echo "<tr>
					<td width='4%' style='text-align: center;'><b>Sn</b></td>
					<td><b>Medication Name</b></td>
					<td width='10%' style='text-align: center;'><b>Quantity</b></td>
					<td width='10%' style='text-align: center;'><b>Balance</b></td>
					<td width='15%'><b>Pharmacy Name</b></td>
				</tr>";
		echo "<tr><td colspan='5'><hr></td></tr>";
		while ($data = mysqli_fetch_array($select)) {
			$Item_ID = $data['Item_ID'];
			if($data['Edited_Quantity'] > 0){
				$Qty = $data['Edited_Quantity'];
			}else{
				$Qty = $data['Quantity'];
			}
			//get item balance
			$get = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($get);
			if($no > 0){
				while ($row = mysqli_fetch_array($get)) {
					$Item_Balance = $row['Item_Balance'];
				}
			}else{
				mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
				$Item_Balance = 0;
			}
?>
			<tr>
				<td><input type="text" value="<?php echo ++$temp; ?>" readonly="readonly" style='text-align: center;'></td>
				<td><input type="text" value="<?php echo $data['Product_Name']; ?>" readonly="readonly"></td>
				<td><input type="text" value="<?php echo $Qty; ?>" readonly="readonly" style='text-align: center;'></td>
				<td><input type="text" value="<?php echo $Item_Balance; ?>" readonly="readonly" style='text-align: center;'></td>
				<td>
					<select name="Dep_<?php echo $temp; ?>" id="Dep_<?php echo $temp; ?>" onchange="Change_Medication_Get_Values(<?php echo $data['Payment_Item_Cache_List_ID']; ?>,<?php echo $temp; ?>)">
			<?php
				$prepare = mysqli_query($conn,"select Sub_Department_Name, sdep.Sub_Department_ID from
										tbl_department dep, tbl_sub_department sdep
										where dep.department_id = sdep.department_id and
										Department_Location = 'Pharmacy'") or die(mysqli_error($conn));
				$no_pre = mysqli_num_rows($prepare);
				if($no_pre > 0){
					while ($data = mysqli_fetch_array($prepare)) {
						if($Sub_Department_ID == $data['Sub_Department_ID']){
							echo "<option value='".$data['Sub_Department_ID']."' selected='selected'>".$data['Sub_Department_Name']."</option>";
						}else{
							echo "<option value='".$data['Sub_Department_ID']."'>".$data['Sub_Department_Name']."</option>";
						}
					}
				}else{

				}
			?>
					</select>
				</td>
			</tr>
<?php
		}
		echo "</table>";
	}
?>
</fieldset>
<table width="100%">
	<tr>
		<td style="text-align: right;">
			<input type="button" name="Submit_Changes" id="Submit_Changes" value="UPDATE" class="art-button-green" onclick="Change_Medication_Location_Confirm()">
			<input type="button" name="Calnce_Process" id="Calnce_Process" value="CANCEL" class="art-button-green" onclick="Cancel_Change_Medication()">
		</td>
	</tr>
</table>