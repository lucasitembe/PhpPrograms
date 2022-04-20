<?php
    session_start();
    include("./includes/connection.php");

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
    	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
    	$Employee_ID = 0;
    }

    if(isset($_GET['Payment_Item_Cache_List_ID'])){
    	$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
    	$Payment_Item_Cache_List_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
    	$Registration_ID = $_GET['Registration_ID'];
    }else{
    	$Registration_ID = 0;
    }
    
    if(isset($_GET['Patient_Payment_Item_List_ID']) && $_GET['Patient_Payment_Item_List_ID'] != null && $_GET['Patient_Payment_Item_List_ID'] != ''){
    	$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }else{
    	$Patient_Payment_Item_List_ID = 0;
    }

    //get Ogd_Post_operative_ID
    $get_Ogd_Post_operative_ID = mysqli_query($conn,"select Ogd_Post_operative_ID from tbl_ogd_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($get_Ogd_Post_operative_ID);
    if($nm > 0){
    	while ($dt = mysqli_fetch_array($get_Ogd_Post_operative_ID)) {
    		$Ogd_Post_operative_ID = $dt['Ogd_Post_operative_ID'];
    	}
    }else{
    	$Ogd_Post_operative_ID = 0;
    }
?>

<fieldset>
    <table width='100%'>
		<tr>
		    <td style='text-align: center;'><b style='font-size: 16px;font-weight: bold'>
			    <table width='100%'>
					<tr>
					    <td style='text-align: right; width: 15%'>Disease Category</td>
					    <td style="text-align: left;">&nbsp;&nbsp;&nbsp;
							<select id='disease_category_ID' onchange='Get_Disease_Subcategory();' name='disease_category_ID' style='width: 200px;'>
							    <option value="0">ALL</option>
						<?php
								$select = mysqli_query($conn,"select * from tbl_disease_category") or die(mysqli_error($conn));
								while($row = mysqli_fetch_assoc($select)){
						?>
									<option value='<?php echo $row['disease_category_ID']?>'><?php echo $row['category_discreption']; ?></option>
						<?php 
								}
						?>
							</select>
					    </td>
					    <td style='text-align: right; width: 15%'>Disease Sub Category</td>
					    <td style='text-align: right;'>
					    	<select id='subcategory_ID' name='subcategory_ID' onchange='Search_Disease()' style='width: 180px;'>
						    	<option>ALL</option>
							</select>
					    </td>
					</tr>
					<tr>
					    <td style='width: 20%;'><input type='text' id='disease_code' name='disease_code' style='width: 100%;  text-align: center;' onkeyup='searchDisease(); Clear_Disease_Name();' placeholder='----------DISEASE CODE----------'>
					    <td style='width: 20%;' colspan="3"><input type='text' id='disease_name' name='disease_name' style='width: 100%;  text-align: center;' onkeyup='searchDisease(); Clear_Disease_Code();' placeholder='----------DISEASE NAME----------'>
					    </td>
					</tr>
			    </table>
		    </td>
		</tr>
    </table>
</fieldset><br/>
<table width="100%">
	<tr>
	    <td width="35%">
	    	<fieldset style='overflow-y: scroll; height: 260px; background-color: white;' id="Disease_List_Area">
	    		<table width="100%">
				    <?php
						$result = mysqli_query($conn,"select disease_ID, disease_code, disease_name from tbl_disease limit 100") or die(mysqli_error($conn));
			    		while($row = mysqli_fetch_array($result)){
					?>
						    <tr>
								<td><input type='radio' id="<?php echo $row['disease_ID']; ?>" name="Disease" onclick="Get_Selected_Disease(<?php echo $Patient_Payment_Item_List_ID; ?>,<?php echo $Payment_Item_Cache_List_ID; ?>,<?php echo $Registration_ID; ?>,<?php echo $row['disease_ID']; ?>)"></td>
								<td><label class="itemhoverlabl" for="<?php echo $row['disease_ID']; ?>" ><?php echo $row['disease_name'];?>(<b><?php echo $row['disease_code']; ?></b>)</label></td>
						    </tr>
					<?php
					    }
				    ?>
			    </table>
			</fieldset>
	    </td>
	    <td width="65%">
	    	<fieldset style='overflow-y: scroll; height: 260px; background-color: white;' id='Selected_Disease_List_Area'>
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
										from tbl_disease d, tbl_ogd_post_operative_diagnosis po where
										d.disease_ID = po.Disease_ID and
										po.Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'") or die(mysqli_error($conn));
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
</table>
<table width='100%'>
    <tr>
		<td style='text-align: right;'><button onclick='Close_Diagnosis_Dialog()'class='art-button-green'>DONE</button></td>
    </tr>
</table>