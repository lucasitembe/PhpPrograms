<?php
	session_start();
	include("./includes/connection.php");
	$get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysql_error);
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }

	if(isset($_GET['Disease_Code'])){
		$Disease_Code = $_GET['Disease_Code'];
	}else{
		$Disease_Code = '';
	}

	if(isset($_GET['subcategory_ID'])){
		$subcategory_ID = $_GET['subcategory_ID'];
	}else{
		$subcategory_ID = 0;
	}
?>
<table width="100%">
	<?php
		$temp = 0;
		$Title = '<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td width="5%"></td>
						<td><b>Disease Name</b></td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>';
		echo $Title;
		if($subcategory_ID == 0){
			$select = mysqli_query($conn,"SELECT disease_ID, disease_code, disease_name from tbl_disease where disease_code like '%$Disease_Code%' AND  disease_version = '$configvalue_icd10_9' order by disease_name limit 200") or die(mysqli_error($conn));
		}else{
			$select = mysqli_query($conn,"SELECT disease_ID, disease_code, disease_name from tbl_disease where disease_code like '%$Disease_Code%' AND  disease_version = '$configvalue_icd10_9'  and subcategory_ID = '$subcategory_ID' order by disease_name limit 200") or die(mysqli_error($conn));
		}
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
	?>
				<tr>
					<td>
						<input type="radio" name="Check_Box" id="<?php echo $temp; ?>"  onclick="Get_Selected_Postoperative_Diagnosis(<?php echo $row['disease_ID']; ?>)">
					</td>
					<td><label for="<?php echo $temp; ?>"><?php echo $row['disease_name']; ?></label></td>
				</tr>
	<?php
				$temp++;
			}
		}
	?>
</table>