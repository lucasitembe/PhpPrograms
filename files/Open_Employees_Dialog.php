<?php
	session_start();
	include("./includes/connection.php");
?>
<table width="100%">
	<tr>
		<td>
			<input type="text" name="E_Name" id="E_Name" placeholder="~~~ ~~~ ~~~ Enter Employee Name ~~~ ~~~ ~~~" style="text-align: center;" onkeypress="Search_Employee()" oninput="Search_Employee()">
		</td>
	</tr>
	<tr>
		<td>
			<fieldset style='overflow-y: scroll; height: 305px;' id='Employees_Fieldset'>
				<table width="100%">
					<tr>
						<td width="3%"></td>
						<td><b>EMPLOYEE NAME</b></td>
					</tr>
				<?php
					$temp = 0;
					$select = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee order by Employee_Name limit 100") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select);
					if($num > 0){
						while ($data = mysqli_fetch_array($select)) {
				?>
							<tr>
								<td><input type="radio" name="Sp" id="<?php echo $data['Employee_ID']; ?>" onclick="Get_Employee(<?php echo $data['Employee_ID']; ?>,'<?php echo strtoupper($data['Employee_Name']); ?>')"></td>
								<td><label for="<?php echo $data['Employee_ID']; ?>"><?php echo strtoupper($data['Employee_Name']); ?></label></td>
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
		<td style="text-align: center;">
			<input type="radio" id="All_Employee" name="All_Employee" onclick="Select_All_Employees()"><label for="All_Employee"><b>All Employees</b></label>
		</td>
	</tr>
</table>