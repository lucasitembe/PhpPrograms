<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Pre_Disease_Code'])){
		$Pre_Disease_Code = $_GET['Pre_Disease_Code'];
	}else{
		$Pre_Disease_Code = '';
	}

	if(isset($_GET['Pre_subcategory_ID'])){
		$Pre_subcategory_ID = $_GET['Pre_subcategory_ID'];
	}else{
		$Pre_subcategory_ID = 0;
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
		if($Pre_subcategory_ID == 0){
			$select = mysqli_query($conn,"select disease_code, disease_name, disease_ID from tbl_disease where disease_code like '%$Pre_Disease_Code%' order by disease_name limit 200") or die(mysqli_error($conn));
		}else{
			$select = mysqli_query($conn,"select disease_code, disease_name, disease_ID from tbl_disease where disease_code like '%$Pre_Disease_Code%' and subcategory_ID = '$Pre__subcategory_ID' order by disease_name limit 200") or die(mysqli_error($conn));
		}
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
	?>
				<tr>
					<td>
						<input type="radio" name="Check_Box" id="Check_Box<?php echo $temp; ?>" onclick="Get_Selected_Preoperative_Diagnosis(<?php echo $row['disease_ID']; ?>)">
					</td>
					<td><label for="Check_Box<?php echo $temp; ?>"><?php echo $row['disease_name']; ?>(<b><?php echo $row['disease_code']; ?></b>)</label></td>
				</tr>
	<?php
				$temp++;
			}
		}
	?>
</table>