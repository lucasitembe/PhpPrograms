<?php
	include("./includes/connection.php");
	if(isset($_GET['Doctror_Name'])){
		$Doctror_Name = $_GET['Doctror_Name'];
	}else{
		$Doctror_Name = '';
	}
?>
<table width="100%">
<?php
	$counter = 0;//emp.Employee_Job_Code = 'Endoscopist' and 
	$get_surgeons = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where (Employee_Job_Code = 'Anaesthesiologist' or Employee_Job_Code = 'Endoscopist_and_Anaesthesiologist') and Account_Status = 'active' AND Employee_Name like '%$Doctror_Name%' order by Employee_Name limit 100") or die(mysqli_error($conn));
	$surgeons_num = mysqli_num_rows($get_surgeons);
	if($surgeons_num > 0){
		while ($data = mysqli_fetch_array($get_surgeons)) {
?>
            <tr>
                <td width="10%">
                    <input type="radio" name="ASST" id="A<?php echo $counter; ?>" onclick="Get_Selected_Anaesthesiologist('<?php echo $data['Employee_ID']; ?>')">
                </td>
                <td>
                    <label for="A<?php echo $counter; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                </td>
            </tr>
<?php
			$counter++;
		}
	}
?>
</table>