<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = '';
	}

	if(isset($_GET['Transfer_Type'])){
		$Transfer_Type = $_GET['Transfer_Type'];
	}else{
		$Transfer_Type = '';
	}

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = 0;
	}

	//get current patient destination
	$Clinic_Destination = 'no';
	$filter = '';
	$slct = mysqli_query($conn,"select Patient_Direction, Clinic_ID from tbl_patient_payment_item_list where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($slct);
	if($no > 0){
		while ($row = mysqli_fetch_array($slct)) {
			$Patient_Direction = strtolower($row['Patient_Direction']);
			$Clinic_ID = $row['Clinic_ID'];
		}
		if($Patient_Direction == 'direct to clinic' || $Patient_Direction == 'direct to clinic via nurse station'){
			$filter = "and Clinic_ID <> '$Clinic_ID'";
		}
	}

	if($Transfer_Type == 'Transfer To Doctor'){
		echo "Select Doctor&nbsp;&nbsp;&nbsp;";
		echo "<select id='Destination_ID'><option></option>";
		//get list of doctors
		$select = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'doctor' and Account_Status = 'active' and Employee_ID <> '$Employee_ID' order by employee_name") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
?>
				<option value="<?php echo $data['Employee_ID']; ?>"><?php echo $data['Employee_Name']; ?></option>
<?php
			}
		}
		echo "</select>";
	}else{
		echo "Select Clinic&nbsp;&nbsp;&nbsp;";
		echo "<select id='Destination_ID'><option></option>";
		$select = mysqli_query($conn,"select Clinic_Name, Clinic_ID from tbl_clinic where Clinic_Status = 'Available' $filter") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
?>
				<option value="<?php echo $data['Clinic_ID']; ?>"><?php echo $data['Clinic_Name']; ?></option>
<?php
			}
		}
		echo "</select>";
	}
?>