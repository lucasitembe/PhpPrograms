<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Disease_Name'])){
		$Disease_Name = $_GET['Disease_Name'];
	}else{
		$Disease_Name = '';
	}

	if(isset($_GET['subcategory_ID'])){
		$subcategory_ID = $_GET['subcategory_ID'];
	}else{
		$subcategory_ID = 0;
	}
	
	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}
	
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}
	
	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
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
			$select = mysqli_query($conn,"select disease_ID, disease_code, disease_name from tbl_disease where disease_name like '%$Disease_Name%' order by disease_name limit 200") or die(mysqli_error($conn));
		}else{
			$select = mysqli_query($conn,"select disease_ID, disease_code, disease_name from tbl_disease where disease_name like '%$Disease_Name%' and subcategory_ID = '$subcategory_ID' order by disease_name limit 200") or die(mysqli_error($conn));
		}
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
	?>
				<tr>
					<td><input type='radio' id="<?php echo $row['disease_ID']; ?>" name="Disease" onclick="Get_Selected_Disease(<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $Payment_Item_Cache_List_ID; ?>,<?php echo $Registration_ID; ?>,<?php echo $row['disease_ID']; ?>)"></td>
					<td><label class="itemhoverlabl" for="<?php echo $row['disease_ID']; ?>" ><?php echo $row['disease_name'];?>(<b><?php echo $row['disease_code']; ?></b>)</label></td>
			    </tr>
	<?php
				$temp++;
			}
		}
	?>
</table>