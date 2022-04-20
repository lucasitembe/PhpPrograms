<?php
	include("./includes/connection.php");
	if(isset($_GET['Nurse_Name'])){
		$Nurse_Name = $_GET['Nurse_Name'];
	}else{
		$Nurse_Name = '';
	}
?>
<table width="100%">
<?php
	$counter = 0;
	$get_doctors = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where 
								Employee_Job_Code = 'Scrub Nurse' and 
								Account_Status = 'active' and 
								Employee_Name like '%$Nurse_Name%' order by Employee_Name") or die(mysqli_error($conn));
	$doctors_num = mysqli_num_rows($get_doctors);
	if($doctors_num > 0){
		while ($data = mysqli_fetch_array($get_doctors)) {
?>
            <tr>
                <td width="10%">
                    <input type="radio" name="N" id="N<?php echo $counter; ?>" onclick="Get_Selected_Nurse('<?php echo $data['Employee_ID']; ?>')">
                </td>
                <td>
                    <label for="N<?php echo $counter; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                </td>
            </tr>
<?php
			$counter++;
		}
	}
?>
</table>