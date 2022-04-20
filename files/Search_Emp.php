<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Employee_Name'])){
		$Employee_Name = str_replace(" ", "%", $_GET['Employee_Name']);
	}else{
		$Employee_Name = '';
	}
?>
<table width="100%">
	<tr>
		<td width="3%"></td>
		<td><b>EMPLOYEE NAME</b></td>
	</tr>
<?php
	$temp = 0;
	$select = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Name like '%$Employee_Name%' order by Employee_Name limit 100") or die(mysqli_error($conn));
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