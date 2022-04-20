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
	$counter = 0;
	$get_surgeons = mysqli_query($conn,"SELECT emp.Employee_ID, emp.Employee_Name from tbl_employee emp, tbl_privileges pri where
	emp.Employee_ID = pri.Employee_ID and
	pri.Theater_Works = 'yes'  AND (emp.Employee_Type = 'Doctor' or emp.Employee_Type LIKE '%Surge%')  and emp.Employee_Name LIKE '%$Doctror_Name%' AND
	emp.Account_Status = 'active' AND emp.employee_signature IS NOT NULL order by Employee_Name limit 100") or die(mysqli_error($conn));
	$surgeons_num = mysqli_num_rows($get_surgeons);
	if($surgeons_num > 0){
		while ($data = mysqli_fetch_array($get_surgeons)) {
?>
            <tr>
                <td width="10%">
                    <input type="radio" name="D" id="D<?php echo $counter; ?>" onclick="Get_Selected_Surgeon('<?php echo $data['Employee_ID']; ?>')">
                </td>
                <td>
                    <label for="D<?php echo $counter; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                </td>
            </tr>
<?php
			$counter++;
		}
	}
?>
</table>