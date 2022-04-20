<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}

	// @mfoy dn
	if (isset($_GET['status'])) {
		$status = $_GET['status'];
	} else {
		$status = "Unknown";
	}
	// @mfoy dn

	$select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($rw = mysqli_fetch_array($select)) {
			$Post_operative_ID = $rw['Post_operative_ID'];
			// @mfoy dn
			mysqli_query($conn,"UPDATE tbl_post_operative_notes SET surgery_status='$status' WHERE Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
			// @mfoy dn
		}
	}else{
		$Post_operative_ID = 0;
	}

	$Required_Data = '<h4>YOU MUST FILL THE FOLLOWING FIELDS TO CONTINUE</h4><br/>';
	$Filled_Status = 'yes';
	$temp = 0;
	//Check if required fields filled
	//1. Surgeon
	$check1 = mysqli_query($conn,"select Participant_ID from tbl_post_operative_participant where Post_operative_ID = '$Post_operative_ID' and Employee_Type = 'Surgeon'") or die(mysqli_error($conn));
	$num_Check1 = mysqli_num_rows($check1);
	if($num_Check1 < 1){
		$Filled_Status = 'no';
		$Required_Data .= ++$temp."<b>.</b> Surgeon<br/>";
	}

	//2. Type of Anesthetic
	$check2 = mysqli_query($conn,"select Type_Of_Anesthetic from tbl_post_operative_notes where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
	$num_Check2 = mysqli_num_rows($check2);
	if($num_Check2 > 0){
		while ($data = mysqli_fetch_array($check2)) {
			$Type_Of_Anesthetic = $data['Type_Of_Anesthetic'];
		}
		if($Type_Of_Anesthetic == '' || $Type_Of_Anesthetic == null){
			$Filled_Status = 'no';
			$Required_Data .= ++$temp."<b>.</b> Type Of Anesthetic<br/>";
		}
	}else{
		$Filled_Status = 'no';
		$Required_Data .= ++$temp."<b>.</b> Type Of Anesthetic<br/>";
	}

	//3. Postoperative Diagnosis(findings)
	$check3 = mysqli_query($conn,"select Disease_ID from tbl_post_operative_diagnosis where 
							Post_operative_ID = '$Post_operative_ID' and 
							Diagnosis_Type = 'Postoperative Diagnosis'") or die(mysqli_error($conn));
	$num_Check3 = mysqli_num_rows($check3);
	if($num_Check3 < 1){
		$Filled_Status = 'no';
		$Required_Data .= ++$temp."<b>.</b> Postoperative Diagnosis(findings)<br/>";
	}

	//4. Procedure Description
	$check4 = mysqli_query($conn,"select Procedure_Description from tbl_post_operative_notes where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
	$num_Check4 = mysqli_num_rows($check4);
	if($num_Check4 > 0){
		while ($data = mysqli_fetch_array($check4)) {
			$Procedure_Description = $data['Procedure_Description'];
		}
		if($Procedure_Description == '' || $Procedure_Description == null){
			$Filled_Status = 'no';
			$Required_Data .= ++$temp."<b>.</b> Procedure description & closure<br/>";
		}
	}else{
		$Filled_Status = 'no';
		$Required_Data .= ++$temp."<b>.</b> Procedure description & closure<br/>";
	}

	//5. Specimen sent
	$check5 = mysqli_query($conn,"select Specimen_sent from tbl_post_operative_notes where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
	$num_Check5 = mysqli_num_rows($check5);
	if($num_Check5 > 0){
		while ($data = mysqli_fetch_array($check5)) {
			$Specimen_sent = $data['Specimen_sent'];
		}
		if($Specimen_sent == '' || $Specimen_sent == null){
			$Filled_Status = 'no';
			$Required_Data .= ++$temp."<b>.</b> Specimen sent<br/>";
		}
	}else{
		$Filled_Status = 'no';
		$Required_Data .= ++$temp."<b>.</b> Specimen sent<br/>";
	}



	if($Filled_Status == 'yes'){
		//Check Postoperative orders
		$slct = mysqli_query($conn,"select consultation_ID from tbl_post_operative_notes where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
		$num_Check6 = mysqli_num_rows($slct);
		if($num_Check6 > 0){
			while ($dt = mysqli_fetch_array($slct)) {
				$consultation_ID = $dt['consultation_ID'];
			}
		}else{
			$consultation_ID = 0;
		}
		$slct = mysqli_query($conn,"select pc.Payment_Cache_ID from tbl_payment_cache pc, tbl_item_list_cache ilc where
							pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
							consultation_ID = '$consultation_ID' and Order_Type = 'post operative' limit 1") or die(mysqli_error($conn));
		$nm_slct = mysqli_num_rows($slct);
		if($nm_slct > 0){
			$Postoperative_Orders = 1;
		}else{
			$Postoperative_Orders = 0;
		}
		if($Postoperative_Orders == 0){
			echo "nop";
		}else{
			echo $Filled_Status;
		}
	}else{
		echo $Required_Data;

		for ($i=$temp; $i < 6; $i++) { 
			echo "<br/>";
		}
		echo "<table width='100%'>";
		echo "<tr>
					<td style='text-align: right;'>
						<input type='button' value='CLOSE' class='art-button-green' onclick='Close_Mandatory_Dialog()'>
					</td>
				</tr>";
		echo "</table>";
	}
?>