<?php
	include("./includes/connection.php");
	if(isset($_GET['Emp_Name'])){
		$Emp_Name = $_GET['Emp_Name'];
	}else{
		$Emp_Name = '';
	}
	$counter = 0;
?>
<table width="100%">
	<tr>
	    <td style='text-align: right;'>
	        <label onclick="Get_Selected_Employee('All',0)"><?php echo ++$counter; ?></label>
	    </td>
	    <td>
	        <label onclick="Get_Selected_Employee('All',0)">&nbsp;&nbsp;&nbsp;&nbsp;All</label>
	    </td>
	</tr>
<?php
	$get_doctors = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where
								Account_Status = 'active' and
								Employee_Name <> 'CRDB' and
								Employee_Name like '%$Emp_Name%' order by Employee_Name limit 100") or die(mysqli_error($conn));
	$doctors_num = mysqli_num_rows($get_doctors);
	if($doctors_num > 0){
		while ($data = mysqli_fetch_array($get_doctors)) {
?>
		<tr>
			<td style='text-align: right;'>
				<label onclick="Get_Selected_Employee('<?php echo $data['Employee_Name']; ?>',<?php echo $data['Employee_ID']; ?>)"><?php echo ++$counter; ?></label>
			</td>
			<td>
				<label onclick="Get_Selected_Employee('<?php echo $data['Employee_Name']; ?>',<?php echo $data['Employee_ID']; ?>)">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
			</td>
		</tr>
<?php
		}
	}
?>
</table>