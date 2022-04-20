<?php
	session_start();
	include("./includes/connection.php");
?>

<table width="100%">
	<tr>
		<td width="48%">
			<input type="text" name="Unselected_Employee" id="Unselected_Employee" placeholder="~~~ ~~~ ~~~ Enter Employee Name ~~~ ~~~ ~~~" style="text-align: center;" onkeypress="Search_Employee('Unselected')" oninput="Search_Employee('Unselected')">
		</td>
		<td></td>
		<td width="48%">
			<input type="text" name="Selected_Employee" id="Selected_Employee" placeholder="~~~ ~~~ ~~~ Enter Employee Name ~~~ ~~~ ~~~" style="text-align: center;" onkeypress="Search_Employee('Selected')" oninput="Search_Employee('Selected')">
		</td>
	</tr>
	<tr>
		<td>
			<fieldset style='overflow-y: scroll; height: 350px; background-color: white;' id='Unselected_List'>
				<table width="100%">
					<tr>
						<td width="5%"><b>SN</b></td>
						<td>&nbsp;&nbsp;&nbsp;<b>EMPLOYEE NAME</b></td>
						<td width="10%"><b></b></td>
					</tr>
			<?php
				//get unselected employees
				$count = 0;
				$select = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_ID not in(select Employee_ID from tbl_employee_performance_cache) order by Employee_Name limit 100") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
					while($row = mysqli_fetch_array($select)){
			?>
						<tr>
							<td><?php echo ++$count; ?></td>
							<td>&nbsp;&nbsp;&nbsp;<?php echo $row['Employee_Name']; ?></td>
							<td><input type="button" id="<?php echo $row['Employee_ID']; ?>" value=">>>" onclick="Add_Employee(<?php echo $row['Employee_ID']; ?>)"></td>
						</tr>
			<?php
					}
				}
			?>
				</table>
			</fieldset>
		</td>
		<td></td>
		<td>
			<fieldset style='overflow-y: scroll; height: 350px; background-color: white;' id='Selected_List'>
				<table width="100%">
					<tr>
						<td width="15%"><b></b></td>
						<td width="5%" style="text-align: right;"><b>SN</b></td>
						<td>&nbsp;&nbsp;&nbsp;<b>EMPLOYEE NAME</b></td>
					</tr>
			<?php
				//get unselected employees
				$count = 0;
				$select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_employee emp, tbl_employee_performance_cache epc where
										emp.Employee_ID = epc.Employee_ID order by Employee_Name limit 100") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
					while($row = mysqli_fetch_array($select)){
			?>
						<tr>
							<td><input type="button" id="<?php echo $row['Employee_ID']; ?>" value="<<<" onclick="Remove_Employee(<?php echo $row['Employee_ID']; ?>)"></td>
							<td style="text-align: right;"><?php echo ++$count; ?><b>.</b></td>
							<td>&nbsp;&nbsp;&nbsp;<?php echo $row['Employee_Name']; ?></td>
						</tr>
			<?php
					}
				}
			?>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="text-align: right;">
			<input type="button" class="art-button-green" value="CLOSE" onclick="Close_Dialog()">
		</td>
	</tr>
</table>