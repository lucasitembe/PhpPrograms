<?php
	include("./includes/connection.php");
	if(isset($_GET['Assistant_Surgeon_Name'])){
		$Assisitant_Surgeon_Name = $_GET['Assistant_Surgeon_Name'];
	}else{
		$Assisitant_Surgeon_Name = '';
	}
?>
<table width="100%">
<?php
	$counter = 0;
	$get_doctors = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_employee emp, tbl_privileges pri where
								emp.Employee_ID = pri.Employee_ID and
								Employee_Type = 'Doctor' and 
								Account_Status = 'active' and
								pri.Theater_Works = 'yes' and
								Employee_Name like '%$Assisitant_Surgeon_Name%' order by Employee_Name") or die(mysqli_error($conn));
	$doctors_num = mysqli_num_rows($get_doctors);
	if($doctors_num > 0){
		while ($data = mysqli_fetch_array($get_doctors)) {
?>
            <tr>
                <td width="10%">
                    <input type="radio" name="As" id="A<?php echo $counter; ?>" onclick="Get_Selected_Assistant('<?php echo $data['Employee_ID']; ?>')">
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