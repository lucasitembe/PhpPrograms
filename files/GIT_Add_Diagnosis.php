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
    
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
    	$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }else{
    	$Patient_Payment_Item_List_ID = 0;
    }

    //get Git_Post_operative_ID
    $get_Git_Post_operative_ID = mysqli_query($conn,"SELECT Git_Post_operative_ID from tbl_git_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($get_Git_Post_operative_ID);
    if($nm > 0){
    	while ($dt = mysqli_fetch_array($get_Git_Post_operative_ID)) {
    		$Git_Post_operative_ID = $dt['Git_Post_operative_ID'];
    	}
    } else {
         $select = mysqli_query($conn,"SELECT pc.consultation_id, pc.Payment_Cache_ID from 
                            tbl_item_list_cache ilc, tbl_payment_cache pc where
                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and 
                            Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Payment_Cache_ID = $data['Payment_Cache_ID'];
				$consultation_ID = $data['consultation_id'];
			}
		}else{
			$Payment_Cache_ID = 0;
			$consultation_ID = 0;
		}

			$insert = mysqli_query($conn,"INSERT into tbl_git_post_operative_notes(
										Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
										consultation_ID, Registration_ID, Employee_ID)
										
									values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
										'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	
			
			//get Git_Post_operative_ID
		$get_Git_Post_operative_ID = mysqli_query($conn,"SELECT Git_Post_operative_ID from tbl_git_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($get_Git_Post_operative_ID);
		if($nm > 0){
			while ($dt = mysqli_fetch_array($get_Git_Post_operative_ID)) {
				$Git_Post_operative_ID = $dt['Git_Post_operative_ID'];
			}
		}
    }
?>

<fieldset>
    <table width='100%'>
		<tr>
		    <td style='text-align: center;'><b style='font-size: 16px;font-weight: bold'>
			    <table width='100%'>
					 
					<tr>
					    <td style='width: 20%;'><input type='text' id='procedure_dignosis_code' name='procedure_dignosis_code' style='width: 100%;  text-align: center;' onkeyup='Search_Via_Diagnosis_Code(<?= $Git_Post_operative_ID ?>)' oninput='Search_Via_Diagnosis_Code(<?= $Git_Post_operative_ID ?>)'  placeholder='----------ENTER DISEASE CODE----------'>
					    <td style='width: 20%;' colspan="3"><input type='text' id='procedure_dignosis_name' name='procedure_dignosis_name' style='width: 100%;  text-align: center;' onkeyup='Search_Via_Diagnosis_Name(<?= $Git_Post_operative_ID ?>)' oninput='Search_Via_Diagnosis_Name(<?= $Git_Post_operative_ID ?>)' placeholder='----------ENTER DISEASE NAME----------'>
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
	    	<fieldset style='overflow-y: scroll; height: 260px; background-color: white;' id="Diagnosis_List_Area">
                    <table width="100%" class="table table-condensed">
                        <tbody id="diagnosis_display_area">
				    <?php
						$result = mysqli_query($conn,"SELECT * FROM tbl_procedure_diagnosis WHERE disable_enable='enabled' LIMIT 100") or die(mysqli_error($conn));
			    		while($row = mysqli_fetch_array($result)){
					?>
						    <tr>
								<td><input type='radio' id="<?php echo $row['procedure_diagnosis_id']; ?>" name="Disease" onclick="Get_Selected_Diagnosis(<?php echo $row['procedure_diagnosis_id']; ?>,<?= $Git_Post_operative_ID ?>)"></td>
								<td><label class="itemhoverlabl" for="<?php echo $row['procedure_diagnosis_id']; ?>" ><?php echo $row['procedure_dignosis_name'];?>(<b><?php echo $row['procedure_dignosis_code']; ?></b>)</label></td>
						    </tr>
					<?php
					    }
				    ?>
                        </tbody>
                    </table>
                </fieldset>
	    </td>
	    <td width="65%">
	    	<fieldset style='overflow-y: scroll; height: 260px; background-color: white;' id='Selected_Diagnosis_List_Area'>
		    	<table width="100%" class="table table-condensed">
					<tr><td colspan="4"><hr></td></tr>
					<tr>
						<td width='4%'><b>SN</b></td>
						<td><b>DIAGNOSIS</b></td>
						<td width='20%'><b>CODE</b></td>
						<td width='8%'><b>ACTION</b></td>
					</tr>
					<tr><td colspan="4"><hr></td></tr>
			<?php
				//get selected diagnosis disease
				$select = mysqli_query($conn,"SELECT d.procedure_dignosis_code, d.procedure_dignosis_name, po.Diagnosis_ID 
										from tbl_procedure_diagnosis d, tbl_gti_post_operative_diagnosis po where
										d.procedure_diagnosis_id = po.procedure_diagnosis_id and
										po.Git_Post_operative_ID = '$Git_Post_operative_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
					$temp = 0;
					while ($data = mysqli_fetch_array($select)) {
			?>
						<tr>
							<td><?php echo ++$temp; ?></td>
							<td><?php echo $data['procedure_dignosis_name']; ?></td>
							<td><?php echo $data['procedure_dignosis_code']; ?></td>
							<td>
								<input type="button" name="Remove_Button" id="Remove_Button" value="REMOVE" class="art-button-green" onclick="Remove_Diagnosis(<?php echo $data['Diagnosis_ID']; ?>,<?= $Git_Post_operative_ID ?>)">
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
