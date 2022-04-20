<?php 
include("./includes/connection.php");
//    select patient information
if (isset($_GET['registration_id'])) {
    $Reg_ID = $_GET['registration_id'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,Registration_Date,Patient_Picture,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Reg_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Registration_Date = $row['Registration_Date'];
            $Patient_Picture = $row['Patient_Picture'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        //$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days, ".$diff->h." Hours";
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days";

    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $Registration_Date = '';
    $age = 0;
}






if (isset($_GET['registration_id'])) {
	$registration = $_GET['registration_id'];


$htm = "<div><img src='branchBanner/branchBanner.png'></div>";	

$htm .= "<table width='100%'><tr style='height:40px !important'><td style='text-align:right;'><i>Name:</i></td><td>".$Patient_Name."</td><td style='text-align:right;'><i>Reg:</i></td><td>".$Registration_ID."</td><td style='text-align:right;'><i>Sponsor:</i></td><td>".$Guarantor_Name."</td><td style='text-align:right;'><i>Gender:</i></td><td >".$Gender."</td></tr>
	<tr style='height:40px !important'><td style='text-align:right;'><i>Age:</i></td><td>".$age."</td>
	<td style='text-align:right;'><i>Ward:</i></td><td colspan='5'>".$ward."</td></tr></table>";

?>
<style type="text/css">
		table,tr,td{
		border: none !important;

	}

	.label-title{
		width: 15% !important;
	}
	 
</style>;
<?php

$select_anethesia_details = "SELECT * FROM tbl_anethesia WHERE registration_id='$registration'";
if ($result = mysqli_query($conn,$select_anethesia_details)) {

	$num = mysqli_num_rows($result);

	while($row=mysqli_fetch_assoc($result)){

	
	$registration_id = $row['registration_id']; 

	$procedure = $row['procedures'];
	$diagnosis = $row['diagnosis'];
	$surgeon = $row['surgeon'];
	$consent = $row['consent'];
	$anethesia = $row['anethesia'];
	$assist_anethesia = $row['assist_anethesia'];
	$significant_history = $row['significant_history'];
	$family_history = $row['family_history'];
	$cormobidities = $row['cormobidities'];
	$allergies = $row['allergies'];
	$medication = $row['medication'];
	$obese = $row['obese'];
	$healthy = $row['healthy'];
	$weak = $row['weak'];
	$pale = $row['pale'];
	$cooperative = $row['cooperative'];
	$aweke = $row['aweke'];
	$unconscious = $row['unconscious'];
	$aggressive = $row['aggressive'];
	$dehydrated = $row['dehydrated'];
	$special_pregnantbp =$row['special_pregnantbp'];
	$bp = $row['bp'];
	$temp = $row['temp'];
	$rr = $row['rr'];
	$spo2 = $row['spo2'];
	$wt = $row['wt'];
	$ht = $row['ht'];
	$bmi = $row['bmi'];
	$rgb = $row['rgb'];
	$cvs = $row['cvs'];
	$lungs = $row['lungs'];
	$system = $row['system'];
	$normal = $row['normal'];
	$limited = $row['limited'];
	$micrognathia = $row['micrognathia'];
	$next_extension = $row['next_extension'];
	$thyromental_distance = $row['thyromental_distance'];
	$loose = $row['loose'];
	$impant = $row['impant'];
	$normal_teeth = $row['normal_teeth'];
	$rft =$row['rft'];
	$lft = $row['lft'];
	$elect = $row['elect'];
	$hb_level = $row['hb_level'];
	$blood_group = $row['blood_group'];
	$fio2 = $row['fio2'];
	$sao2 = $row['sao2'];
	$be = $row['be'];
	$bic = $row['bic'];
	$pco2 = $row['pco2'];
	$ph = $row['ph'];
	$cxr = $row['cxr'];
	$ecg_echo_ctc_scan = $row['ecg_echo_ctc_scan'];
	$anethetic_risk = $row['anethetic_risk'];
	$proposed_anethesia = $row['proposed_anethesia'];
	$fasting_for = $row['fasting_for'];
	$blood_unit = $row['blood_unit'];
	$date = $row['date'];
	$name = $row['name'];
	$anethisiology_opinion = $row['anethisiology_opinion'];
	$patient_fasted = $row['patient_fasted'];
	$elective_surgery = $row['elective_surgery'];
	$emergency_surgery = $row['emergency_surgery'];
	$central_line = $row['central_line'];
	$spo2_morinitoring = $row['spo2_morinitoring'];
	$nbp = $row['nbp'];
	$ecg = $row['ecg'];
	$etco2 = $row['etco2'];
	$gases = $row['gases'];
	$iv =$row['iv'];
	$inhalational = $row['inhalational'];
	$rsi = $row['rsi'];
	$intubation =$row['intubation'];
	$comment = $row['comment'];
	$circle = $row['circle'];
	$t_iece = $row['t_iece'];
	$mask = $row['mask'];
	$lma = $row['lma'];
	$nasal = $row['nasal'];
	$type = $row['type'];
	$size = $row['size'];
	$spont = $row['spont'];
	$cont = $row['cont'];
	$tv = $row['tv'];
	$press = $row['press'];
	$peep = $row['peep'];
	$ie = $row['ie'];
	$air = $row['air'];
	$o2 = $row['o2'];
	$haloth = $row['haloth'];
	$isofl = $row['isofl'];
	$sevofl = $row['sevofl'];
	$others = $row['others'];
	$local_type = $row['local_type'];
	$conc = $row['conc'];
	$amount = $row['amount'];
	$position = $row['position'];
	$comments = $row['comments'];
	$done_by = $row['done_by'];


	$htm .= '

		<form id="anethesia_form" method="post" style="font-size:16">

			
			<table width="100%;" style="border:none !important">
				<tr>
					<td class="label-title" style="text-align:right;"><i>Diagnosis</i></td>
					<td>
						'.$diagnosis.'
					 </td>
					<td colspan="4" >Proposed</td>
				</tr>
				<tr>
					<td class="label-title" style="text-align:right;"><i>Procedure:</i></td>
					<td colspan="4">
						'. $procedure. '
					 </td>
				</tr>
				<tr>
					<td class="label-title" style="text-align:right;"><i>Surgeon:<i></td>
					<td>'.$surgeon.' </td>
					<td class="label-title" style="text-align:right;"><i>Anethesia:</i></td>
					<td>'.$anethesia.' </td>	
				</tr>

				<tr>
					<td class="label-title" style="text-align:right;"><i>Consent Signed:</i></td>
					<td>
					';

					if (!empty($consent)) {
						if ($consent == "YES") {
							$htm .= '<label>
							<span>
								<input type="radio" name="consent" checked="checked">YES</span>
							</label>
						<label>
						<span>
							<input type="radio" name="consent">NO
						</span>
						</label>';		
						}elseif ($consent == "NO") {
							$htm .= '<label>
							<span>
								<input type="radio" name="consent" >YES</span>
							</label>
						<label>
						<span>
							<input type="radio" name="consent" checked="checked">NO
						</span>
						</label>';
						}
					}else{
						$htm .= '<span>
								<input type="radio" name="consent" >YES</span>
							</label>
						<label>
						<span>
							<input type="radio" name="consent" checked="checked">NO
						</span>
						</label>';
					}
			$htm .= '
					</td>
						 
					<td class="label-title" style="text-align:right;">ASSIST. ANESTHETISTS:</td>
					<td>'.$assist_anethesia.' </td>	
				</tr>

				<tr>
					<td class="label-title" style="text-align:right;"> 
						<p style="margin:0px; padding:0px;"><i>PREOP ASSESSMENT:</i></p> 
						<p style="margin:0px; padding:0px;">Significant history:</p>	 
				</td>
				<td>
					'.$significant_history.'
				</td>

				<td class="label-title" style="text-align:right;"> 
						Family History	 
				</td>
				<td>
					'.$family_history.'
				</td>
				</tr>

				<tr>
					<td  class="label-title" style="text-align:right;">Cormobidities/Other systems affected:</td>
					<td colspan="3">
					'.$cormobidities.'
				</td>
				</tr>

				<tr>
					<td class="label-title" style="text-align:right;">Allergies:</td>
					<td>'.$allergies.' </td>
					<td class="label-title" style="text-align:right;">Medication:</td>
					<td>'.$medication.' </td>	
				</tr>
				<tr style="border:none;">
					<th>General Examination</th>
				</tr>
			</table>
			<table width="100%" border="none !important">
				<tr>';

				if (!empty($obese)) {
					$htm .='<td>
						<label><input type="checkbox" name="obese" value="Obese" checked="checked">Obese</label>
					</td>';
				}else{
					$htm .= '<td>
						<label><input type="checkbox" name="obese" value="Obese">Obese</label>
					</td>';
				}


				if (!empty($healthy)) {
					$htm .='<td>
						<label><input type="checkbox" name="healthy" value="Healthy" checked="checked">Healthy </label>
					</td>';
				}else{
					$htm .= '<td>
						<label><input type="checkbox" name="healthy" value="Healthy">Healthy </label>
					</td>';
				}



				if (!empty($weak)) {
					$htm .='<td>
						<label>
							<input type="checkbox" name="weak " value="weak" checked="checked">Weak 
						</label>
					</td>';
				}else{
					$htm .= '<td>
						<label>
							<input type="checkbox" name="weak " value="weak">Weak 
						</label>
					</td>';
				}

				if (!empty($pale)) {
					$htm .='<td>
						<label>
							<input type="checkbox" name="pale" value="pale" checked="checked">Pale 
						</label>
					</td>
					';
				}else{
					$htm .='<td>
						<label><input type="checkbox" name="pale" value="pale">Pale </label>
					</td>
		';
				}

			$htm .='
					<td>
						<label><b>Patient’s State:</b></label>
					</td>';
					
					if (!empty($cooperative)) {
			$htm .= '<td>
						<label><input type="checkbox" name="cooperative" value="cooperative" checked="checked">cooperative</label>
					</td>
					';	
					}else{
						
			$htm .=	'<td><label><input type="checkbox" name="cooperative" value="cooperative">cooperative</label>
					</td>';
					
					}


			if (!empty($aweke)) {
			$htm .= '<td>
						<label>
						<input type="checkbox" name="aweke" value="awake" checked="checked">
						Awake</label>
					</td>';			
					}else{
			$htm .= '<td>
						<label>
						<input type="checkbox" name="aweke" value="awake">
						Awake</label>
					</td>';			
						
					}		


			if (!empty($unconscious)) {
			$htm .= '<td>
						<label>
						<input type="checkbox" name="unconscious" value="unconscious" checked="checked">unconscious</label>
					</td>
					';			
					}else{
			$htm .= '<td>
						<label><input type="checkbox" name="unconscious" value="unconscious">unconscious</label>
					</td>
					';			
						
					}		

			if (!empty($aggressive)) {
			$htm .= '<td>
						<label><input type="checkbox" name="aggressive" value="Aggressive" checked="checked"> Aggressive</label>
					</td>
					';			
					}else{
			$htm .= '<td>
						<label>
						<input type="checkbox" name="unconscious" value="unconscious">unconscious</label>
					</td>
					';			
						
					}		
		
			
			if (!empty($dehydrated)) {
				if ($dehydrated == "yes") {
					$htm .= '<td>
						Dehydrated	<br />
						<label><input type="radio" name="dehydrated" value="yes" checked="checked">YES</label>
						<label><input type="radio" name="dehydrated">NO</label>
					</td>
					';		
				}elseif ($dehydrated == "no") {
					$htm .= '<td>
						Dehydrated	<br />
						<label><input type="radio" name="dehydrated" value="yes" >YES</label>
						<label><input type="radio" name="dehydrated" checked="checked">NO</label>
					</td>
					';
				}
						
					}else{
			$htm .= '<td>
						Dehydrated	<br />
						<label><input type="radio" name="dehydrated" value="yes">YES</label>
						<label><input type="radio" name="dehydrated" checked="checked">NO</label>
					</td>
					';			
						
					}	



			$htm .='
			</tr>
			</table>
			<table width="100%">
				<tr>
					<td style="text-align:right; !important">
						<b>Special: Pregnant:</b>
					</td>
					<td>'.$special_pregnant.'Wk Ga</td>
					
					<td style="text-align: right;">BP:</td>
					<td>'.$bp.' </td>

					<td style="text-align: right;">HR/PR:</td>
					<td>'.$hr_pr.' </td>

					<td style="text-align: right;">TEMP:</td>
					<td>'.$temp.' </td>
					<td style="text-align: right;">RR:</td>
					<td>'.$rr.' </td>
					<td style="text-align: right;">SPO2:</td>
					<td>'.$spo2.' </td>
				</tr>
				<tr>
					<td style="text-align: right;">WT:</td>
					<td>'.$wt.'<span>kg</span></td>
					<td style="text-align: right;">HT:</td>
					<td>'.$ht.' </td>
					<td style="text-align: right;">BMI:</td>
					<td>'.$bmi.' </td>
					<td style="text-align: right;">RBG:</td>
					<td>'.$rgb.' </td>
				</tr>

			</table>
			<table width="100%">
				<tr>
					<td><b>Mouth Opening: </b></td>
					<td>
					';

					if(!empty($normal)) {
						$htm .= '<label>
						<input type="checkbox" name="normal" value="normal" checked="checked">Normal</label>
						</td>';
					}else{
						$htm .= '<label>
						<input type="checkbox" name="normal" value="normal">Normal
						</label>
						</td>';
					}

					if(!empty($limited)) {
						$htm .= '
						<td>
						<label>
						<input type="checkbox" name="limited" value="limited" checked="checked">Limited
						</label>
						</td>';
					}else{
						$htm .= '<td>
						<label>
						<input type="checkbox" name="limited" value="limited" checked="checked">Limited
						</label>
						</td>';
					}


					if (!empty($micrognathia)){
						if ($micrognathia == "YES") {
							$htm .= '<td><i><strong>Micrognathia:</strong></i><br />
						<label><input type="radio" name="micrognathia" value="yes" checked="checked">YES</label>
						<label><input type="radio" name="micrognathia" value="no">NO</label>
						</td>';
						}elseif ($micrognathia == "NO") {
							$htm .= '<td><i><strong>Micrognathia:</strong></i><br />
						<label><input type="radio" name="micrognathia" value="yes">YES</label>
						<label>
						<input type="radio" name="micrognathia" value="no" checked="checked">NO
						</label>
						</td>';
						}

					}else{
						$htm = '<td><i><strong>Micrognathia:</strong></i><br />
						<label><input type="radio" name="micrognathia" value="yes">YES</label>
						<label><input type="radio" name="micrognathia" value="no">NO</label>
						</td>';
					}


					if (!empty($next_extension)){
						if ($next_extension == "yes") {
							$htm .= '<td><i><strong>Neck Extension:</strong></i><br/>
						<label><input type="radio" name="next_extension" value="yes" checked="checked">YES</label>
						<label><input type="radio" name="next_extension" value="no">NO</label>
					</td>';
						}elseif ($next_extension == "no") {
							$htm .= '<td><i><strong>Neck Extension:</strong></i><br/>
						<label><input type="radio" name="next_extension" value="yes">YES</label>
						<label><input type="radio" name="next_extension" value="no" checked="checked">NO</label>
					</td>';
						}

					}else{
						$htm = '<td><i><strong>Neck Extension:</strong></i><br/>
						<label><input type="radio" name="next_extension" value="yes">YES</label>
						<label><input type="radio" name="next_extension" value="no">NO</label>
					</td>';
					}

					if (!empty($thyromental_distance)){
						if ($next_extension == "yes") {
							$htm .= '<td><i><strong>Thyromental distance:</strong></i><br/>
						<label>
						<input type="radio" name="thyromental_distance" value="yes" checked="checked">>6cm
						</label>
						<label><input type="radio" name="thyromental_distance" value="no"><6cm</label>
					</td>';
						}elseif ($thyromental_distance == "no") {
							$htm .= '<td><i><strong>Thyromental distance:</strong></i><br/>
						<label>
						<input type="radio" name="thyromental_distance" value="yes" >>6cm
						</label>
						<label>
						<input type="radio" name="thyromental_distance" value="no" checked="checked"><6cm
						</label>
					</td>';
						}

					}else{
						$htm = '<td><i><strong>Thyromental distance:</strong></i><br/>
						<label>
						<input type="radio" name="thyromental_distance" value="yes">>6cm
						</label>
						<label><input type="radio" name="thyromental_distance" value="no"><6cm</label>
					</td>';
					}
				

				$htm .='
					<td><i><strong>Teeth:</strong></i><br/>
					';

				if (!empty($loose)) {
						$htm .= '<label>
						Loose<input type="checkbox" name="loose" value="llose" checked="checked">
						</label><br/>';
					}else
					{
						$htm .='<label>
						Loose<input type="checkbox" name="loose" value="llose">
						</label><br />';
					}

				if (!empty($impant)){
						$htm .= '<label>Impant
						<input  type="checkbox" name="impant" value="impant" checked="checked">
						</label><br />';
					}else
					{
						$htm .='<label>Impant
						<input  type="checkbox" name="impant" value="impant" >
						</label> <br />';
					}

				if (!empty($normal_teeth)) {
						$htm .= '<label>Normal<input type="checkbox" name="normal_teeth" value="normal" checked="checked"></label>';
					}else
					{
						$htm .='<label>Normal<input type="checkbox" name="normal_teeth" value="normal">
						</label>';
					}

								
				$htm .='											
					</td>

					</tr>
					</table>
				<table width="100%">
					<tr>
						<td style="text-align: right;"><i>CVS:</i></td>
						<td >'.$cvs.'</td>
						<td style="text-align: right;"><i>Lungs:</i></td>
						<td >'.$lungs.'</td>
						<td  style="text-align: right;"><i>System:</i></td>
						<td >'.$system.'</td>
					</tr>
			</table>

			<table width="100%" style="border:none !important;">
				<thead>
				
				<tr style="border:none;">
				<th colspan="2">ASA Classification 1,2,3,4,5, E</th>
				<th colspan="3"> MALLAMPATI 1 2 3 4</th>	
				</tr>
				</thead>

				<tr>
					<td style="text-align: right;"><i>Labs:RFT:</i></td>
					<td>'.$rft.'</td>
					<td style="text-align: right;"><i>LFT:</i></td>
					<td>'.$lft.'</td>
					<td style="text-align: right;"><i>Elect:</i></td>
					<td>'.$elect.'</td>
					<td style="text-align: right;"><i>Hb level:</i></td>
					<td>'.$hb_level.'</td>
					<td style="text-align: right;"><i>Blood Group:</i></td>
					<td>'.$blood_group.'</td>
				</tr>
				<tr>
					<td style="text-align: right;"><i>Blood Gas:FiO2</i></td>
					<td>'.$fio2.'</td>
					<td style="text-align: right;"><i>SaO2</i></td>
					<td>'.$sao2.'</td>
					<td style="text-align: right;"><i>BE</i></td>
					<td>'.$be.'</td>
					<td style="text-align: right;"><i>Bic</i></td>
					<td>'.$bic.'</td>
					<td style="text-align: right;"><i>PCO2</i></td>
					<td>'.$pco2.'</td>
				</tr>

				<tr>
					<td style="text-align: right;">PH:</td>
					<td>'.$ph.'</td>
					<td style="text-align: right;">CXR</td>
					<td>'.$cxr.'</td>
					<td style="text-align: right;">ECG/ECHO/CTC SKAN</td>
					<td colspan="5">'.$ecg_echo_ctc_scan.'</td>
					
				</tr>
				<tr>
					<td style="text-align: right;">Anethetic Risk:</td>
					<td colspan="4">
						'.$anethetic_risk.'
					</td>
						
					<td style="text-align: right;">Proposed Anathesia</td>
					<td colspan="4">
						'.$proposed_anethesia.'
					</td>
					
				</tr>
				<tr>
					<td style="text-align: right;">Premedicaton/Orders: Fasting For:</td>
					<td colspan="3">
						'.$fasting_for.'
					</td>

					<td style="text-align: right;">Blood unit</td>
					<td colspan="5">
						'.$blood_unit.'
					</td>

				</tr>
				<tr>
					<td style="text-align: right;">Reviewed By</td>
					<td colspan="2">
						'.$reviewed_by.'
					</td>
					<td style="text-align: right;">Signature</td>
					<td colspan="2">
						<input type="text" name="">
					</td>
					<td style="text-align: right;">Date</td>
					<td colspan="4">
						'.$date.'
					</td>
				</tr>

				<tr>
					<td>ANESTHESIOLOGIST OPINION:</td>
					<td colspan="5">
						'.$anethisiology_opinion.'
					</td>
					<td style="text-align: right;">name</td>
					<td colspan="4">
						'.$name.'
					</td>
				</tr>
			</table>

			<table width="100%">
				<tr>
					<td colspan="1"><strong>INTRAOP RECORD:</strong></td>
					<td colspan="3" style="text-align: right;">Has Patient Fasted</td>
				';	
				if (!empty($patient_fasted)) {
					if ($patient_fasted == "YES") {
						$htm .='<td colspan="2"><label><input type="radio" name="patient_fasted" value="YES" checked="checked">YES</label>
						<label><input type="radio" name="patient_fasted" value="NO">NO</label>
					</td>';	
					}elseif ($patient_fasted ==  "NO") {
						$htm .='<td colspan="2">
						<label>
						<input type="radio" name="patient_fasted" value="YES" >YES
						</label>
						<label><input type="radio" name="patient_fasted" value="NO" checked="checked">NO</label>
					</td>';
					}
					
				}else{
				$htm .='<td colspan="2"><label><input type="radio" name="patient_fasted" value="YES">YES</label>
						<label><input type="radio" name="patient_fasted" value="NO" checked="checked">NO</label>
					</td>';	
				}
			$htm .='					
					<td colspan="3" style="text-align: right;">
					';

				if (!empty($elective_surgery)) {
					$htm .='<label>ELECTIVE SURGERY
				<input type="checkbox" name="elective_surgery" value="elective surgery" checked="checked"> 
				</label>
				</td>';		
				}else{
				$htm .='<label>ELECTIVE SURGERY
				<input type="checkbox" name="elective_surgery" value="elective surgery">
				</label>
				</td>';	
				}


				if (!empty($emergency_surgery)) {
					$htm .='<td  colspan="4" style="text-align: right;">
						<label>EMERGENCY SURGERY
						<input type="checkbox" name="emergency_surgery" checked="checked">
						</label>
					</td>';		
				}else{
				$htm .='<td  colspan="4" style="text-align: right;">
						<label>EMERGENCY SURGERY<input type="checkbox" name="emergency_surgery"  surgery">
						</label>
					</td>';	
				}


				$htm .='
				</tr>
				<tr>
					<td colspan="1"><strong>Intraoperative record:</strong></td>
					<td style="text-align: right;"><i>IV Sites:</i></td>
					<td colspan="2">
						<label>'.$ivsites.'</label>
						
					</td>
					<td colspan="2" style="text-align: right;">
						<i>central Line</i>
					</td>	
					<td colspan="8">'.$central_line.'</label>
					</td>
				</tr>
				<tr>
					<td><strong>Monitoring</strong></td>
				<label>
					<td style="text-align: right;">Spo2</td>
					';

			if (!empty($spo2_morinitoring)) {
				$htm .='<td>
				<input type="checkbox" name="spo2_morinitoring" value="SpO2" checked="checked">
				</td>
				</label>';
				}else{
				$htm .='<label>
				<td>
				<input type="checkbox" name="spo2_morinitoring" value="SpO2">
				</td>
				</label>';	
				}


			if (!empty($nbp)) {
				$htm .='<td style="text-align: right;"><i>NIBP</i></td>
					<td>
					<input type="checkbox" name="nbp" value="NIBP" checked="checked">
					</td>
				';
				}else{
				$htm .='<td style="text-align: right;"><i>NIBP:</i></td>
					<td><input type="checkbox" name="nbp" value="NIBP"></td>
				';	
				}

			if (!empty($ecg)) {
				$htm .='<td style="text-align: right;"><i>ECG:<i/></td>
					<td><input type="checkbox" name="ecg" value="ECG" checked></td>
				';
				}else{
				$htm .='<td style="text-align: right;"><i>NIBP:</i></td>
					<td><input type="checkbox" name="nbp" value="NIBP" ></td>
				';	
				}


				if (!empty($etco2)) {
				$htm .='<td style="text-align: right;"><i>ETCO2:</i></td>
					<td><input type="checkbox" name="etco2" value="ETCO2" checked="checked"></td>
				';
				}else{
				$htm .='<td style="text-align: right;">ETCO2:</td>
					<td><input type="checkbox" name="etco2" value="ETCO2" ></td>
				';	
				}
				if (!empty($gases)) {
				$htm .='<td style="text-align: right;"><i>Gases:</i></td>
					<td><input type="checkbox" name="gases" value="Gases" checked="checked"></td>
				';
				}else{
				$htm .='<td style="text-align: right;"><i>Gases:</i></td>
					<td><input type="checkbox" name="gases" value="Gases"></td>
				';	
				}	
					
			$htm .='			

					<td style="text-align: right;">Position</td>
					<td>'.$position.'</td>

			</tr>
			</table>

			<table width="100%" style="border:none;">
				<tr style="border:none;">
					<th>
						TYPE OF ANAESTHESIA
					</th>

				</tr>
				<tr style="border:none; !important">
					<td style="border:none !important;">
						<strong>1. General anaesthesia</strong>
					</td>
				</tr>

				<tr>
					<td colspan="2">Induction:';

			if (!empty($iv)) {
				$htm .= '<label><input type="checkbox" name="iv" checked="checked">IV</label>';		
				}else{
					$htm .= '<label><input type="checkbox" name="iv"">IV</label>';
				}

			if (!empty($inhalational)) {
				$htm .= '<label>
				<input type="checkbox" name="inhalational" value="Inhalational" checked="checked">Inhalational
				</label>';		
				}else{
					$htm .= '<label>
					<input type="checkbox" name="inhalational" value="Inhalational">Inhalational</label>';
				}

			if (!empty($inhalational)) {
				$htm .= '<label><input type="checkbox" checked="checked" name="rsi" value="rsi">RSI</label>
					</td>
					';		
				}else{
					$htm .= '<label><input type="checkbox" name="rsi" value="rsi">RSI</label>
					</td>
					';
				}

				if (!empty($intubation)) {

					if ($intubation =="Awake") {
						$htm .= '<td colspan="2">intubation:<br>
						<label><input type="radio" name="intubation" value="Awake" checked="checked">Awake</label>
						<label><input type="radio" name="intubation" value="sleep">Sleep</label>
					</td>
							';	
					}else{
						$htm .= '<td colspan="2">intubation:<br/>
						<label><input type="radio" name="intubation" value="Awake" >Awake</label>
						<label>
						<input type="radio" name="intubation" value="sleep" checked="checked">Sleep
						</label>
					</td>
						';
					}
					
				}else{
					$htm .= '<td colspan="2">intubation:<br/>
						<label><input type="radio" name="intubation" value="Awake" >Awake</label>
						<label><input type="radio" name="intubation" value="sleep">Sleep</label>
					</td>
					';
				}

				if (!empty($comment)) {

					if ($comment =="easy") {
						$htm .= '<td colspan="8">Comment:<br />
						<label><input type="radio" name="comment" value="easy" checked="checked">Easy</label>
						<label><input type="radio" name="comment" value="difficult">Difficult</label>
					</td>
							';	
					}else{
						$htm .= '<td colspan="2">Comment:<br />
						<label><input type="radio" name="comment" value="easy">Easy</label>
						<label>
						<input type="radio" name="comment" value="difficult" checked="checked">Difficult
						</label>
					</td>
						';
					}
					
				}else{
					$htm .= '<td colspan="2">Comment:<br/>
						<label><input type="radio" name="comment" value="easy">Easy</label>
						<label><input type="radio" name="comment" value="difficult">Difficult</label>
					</td>
					';
				}
							
			$htm .='		
				</tr>

				<tr>
					<td colspan="2">Circuit:
					';

				if (!empty($circle)) {
					$htm .='<label><input type="checkbox" name="circle" value="Circle" checked="checked">Circle</label>';	
					}else{
					$htm .= '<label><input type="checkbox" name="circle" value="Circle">Circle</label>';	
					}


				if (!empty($t_iece)) {
					$htm .='<label>
					<input type="checkbox" name="t_iece" value="T-piece" checked="checked">T-piece</label>';	
					}else{
					$htm .= '<label><input type="checkbox" name="t_iece" value="T-piece">T-piece</label>';	
					}		

				if (!empty($mask)) {
					$htm .='</td>
					<td>Airway:
						<label><input type="checkbox" name="mask" value="Mask" checked="checked">Mask</label>';	
					}else{
					$htm .= '</td>
					<td>Airway:
						<label><input type="checkbox" name="mask" value="Mask">Mask</label>';	
					}	

				if (!empty($lma)) {
					$htm .='<label>
					<input type="checkbox" name="lma" value="LMA" checked="checked">LMA</label>
					</td>';	
					}else{
					$htm .= '
					<label><input type="checkbox" name="lma" value="LMA">LMA</label>
					</td>';	
					}	

				$htm .='			
					<td style="text-align: right"><i>Nasal:</i></td>
							'.$nasal.'
					<td ><b>ETT:<b></td>
					<td style="text-align: right"><i>Type:</i></td>
					<td>
						'.$type.'
					</td>
					<td style="text-align: right"><i>Size:</i></td>
					<td>
						'.$size.'
					</td>
					
				</tr>

			</table>
			<table width="100%">
				<tr>
					<td colspan="2">Ventillation:<br />
					';

			if (!empty($spont)) {
					$htm .='<label><input type="checkbox" name="spont" value="Spont" checked="checked">Spont</label>';	
					}else{
					$htm .= '
					<label><input type="checkbox" name="spont" value="Spont">Spont</label>';	
					}	
						
			if (!empty($cont)) {
					$htm .='<label><input type="checkbox" name="cont" value="Cont" checked="checked">Cont</label>
						
					</td>';	
					}else{
					$htm .= '
					<label><input type="checkbox" name="cont" value="Cont">Cont</label>
					</td>';	
					}					
			$htm .='
					
						
					
					<td style="text-align: right "><i>RR:</i></td>
							<td style="width:20%><input type="text" name="rr_last" "></td>
					
					
					<td style="text-align: right">TV</td>
					<td>
						'.$tv.'
					</td>
					<td style="text-align: right">Press</td>
					<td>
						'.$press.'
					</td>
					<td style="text-align: right">PEEP</td>
					<td>
						'.$peep.'
					</td>
					<td style="text-align: right">I:E</td>
					<td>
						'.$ie.'
					</td>	
				</tr>

			</table>
			<table width="100%">
				<tr>
					<td colspan="2">Anesth-Maintainance:
					';

					if (!empty($air)) {
						$htm .='<label>
						<input type="checkbox" name="air" value="air" checked="checked">Air</label>';
					}else{
						$htm .= '<label><input type="checkbox" name="air" value="air">Air</label>';
					}

					if (!empty($o2)) {
						$htm .='<label><input type="checkbox" name="o2" value="O2" checked="checked">O2</label>';
					}else{
						$htm .= '<label><input type="checkbox" name="o2" value="O2">O2</label>';
					}

					if (!empty($haloth)) {
						$htm .='<label><input type="checkbox" name="haloth" value="Haloth" checked="checked">Haloth</label>';
					}else{
						$htm .= '<label><input type="checkbox" name="haloth" value="Haloth">Haloth</label>';
					}

					if (!empty($isofl)) {
						$htm .='<label><input type="checkbox" name="isofl" value="Isofl" checked="checked">Isofl</label>';
					}else{
						$htm .= '<label><input type="checkbox" name="isofl" value="Isofl">Isofl</label>';
					}

					if (!empty($sevofl)) {
						$htm .='<label><input type="checkbox" name="sevofl" value="Sevofl" checked="checked">Sevofl</label>';
					}else{
						$htm .= '<label><input type="checkbox" name="sevofl" value="Sevofl">Sevofl</label>';
					}

				$htm .='						
						
					</td>
					
					<td style="text-align: right">Others</td>
					<td>
						'.$others.'
					</td>
					
				</tr>

			</table>


			<table width="100%">
				<tr style="border:none;">
					<th>
					<strong>2.Local/Regional:</strong>
				</th>
			</tr>

			<tr>
			<td style="text-align: right;">
					<i>Type:</i>
				</td>				
				<td>
				'.$local_type.'
			</td>
			<td style="text-align: right;">
					<i>Agent:</i>		
				</td>				
				<td>
				'.$agent.'
			</td>
			
			<td style="text-align: right;">
					<i>Conc:</i>
				</td>				
				<td>
				'.$conc.'
			</td>
				
			<td style="text-align: right;">
					<i>amount:</i>
				</td>				
				<td>
				'.$amount.'
			</td>
			<td style="text-align: right; color:#ccc">
					<b><i>Position:</i></b>
				</td>				
				<td>
				'.$position.'
			</td>

			</tr>
			<tr>
				<td style="text-align: right;">Comments</td>
				<td colspan="5">
					'.$comments.'
				</td>

				<td style="text-align: right;" >Done By</td>
				<td colspan="4">
				'.$done_by.'
			</td>
			</tr>
			</table>

		
		</form>';



	}
}
}


include("MPDF/mpdf.php");
// $mpdf = new mPDF('', 'A4-L');
// $mpdf=new mPDF('','A4-l', 0, 20,30,20,'P');
 $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8);

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);

$mpdf->Output();




