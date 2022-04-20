<?php
	include("./includes/connection.php");
	$temp = 0;
	if(isset($_GET['Approval_Title'])){
		$Approval_Title = $_GET['Approval_Title'];
	}else{
		$Approval_Title = '';
	}

	if(isset($_GET['Employee_Name'])){
		$Employee_Name = $_GET['Employee_Name'];
	}else{
		$Employee_Name = '';
	}


	//get employees based on employee type
	$select = mysqli_query($conn,"select emp.Employee_Name, emp.Employee_Job_Code, emp.Employee_ID, emp.Employee_Title, emp.Employee_Type, dep.Department_Name from
							tbl_employee emp, tbl_department dep where
							emp.Department_ID = dep.Department_ID and
							Employee_Type = '$Approval_Title' and
							emp.Employee_Name like '%$Employee_Name%' and
							emp.Account_Status = 'active' order by Employee_Name") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	echo "<legend align='right'><b>ASSIGN APPROVAL LEVELS</b></legend>";
	if($num > 0){
?>
	<table width=100%>
		<tr>
			<td width="5%"><b>SN</b></td>
			<td><b>EMPLOYEE NAME</b></td>
			<td width="14%"><b>EMPLOYEE TYPE</b></td>
			<td width="14%"><b>EMPLOYEE TITLE</b></td>
			<td width="14%"><b>DEPARTMENT NAME</b></td>
			<td width="7%"><b>ACTION</b></td>
		</tr>
<?php
	while ($data = mysqli_fetch_array($select)) {
		//check if employee already assigned level
		$Employee_ID = $data['Employee_ID'];
		$check = mysqli_query($conn,"select * from tbl_approval_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($check);

?>
		<tr>
			<td width="5%"><?php echo ++$temp; ?></td>
			<td><?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
			<td><?php echo ucwords(strtolower($data['Employee_Type'])); ?></td>
			<td><?php echo ucwords(strtolower($data['Employee_Title'])); ?></td>
			<td><?php echo ucwords(strtolower($data['Department_Name'])); ?></td>
			<td id='<?php echo $data['Employee_ID']; ?>'>
			<?php if($no > 0){ ?>
				<input type="button" name="Remove_Level" id="Remove_Level" value="REMOVE" class="art-button-green" onclick="Remove_Approval_Level('<?php echo $data['Employee_ID']; ?>','<?php echo $Approval_Title; ?>')">
			<?php }else{ ?>
				<input type="button" name="Update" id="Update" value="ASSIGN" class="art-button-green" onclick="Assign_Approval_Level('<?php echo $data['Employee_ID']; ?>','<?php echo $Approval_Title; ?>')">
			<?php } ?>
			</td>
		</tr>
<?php
	}
?>
	</table>
<?php
	}else{
		echo "<center><h3>NO EMPLOYEE FOUND</h3></center>";
	}
?>

