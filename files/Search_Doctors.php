<?php
	include("./includes/connection.php");
	if(isset($_POST['name'])){
		$Doctror_Name = $_POST['name'];
	}else{
		$Doctror_Name = '';
	}
	
?>
<table width="100%">
<?php
	$counter = 0;
	$get_doctors = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where 
								Employee_Type = 'Doctor' and 
								Account_Status = 'active' and 
								Employee_Name like '%$Doctror_Name%' order by Employee_Name") or die(mysqli_error($conn));
	$doctors_num = mysqli_num_rows($get_doctors);
	if($doctors_num > 0){
		while ($data = mysqli_fetch_array($get_doctors)) {
?>
		<tr>
			<td style='text-align: right;'>
				<label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')"><?php echo ++$counter; ?></label>
			</td>
			<td>
				<label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
			</td>
		</tr>
<?php
		}
	}
?>
</table>
<script>
	function Get_Selected_Doctor(getDoctor_name){
        $("#consultant_Name").val(getDoctor_name);
    }
</script>