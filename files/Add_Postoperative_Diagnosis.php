<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = NULL;
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = NULL;
	}
	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = 0;
	}

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	$get_icd_9_or_10_result=mysqli_query($conn,"SELECT configvalue FROM tbl_config WHERE configname='Icd_10OrIcd_9'") or die(mysql_error);
    if(mysqli_num_rows($get_icd_9_or_10_result)>0){
        $configvalue_icd10_9=mysqli_fetch_assoc($get_icd_9_or_10_result)['configvalue'];
    }

	//check if data available into tbl_post_operative_notes
    $select = mysqli_query($conn,"SELECT Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
    	while ($data = mysqli_fetch_array($select)) {
    		$Post_operative_ID = $data['Post_operative_ID'];
    	}
    }else{
    	$insert = mysqli_query($conn,"INSERT into tbl_post_operative_notes(
    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
    							consultation_ID, Registration_ID, Employee_ID)
    							
    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
    }

    //Select Post_operative_ID
    $select = mysqli_query($conn,"SELECT Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
    	while ($data = mysqli_fetch_array($select)) {
    		$Post_operative_ID = $data['Post_operative_ID'];
    	}
    }else{
    	$Post_operative_ID = 0;
    }	    
?>

<table width="100%">
	<tr>
		<td width="30%"><b>Disease Category</b></td>
		<td>
			<select name="disease_category_ID" id="disease_category_ID" onchange="Get_Sub_Categories()">
				<option selected="selected" value="0">All</option>
			<?php
				$select = mysqli_query($conn,"SELECT * from tbl_disease_category") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
					while ($data = mysqli_fetch_array($select)) {
			?>
					<option value="<?php echo $data['disease_category_ID']; ?>"><?php echo $data['category_discreption']; ?></option>
			<?php
					}
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="30%"><b>Disease Sub Category</b></td>
		<td id="Disease_Sub_Category_Area">
			<select name="subcategory_ID" id="subcategory_ID">
				<option selected="selected" value="0">All</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<input type="text" name="Disease_Code" id="Disease_Code" autocomplete="off" placeholder="~~~ Enter Disease Code ~~~" style="text-align: center;" oninput="Search_Via_Disease_Code()" onkeypress="Search_Via_Disease_Code()">
		</td>
		<td>
			<input type="text" name="Disease_Name" id="Disease_Name" autocomplete="off" placeholder="~~~ ~~~ Enter Disease Name ~~~ ~~~" style="text-align: center;" oninput="Search_Via_Disease_Name()" onkeypress="Search_Via_Disease_Name()">
		</td>
	</tr>
</table>

<table width="100%">
	<tr>
		<td width="35%">
			<fieldset style='overflow-y: scroll; height: 300px; background-color: white;' id='Diseases_Fieldset'>
				<table width="100%">
				<?php
					$temp = 0;
					$Title = '<tr><td colspan="2"><hr></td></tr>
								<tr>
									<td width="3%"></td>
									<td><b>DISEASE NAME</b></td>
								</tr>
								<tr><td colspan="2"><hr></td></tr>';
					echo $Title;
					$select = mysqli_query($conn,"SELECT disease_ID, disease_code, disease_name FROM tbl_disease  WHERE  disease_version = '$configvalue_icd10_9'order by disease_name limit 100") or die(mysqli_error($conn));
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
			</fieldset>
		</td>
		<td width="65%">
			<fieldset style='overflow-y: scroll; height: 300px; background-color: white;' id='Postoperative_Selected_Disease_List_Area'>
		    	<table width="100%">
					<tr><td colspan="4"><hr></td></tr>
					<tr>
						<td width='4%'><b>SN</b></td>
						<td><b>DISEASE</b></td>
						<td width='20%'><b>CODE</b></td>
						<td width='8%'><b>ACTION</b></td>
					</tr>
					<tr><td colspan="4"><hr></td></tr>
			<?php
				//get selected diagnosis disease
				$select = mysqli_query($conn,"select d.disease_code, d.disease_name, d.disease_ID, po.Diagnosis_ID
										from tbl_disease d, tbl_post_operative_diagnosis po where
										d.disease_ID = po.Disease_ID and
										po.Diagnosis_Type = 'Postoperative Diagnosis' and
										po.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
					$temp = 0;
					while ($data = mysqli_fetch_array($select)) {
			?>
						<tr>
							<td><?php echo ++$temp; ?></td>
							<td><?php echo $data['disease_name']; ?></td>
							<td><?php echo $data['disease_code']; ?></td>
							<td>
								<input type="button" name="Remove_Button" id="Remove_Button" value="REMOVE" class="art-button-green" onclick="Remove_Disease(<?php echo $data['Diagnosis_ID']; ?>)">
							</td>
						</tr>
			<?php
					}
				}
			?>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right;">
			<input type="button" name="Close" id="Close" value="DONE" class="art-button-green" onclick="Close_Postoperative_Dialogy()">
		</td>
	</tr>
</table>