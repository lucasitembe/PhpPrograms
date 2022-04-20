<?php
	include("./includes/connection.php");
	$temp = 0;
	if(isset($_GET['subcategory_ID'])){
		$subcategory_ID = $_GET['subcategory_ID'];
	}else{
		$subcategory_ID = 0;
	}

	if(isset($_GET['Disease_Category_ID'])){
		$Disease_Category_ID = $_GET['Disease_Category_ID'];
	}else{
		$Disease_Category_ID = 0;
	}

	if(isset($_GET['subcategory_ID2'])){
		$subcategory_ID2 = $_GET['subcategory_ID2'];
	}else{
		$subcategory_ID2 = 0;
	}

	if(isset($_GET['Search_Assigned_Value'])){
		$Search_Assigned_Value = $_GET['Search_Assigned_Value'];
	}else{
		$Search_Assigned_Value = 0;
	}

	//echo $subcategory_ID.' - '.$Disease_Category_ID.' - '.$subcategory_ID2.' - '.$Search_Assigned_Value; exit();
?>
<table width="100%">
	<tr>
		<td width="6%"><b>SN</b></td>
		<td width="9%"><b>CODE</b></td>
		<td width="77%"><b>DISEASE NAME</b></td>
		<td width="8%"><b>ACTION</b></td>
	</tr>
<?php
	if($subcategory_ID == ''){
		if($Disease_Category_ID == 0){
			$select = mysqli_query($conn,"select d.disease_code, d.disease_ID, d.disease_name from
							tbl_disease_category dc, tbl_disease_subcategory ds, tbl_disease d where
							dc.disease_category_ID = ds.disease_category_ID and
							d.subcategory_ID = ds.subcategory_ID and
							ds.subcategory_ID <> '$subcategory_ID2' and
							d.disease_name like '%$Search_Assigned_Value%' order by d.disease_name, d.disease_code limit 200") or die(mysqli_error($conn));
		}else{
			$select = mysqli_query($conn,"select d.disease_code, d.disease_ID, d.disease_name from
							tbl_disease_category dc, tbl_disease_subcategory ds, tbl_disease d where
							dc.disease_category_ID = ds.disease_category_ID and
							d.subcategory_ID = ds.subcategory_ID and
							dc.disease_category_ID = '$Disease_Category_ID' and
							d.disease_name like '%$Search_Assigned_Value%' order by d.disease_name, d.disease_code") or die(mysqli_error($conn));
		}
	}else{
		$select = mysqli_query($conn,"select d.disease_code, d.disease_ID, d.disease_name from
							tbl_disease_category dc, tbl_disease_subcategory ds, tbl_disease d where
							dc.disease_category_ID = ds.disease_category_ID and
							d.subcategory_ID = ds.subcategory_ID and
							ds.subcategory_ID = '$subcategory_ID' and
							d.disease_name like '%$Search_Assigned_Value%' order by d.disease_name, d.disease_code") or die(mysqli_error($conn));
	}
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
	<tr>
		<td><?php echo ++$temp; ?></td>
		<td><?php echo $data['disease_code']; ?></td>
		<td><?php echo ucwords(strtolower($data['disease_name'])); ?></td>
		<td>
			<input type="button" name="Remove" id="Remove" value=">>" onclick="Remove_Disease(<?php echo $data['disease_ID']; ?>)">
		</td>
	</tr>
<?php
		}
	}
?>
</table>