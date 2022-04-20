<!--<link rel="stylesheet" href="table.css" media="screen">--> 
<style>
	table,tr,td{	
	}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
</style> 
<?php
	require_once('includes/connection.php');

	isset($_GET['Registration_ID']) ? $Registration_ID = $_GET['Registration_ID'] : $Registration_ID = 0;
	isset($_GET['PatientType']) ? $PatientType = $_GET['PatientType'] : $PatientType = '';
	isset($_GET['listtype']) ? $listtype = $_GET['listtype'] : $listtype = '';

	$select_patients = "
		SELECT * 
			FROM
			tbl_patient_payment_item_list ppil,
			tbl_items i,
			tbl_patient_registration pr,
			tbl_patient_payments pp
				WHERE
				ppil.Item_ID = i.Item_ID AND
				ppil.Patient_Payment_ID = pp.Patient_Payment_ID AND
				pp.Registration_ID = pr.Registration_ID AND
				ppil.Check_In_Type = 'Radiology' AND
				pp.Registration_ID = '$Registration_ID' ";
?>
	<center><div id='results_here' style="height:25px;"></div></center>

<?php
	
	echo '<table width="100%">';
	
	echo '<tr style="text-transform:uppercase; font-weight:bold;" class="thead" width="100%" >';	
		echo '<td style="width:3%;">SN</td>';	
		echo '<td>Test Name</td>';	
		echo '<td>Doctor Comment</td>';	
		echo '<td>Remarks</td>';	
		echo '<td>Classification</td>';	
		echo '<td>Sonographer/Radiographer</td>';	
		echo '<td>Radiologist</td>';	
		echo '<td>Status</td>';	
		echo '<td>Parameters</td>';	
	echo '</tr>';	
	
	$select_patients_qry = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));
	
	$num_rows = mysqli_num_rows($select_patients_qry);
	
	if($num_rows > 0){
		//echo $num_rows;
	} else {
		//echo 'No rows';
	}
	
	$sn = 1;
	$doc_comment = ''; // Avoiding error if Doctor Comment is blank
	
	while($patient = mysqli_fetch_assoc($select_patients_qry)){
		$patient_name = $patient['Patient_Name'];
		$The_Item_ID = $patient['Item_ID'];
		$Billing_Type=$patient['Billing_Type'];
		$Status = $patient['Status'];
		$Transaction_type=$patient['Transaction_type'];
		//$doc_comment = $patient['Doctor_Comment'];
		
		//Getting the Old Values *****************//
		$select_rpt = "SELECT * FROM tbl_radiology_patient_tests WHERE Item_ID = '$The_Item_ID' AND Registration_ID = '$Registration_ID'";
		$select_rpt_qry = mysqli_query($conn,$select_rpt) or die(mysqli_error($conn));
		$Sonographer_ID = 0;
		$Radiologist_ID = 0;
		$oldstatus = '';
		$oldremarks = '';
		$oldclas = '';
		while($old_rpt = mysqli_fetch_assoc($select_rpt_qry)){
			$oldstatus = $old_rpt['Status'];
			$oldremarks = $old_rpt['Remarks'];
			$oldclas = $old_rpt['Classification'];
			$Sonographer_ID = $old_rpt['Sonographer_ID'];
			$Radiologist_ID = $old_rpt['Radiologist_ID'];
		}
		//************Old Values *****************//
		
		$doctor_comment ="<textarea class='docRemarks' name='doctor_comment' readonly='readonly' placeholder='No comments.Patient from reception'>Patient from reception</textarea>";
		$test_name = $patient['Product_Name'];
		$sent_date = $patient['Transaction_Date_And_Time'];
		
		//$Status_From = $patient['Status_From'];
		$Item_ID = $patient['Item_ID'];
		$Patient_Payment_Item_List_ID = $patient['Patient_Payment_Item_List_ID'];
		$Patient_Payment_ID = $patient['Patient_Payment_ID'];
		$Registration_ID = $patient['Registration_ID'];
		$Patient_Name = $patient['Patient_Name'];
		
		$href = "radiologyviewimage.php?II=".$Item_ID."&PPILI=".$Patient_Payment_Item_List_ID."&PPI=".$Patient_Payment_ID.'&RI='.$Registration_ID.'&PatientType='.$PatientType.'&listtype='.$listtype;
		
		/* $add_parameters = "<a href = '".$href."' target='_parent'><button>Add</button></a>"; */
		
		$add_parameters='<button style="width:60%;" class="art-button-green" onclick="radiologyviewimage(\''.$href.'\',\''.$test_name.'\')">Imaging</button>';
		$commentsDescription='<button style="width:60%;" class="art-button-green" onclick="commentsAndDescription(\''.$href.'\',\''.$test_name.'\')">Comments</button>';
		
		echo '<tr>';	
			echo '<td>'.$sn.'</td>';	
			echo '<td>'.$test_name.'</td>';	
			echo '<input type="hidden" id="'.$sn.'itemid" value="'.$The_Item_ID.'" />';	
			echo '<td>'.$doctor_comment.'</td>';	
			
			echo '<td>';
			?>
				 <textarea class='testRemarks'  placeholder='Add Remarks' onKeyUp='SaveThis(this.value, "<?php echo $sn; ?>", "rema", "<?php echo $Patient_Payment_Item_List_ID; ?>")'> <?php echo $oldremarks; ?></textarea>
			<?php
			echo '</td>';	
			
			//Select Classification
			if($Billing_Type=='Outpatient Cash' && $Transaction_type=='Cash' && $Status !='paid'){
			//'.$Payment_Item_Cache_List_ID.' '.$Transaction_type.' '.$Status.'
			  echo '<td style="padding-top:25px;font-weight:bold;font-size:18px;color:red;text-align:center"  colspan="5">'.$Billing_Type.' Patient has not paid for this item.Please advise him/her to pay first.</td>';
			}elseif($Billing_Type=='Outpatient Credit' && $Transaction_type=='Cash' && $Status !='paid'){
			//'.$Payment_Item_Cache_List_ID.' '.$Transaction_type.' '.$Status.'
			  echo '<td style="padding-top:25px;font-weight:bold;font-size:18px;color:red;text-align:center"  colspan="5"> '.$Billing_Type.' Patient has not paid for this item.Please advise him/her to pay first.</td>';
			}
			else{
			
			//Select Classification
			echo '<td>';
			?>
			<select name="classification" id="<?php echo $sn; ?>classification"  onChange="SaveThis(this.value, '<?php echo $sn; ?>', 'clas')" >
				<option></option>
				<option <?php if($oldclas == 'Ordinary Xray Register') echo 'selected="selected"'; ?> >Ordinary Xray Register</option>
				<option <?php if($oldclas == 'Special Xray Register') echo 'selected="selected"'; ?> >Special Xray Register</option>
				<option <?php if($oldclas == 'Eeg Register') echo 'selected="selected"'; ?> >Eeg Register</option>
				<option <?php if($oldclas == 'Ultrasound Register') echo 'selected="selected"'; ?> >Ultrasound Register</option>
				<option <?php if($oldclas == 'Echo Cardiogram Register') echo 'selected="selected"'; ?> >Echo Cardiogram Register</option>
				<option <?php if($oldclas == 'Other') echo 'selected="selected"'; ?> >Other</option>
			</select>	
			<?php			
			echo '</td>';	
			
			//Sonographer Select
			echo '<td>';
			?>
				<select name="Sonographer" id="<?php echo $sn; ?>sonographer"  onChange="SaveThis(this.value, '<?php echo $sn; ?>', 'sono')" >
			<?php
				echo '<option value="">--Select--<option>';
				$select_sonographer = "SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE Employee_Job_Code LIKE '%Sonographer%'";
				$select_sonographer_qry = mysqli_query($conn,$select_sonographer) or die(mysqli_error($conn));
				while($sonog = mysqli_fetch_assoc($select_sonographer_qry)){
					$sonogname = $sonog['Employee_Name'];
					$sonogid = $sonog['Employee_ID'];
					if($Sonographer_ID == $sonogid){
						echo "<option value='".$sonogid."' selected='selected'>".$sonogname."</option>";
					} else {
						echo "<option value='".$sonogid."'>".$sonogname."</option>";
					}					
				}			
				echo '</select>';	
			echo '</td>';
			
			//Radiologist Select
			echo '<td>';
			?>
				<select name="Radiologist" id="<?php echo $sn; ?>radiologist"  onChange="SaveThis(this.value, '<?php echo $sn; ?>', 'radi')" >
			<?php
				echo '<option value="">--Select--<option>';
				$select_radiologist = "SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE Employee_Job_Code LIKE '%Radiologist%'";
				$select_radiologist_qry = mysqli_query($conn,$select_radiologist) or die(mysqli_error($conn));
				while($radist = mysqli_fetch_assoc($select_radiologist_qry)){
					$radistname = $radist['Employee_Name'];
					$radistid = $radist['Employee_ID'];
					if($Radiologist_ID == $radistid){
						echo "<option value='".$radistid."' selected='selected'>".$radistname."</option>";
					} else {
						echo "<option value='".$radistid."'>".$radistname."</option>";
					}
				}			
				echo '</select>';				
			echo '</td>';	
			echo '<td>';
			?>
			<select name='status' id='<?php echo $sn."status"; ?>' <?php if($oldstatus == 'done') echo 'disabled="disabled"'; ?> onChange='SaveThis(this.value, "<?php echo $sn; ?>", "stat", "<?php echo $Patient_Payment_Item_List_ID; ?>","fromRec")' >
				<option value=''></option>
				<option value='done' <?php if($oldstatus == 'done') echo 'selected="selected"'; ?> >Done</option>
				<option value='not done' <?php if($oldstatus == 'not done') echo 'selected="selected"'; ?> >Not Done</option>
				<option value='pending' <?php if($oldstatus == 'pending') echo 'selected="selected"'; ?> >Pending</option>
			</select>	
			<?php
			echo '</td>';			
			echo '<td>'.$add_parameters.$commentsDescription.'</td>';
			}
		echo '</tr>';
		$sn++;
	}
	echo '</table>';
