<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}
	
	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID = '';
	}
	
	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}

	//get details
	$select = mysqli_query($conn,"select poc.Pre_Operative_ID, poc.Theatre_Time, emp.Employee_Name, poc.Ward_Signature, poc.Theatre_Signature, poc.Operative_Date_Time from 
							tbl_pre_operative_checklist poc, tbl_employee emp where
							Registration_ID = '$Registration_ID' and
							poc.Employee_ID = emp.Employee_ID order by Pre_Operative_ID desc") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	$temp = 0;
	if($num > 0){
?>
		<fieldset style="overflow-y: scroll; height: 300px; background-color: white;">
			<table width="100%">
				<tr><td colspan="7"><hr></td></tr>
				<tr>
					<td width="5%"><b>SN</b></td>
					<td width="15%"><b>THEATRE TIME</b></td>
					<td width="14%"><b>NURSE IN WARD</b></td>
					<td width="14%"><b>NURSE IN THEATRE</b></td>
					<td width="15%"><b>DATE & TIME CREATED</b></td>
					<td width="14%"><b>EMPLOYEE CREATED</b></td>
					<td width="7%"></td>
				</tr>
				<tr><td colspan="7"><hr></td></tr>
<?php
		while ($data = mysqli_fetch_array($select)) {
			//Get Nurse in ward & Nurse in Theatre
			$Ward_Signature = $data['Ward_Signature'];
			$Theatre_Signature = $data['Theatre_Signature'];
			$slct = mysqli_query($conn,"select Employee_Name as Ward_Signature, (select Employee_Name from tbl_employee where Employee_ID = '$Theatre_Signature') as Theatre_Signature from tbl_employee where Employee_ID = '$Ward_Signature'") or die(mysqli_error($conn));
			$results = mysqli_fetch_assoc($slct);
?>
			<tr id="sss">
				<td><?php echo ++$temp; ?></td>
				<td><?php echo $data['Theatre_Time']; ?></td>
				<td><?php echo ucwords(strtolower($results['Ward_Signature'])); ?></td>
				<td><?php echo ucwords(strtolower($results['Theatre_Signature'])); ?></td>
				<td><?php echo $data['Operative_Date_Time']; ?></td>
				<td><?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
				<td><input type="button" class="art-button-green" value="PREVIEW" onclick="Preview_Report(<?php echo $data['Pre_Operative_ID']; ?>)"></td>
			</tr>
<?php
		}
?>
			</table>
		</fieldset>
<?php
	}else{
		echo "<br/><br/><br/><br/><center><h3>NO RECORDS FOUND</h3></center>";
	}
?>