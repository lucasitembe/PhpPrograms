<?php
	include("./includes/connection.php");

	if(isset($_GET['Return_Type'])){
		$Return_Type = $_GET['Return_Type'];
	}else{
		$Return_Type = '';
	}
	echo '<select name="Location_ID" id="Location_ID">';
	echo "<option selected='selected'></option>";
	if($Return_Type == 'Outward'){
		//get all suppliers
		$select = mysqli_query($conn,"select Supplier_Name, Supplier_ID from tbl_supplier order by Supplier_Name") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
?>
			<option value="<?php echo $data['Supplier_ID']; ?>"><?php echo $data['Supplier_Name']; ?></option>
<?php
			}
		}
	}else if($Return_Type == 'Inward'){
		$select = mysqli_query($conn,"select Sub_Department_ID, Sub_Department_Name from
								tbl_department dep, tbl_sub_department sdep
								where dep.department_id = sdep.department_id and
								Department_Location = 'Storage And Supply'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
?>
 				<option value="<?php echo $data['Sub_Department_ID']; ?>"><?php echo $data['Sub_Department_Name']; ?></option>
<?php
			}
		}
	}
	echo "</select>";
?>