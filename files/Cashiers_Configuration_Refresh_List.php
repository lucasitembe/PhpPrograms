<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Employee_Type'])){
		$Employee_Type = $_GET['Employee_Type'];
	}else{
		$Employee_Type = '';
	}

	if($Employee_Type == 'Selected'){
?>
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
<?php
	}else{
?>
		<table width="100%">
			<tr>
				<td width="5%"><b>SN</b></td>
				<td>&nbsp;&nbsp;&nbsp;<b>EMPLOYEE NAME</b></td>
				<td width="10%"><b></b></td>
			</tr>
				<?php
					//get unselected employees
					$count = 0;
					$select = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where
											Employee_ID NOT IN(select Employee_ID from tbl_employee_performance_cache) order by Employee_Name limit 100") or die(mysqli_error($conn));
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
<?php
	}
?>