?>	
<script>
	function SaveThis(theinput, sn, inputname, PPILI,from){
	 //alert(from);exit;
		if(inputname == 'stat' && theinput == 'done'){
			
			var radi_input_id = sn+'radiologist';
			var sono_input_id = sn+'sonographer';
			
			var radi_input = document.getElementById(radi_input_id).value;
			var sono_input = document.getElementById(sono_input_id).value;
			
			if(radi_input == '' || sono_input == ''){
				alert('Please select Radiographer or Sonographer first.');
				var statinputid = sn+'status';
				document.getElementById(statinputid).value = "";
				exit;
			}			
			
			if (confirm('Are you sure you want to change this status to Done?')) {
				var stat_input_id = sn+'status';
				document.getElementById(stat_input_id).disabled = true;
				var hiddenitemid = sn+'itemid';
				var Item_ID = document.getElementById(hiddenitemid).value;
				var Registration_ID = <?php echo $Registration_ID; ?>;
				var PPI = <?php echo $Patient_Payment_ID; ?>;
				
				//alert(PPI);
				
				/* */
				if(window.XMLHttpRequest) {
					st = new XMLHttpRequest();
				}
				else if(window.ActiveXObject){ 
					st = new ActiveXObject('Micrsoft.XMLHTTP');
					st.overrideMimeType('text/xml');
				}

				st.onreadystatechange = AJAXSaveThis; //specify name of function that will handle server response....
				st.open('GET','RadiologyPatientTests_Save.php?theinput='+theinput+'&inputname='+inputname+'&RI='+Registration_ID+'&II='+Item_ID+'&sn='+sn+'&PPI='+PPI+'&PPILI='+PPILI+'&from='+from,true);
				st.send();
			} else {
				exit;
			}
		} else {
			var hiddenitemid = sn+'itemid';
			var Item_ID = document.getElementById(hiddenitemid).value;
			var Registration_ID = <?php echo $Registration_ID; ?>;
			var PPI = <?php echo $Patient_Payment_ID; ?>;
			
			//alert(PPI);
			
			/* */
			if(window.XMLHttpRequest) {
				st = new XMLHttpRequest();
			}
			else if(window.ActiveXObject){ 
				st = new ActiveXObject('Micrsoft.XMLHTTP');
				st.overrideMimeType('text/xml');
			}

			st.onreadystatechange = AJAXSaveThis; //specify name of function that will handle server response....
			st.open('GET','RadiologyPatientTests_Save.php?theinput='+theinput+'&inputname='+inputname+'&RI='+Registration_ID+'&II='+Item_ID+'&sn='+sn+'&PPI='+PPI+'&PPILI='+PPILI,true);
			st.send();				
		}

		function AJAXSaveThis() {
			var respond = st.responseText;
			var message = "<div style='background-color:white; width:200px; color:green; z-index:1000;'>"+respond+"</div>";
			document.getElementById('results_here').innerHTML = message;
		}
	}
</script>

