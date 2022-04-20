<?php
	include("./includes/connection.php");
	if(isset($_GET['Anaesthetic_Name'])){
		$Anaesthetic_Name = $_GET['Anaesthetic_Name'];
	}else{
		$Anaesthetic_Name = '';
	}
?>
<table width="100%">
<?php
	$counter = 0;
	$get_doctors = mysqli_query($conn,"SELECT Employee_ID, Employee_Name from tbl_employee where Employee_Job_Code = 'Anaesthesiologist' and Account_Status = 'active' AND Employee_Name like '%$Anaesthetic_Name%' order by Employee_Name") or die(mysqli_error($conn));
	$doctors_num = mysqli_num_rows($get_doctors);
	if($doctors_num > 0){
		while ($data = mysqli_fetch_array($get_doctors)) {
?>
            <tr>
                <td width="10%">
                    <input type="radio" name="An" id="An<?php echo $counter; ?>" onclick="Get_Selected_Anaesthersis('<?php echo $data['Employee_ID']; ?>')">
                </td>
                <td>
                    <label for="An<?php echo $counter; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                </td>
            </tr>
<?php
			$counter++;
		}
	}
?>
</table>