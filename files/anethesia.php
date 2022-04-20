<?php 
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];

}


?>
<!--    -->
<?php
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}




//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
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
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
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
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
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
?>

<?php 
$select_anethesia_details = "SELECT * FROM tbl_anethesia WHERE registration_id='$Registration_ID'";
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
	$hr_pr = $row['hr_pr'];

}

}

if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
if(isset($_GET['Patient_Payment_ID'])){
   $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
}else{
   $Patient_Payment_ID=""; 
}
if(isset($_GET['Patient_Payment_Item_List_ID'])){
   $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
   $Patient_Payment_Item_List_ID=""; 
}
?>


<?php 

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {

      $backlink = $_SERVER['HTTP_REFERER'];
        ?>
        <a href="nursecommunicationpage.php?<?php echo $_SERVER['QUERY_STRING'] ?>" alt="" class='art-button-green'>
            BACK
        </a>
    <?php }
}
?>



<style type="text/css">
	table,tr,td{
		border: none !important;

	}

	.label-title{
		width: 15% !important;
	}
</style>

<center>
	<fieldset>
		<legend align=center style="font-weight: bold;">
                    ANESTHESIA PRE-OPERATIVE RECORD
		<div style="color:yellow">
			<span><?=$Patient_Name;?></span>
			
			<span>|</span>
			<span><?=$age ?></span>	
			<span>|</span>
			<span><?=$Gender ?></span>
			<span>|</span>
			<span><?=$Registration_ID ?></span>
			<span>|</span>
			<span><?=$Guarantor_Name?></span>	
		</div>
		</legend>


		<form id="anethesia_form" method="post">

			<input type="hidden" id="registration" name="registration_id" value="<?=$Registration_ID?>">
			<table width="100%;">
				<tr>
					<td class="label-title" style="text-align:right;">Diagnosis</td>
					<td>
						<input type="text" name="diagnosis" value="<?php if(!empty($diagnosis))
						echo $diagnosis?>"	>
					 </td>
					<td>Proposed</td>
				</tr>
				<tr>
					<td class="label-title" style="text-align:right;">Procedure:</td>
					<td>
						<input type="text" name="procedures" value="<?php if(!empty($procedure))
						echo $procedure; ?>">
					 </td>
				</tr>
				<tr>
					<td class="label-title" style="text-align:right;">Surgeon:</td>
					<td><input type="text" name="surgeon" value="<?php if(!empty($surgeon))
						echo $surgeon?>">  </td>
					<td class="label-title" style="text-align:right;">Anethesia:</td>
					<td><input type="text" name="anethesia"  value="<?php if(!empty($anethesia))
						echo $anethesia?>" > </td>	
				</tr>

				<tr>
					<td class="label-title" style="text-align:right;">Consent Signed:</td>
					<td>
						
					<?php 
					if (!empty($consent)) {
							if ($consent =="YES") {
								?>
							<label>
							<span>
								<input type="radio" name="consent" value="YES" checked="checked">YES
							</span>
							</label>

							<label>
							<span>
							<input type="radio" name="consent" value="NO">NO
							</span>
							</label>

					<?php
					}elseif ($consent == "NO") {
						?>
						<label>
							<span>
								<input type="radio" name="consent" value="YES">YES
							</span>
							</label>

							<label>
							<span>
							<input type="radio" name="consent" value="NO" checked="checked">NO
							</span>
							</label>
					<?php }
						}else{

						?>	
						<label>
							<span>
								<input type="radio" name="consent" value="YES">YES
							</span>
							</label>

							<label>
							<span>
							<input type="radio" name="consent" value="NO">NO
							</span>
							</label>
					<?php
				}	
					 ?>	
							

						

					</td>
						 
					<td class="label-title" style="text-align:right;">ASSIST. ANESTHETISTS:</td>
					<td><input type="text" name="assist_anethesia" value="<?php if(!empty($assist_anethesia))
						echo $assist_anethesia?>"> </td>	
				</tr>

				<tr>
					<td class="label-title" style="text-align:right;"> 
						<p style="margin:0px; padding:0px;">PREOP ASSESSMENT:</p> 
						<p style="margin:0px; padding:0px;">Significant history:</p>	 
				</td>
				<td>
					<textarea rows="3" cols="10" name="significant_history" ><?php if(!empty($significant_history))
						echo $significant_history?></textarea>
				</td>

				<td class="label-title" style="text-align:right;"> 
						Family History	 
				</td>
				<td>
					<textarea rows="3" cols="10" name="family_history"><?php if(!empty($family_history))
						echo $family_history?></textarea>
				</td>
				</tr>

				<tr>
					<td class="label-title" style="text-align:right;">Cormobidities/Other systems affected:</td>
					<td>
					<textarea rows="3" cols="10" name="cormobidities"><?php if(!empty($cormobidities))
						echo $cormobidities?></textarea>
				</td>
				</tr>

				<tr>
					<td class="label-title" style="text-align:right;">Allergies:</td>
					<td><input type="text" name="allergies" value="<?php if(!empty($allergies))
						echo $allergies?>"> </td>
					<td class="label-title" style="text-align:right;">Medication:</td>
					<td><input type="text" name="medication" value="<?php if(!empty($medication))
						echo $medication?>"> </td>	
				</tr>
				<tr>
					<th>General Examination</th>
				</tr>
			</table>

			<table width="100%">
				<tr>

					<?php 
					if (!empty($obese)){
						?>

					<td>
						<label>
							<input type="checkbox" name="obese" value="Obese" checked="checked">Obese
						<label>
					</td>

				<?php
				}else{
				?>
					
					<td>
						<label>
							<input type="checkbox" name="obese" value="Obese">Obese</label>
					</td>

					<?php 
					}?>


				<?php	
					if (!empty($healthy)) {
					?>
				<td>
					<label>
						<input type="checkbox" name="healthy" value="Healthy" checked="checked">Healthy 
					</label>
				</td>

				<?php
				}else{
				?>
				<td>
					<label>
						<input type="checkbox" name="healthy" value="Healthy" >Healthy 
					</label>
				</td>
				<?php 
				}
				?>




					<?php if (!empty($weak)) {		
					?>
					<td>
						<label><input type="checkbox" name="weak " value="weak" checked="checked">Weak </label>
					</td>
				<?php
				}else{
				
				?>
					 <td>
						<label><input type="checkbox" name="weak " value="weak">Weak </label>
					</td>
				<?php 
				}
				?>


				<?php
					if(!empty($pale)){
					?>	
					<td>
						<label>
							<input type="checkbox" name="pale" value="pale" checked="checked">Pale 
						</label>
					</td>

					<?php
				}else{
					 ?>
					 <td>
						<label><input type="checkbox" name="pale" value="pale">Pale </label>
					</td>

				<?php 
					}
				?>
				


					
					<td style="text-align: right;">
						<label><b>Patient’s State:</b></label>
					</td>

				<?php
				if (!empty($cooperative)) {
					?>
					<td>
						<label>
							<input type="checkbox" name="cooperative" value="cooperative" checked="checked">cooperative
						</label>
					</td>

					<?php
				}else{
				?>
					 <td>
						<label><input type="checkbox" name="cooperative" value="cooperative">cooperative</label>
					</td>

				<?php 
					}?>

					
					<?php if (!empty($aweke)) {
						?>
					<td>
						<label>
							<input type="checkbox" name="aweke" value="awake" checked="checked">Awake
						</label>
					</td>
					<?php
				}else{
					 ?>
					 <td>
						<label><input type="checkbox" name="aweke" value="awake" >Awake</label>
					</td>

					 <?php 
					}?>

					
					<?php if (!empty($unconscious)) {
						?>
					<td>
						<label><input type="checkbox" name="unconscious" value="unconscious" checked="checked">unconscious</label>
					</td>
					<?php
				}else{
					 ?>
					 <td>
						<label><input type="checkbox" name="unconscious" value="unconscious">unconscious</label>
					</td>

					 <?php 
					}?>

					<?php if (!empty($aggressive)) {
						?>
					<td>
						<label><input type="checkbox" name="aggressive" value="Aggressive" checked="checked">Aggressive</label>
					</td>
					<?php
				}else{
					 ?>
					 <td>
						<label><input type="checkbox" name="aggressive" value="Aggressive">Aggressive</label>
					</td>

					 <?php 
					}?>

					
					<td>
						Dehydrated	
					<?php if (!empty($dehydrated)) {
						if ($dehydrated == "yes") {
						?>
					<label><input type="radio" name="dehydrated" value="yes" checked="checked">YES</label>
						<label><input type="radio" name="dehydrated" value="no">NO</label>
					</td>

						<?php }elseif ($dehydrated == "no") {
							?>
					<label><input type="radio" name="dehydrated" value="yes" checked="checked">YES</label>
						<label><input type="radio" name="dehydrated" value="no">NO</label>
					</td>
						
					<?php
					}
				}else{
					 ?>
					<label>
						<input type="radio" name="dehydrated" value="yes" >YES</label>
						<label><input type="radio" name="dehydrated" value="no">NO</label>
					</td>

					 <?php 
					}?>

					
				</tr>

			</table>
			<table width="100%">
				<tr>
					<td style="text-align:right;">
						<b>Special: Pregnant:</b>
					</td>
					<td>
						<input type="text" name="special_pregnant" value="<?php
						 if(!empty($special_pregnantbp))
						echo $special_pregnantbp; ?>">Wk Ga</td>
					<!-- <td style="text-align:left"></td> -->
					<td style="text-align: right;">BP:</td>
					<td><input type="text" name="bp"  value="<?php
						 if(!empty($bp))
						echo $bp?>"> </td>

					<td style="text-align: right;">HR/PR:</td>
					<td><input type="text" name="hr_pr" value="<?php
						 if(!empty($hr_pr))
						echo $hr_pr?>"> </td>

					<td style="text-align: right;">TEMP:</td>
					<td><input type="text" name="temp" value="<?php
						 if(!empty($temp))
						echo $temp?>"> </td>
					<td style="text-align: right;">RR:</td>
					<td><input type="text" name="rr" value="<?php
						 if(!empty($rr))
						echo $rr?>"> </td>
					<td style="text-align: right;">SPO2:</td>
					<td><input type="text" name="spo2" value="<?php
						 if(!empty($spo2))
						echo $spo2?>"> </td>
				</tr>
				<tr>
					<td style="text-align: right;">WT:</td>
					<td><span>
						<input type="text" name="wt" value="<?php
						 if(!empty($wt))
						echo $wt?>"></span><span>kg</span></td>
					<td style="text-align: right;">HT:</td>
					<td><input type="text" name="ht" value="<?php
						 if(!empty($ht))
						echo $ht?>"> </td>
					<td style="text-align: right;">BMI:</td>
					<td><input type="text" name="bmi" value="<?php
						 if(!empty($bmi))
						echo $bmi?>"> </td>
					<td style="text-align: right;">RBG:</td>
					<td><input type="text" name="rgb" value="<?php
						 if(!empty($rgb))
						echo $rgb?>"> </td>
					<!-- <td style="text-align: right;">SPO2:</td>
					<td><input type="text" name="spo2"> </td> -->
				</tr>

			</table>
			<table width="100%">
				<tr>
					<td><b>Mouth Opening: </b></td>
					<td>
						
						<?php 
						if (!empty($normal)){
							?>
						<label>
						<input type="checkbox" name="normal" value="normal" checked="checked">Normal</label>
						</td>	
					<?php		
						}else{
							?>
						<label>
						<input type="checkbox" name="normal" value="normal">Normal</label>
						</td>
							
						<?php 
						}
						?>
						
						<?php 
						if (!empty($limited)) {
							?>
						<td>
						<label><input type="checkbox" name="limited" value="limited" checked="checked">Limited</label></td>
						</td>	
					<?php		
						}else{
							?>
						<td>
						<label><input type="checkbox" name="limited" value="limited">Limited</label></td>
							
						<?php 
						}
						?>


						<?php 
						if (!empty($micrognathia)) {
							if ($micrognathia == "YES") {
						?>

						<td><i>Micrognathia:</i>
						<label><input type="radio" name="micrognathia" value="YES" checked="checked">YES</label>
						<label><input type="radio" name="micrognathia" value="NO">NO</label>
					</td>

					<?php	
					}elseif ($micrognathia == "NO") {
					?>

						<td><i>Micrognathia:</i>
						<label><input type="radio" name="micrognathia" value="YES" >YES</label>
						<label><input type="radio" name="micrognathia" value="NO" checked="checked">NO</label>
					</td>

					
							
					<?php
					}		
						}else{
					?>
						<td><i>Micrognathia:</i>
						<label><input type="radio" name="micrognathia" value="YES" >YES</label>
						<label><input type="radio" name="micrognathia" value="NO" >NO</label>
					</td>

					<?php 
					}
					?>



				<?php 
						if (!empty($next_extension)) {
							if ($next_extension == "yes") {
						?>

					<td><i>Neck Extension:</i>
						<label><input type="radio" name="next_extension" value="yes" checked="checked">YES</label>
						<label><input type="radio" name="next_extension" value="no">NO</label>
					</td>

					<?php	
					}elseif ($next_extension == "no") {
					?>

					<td><i>Neck Extension:</i>
						<label><input type="radio" name="next_extension" value="yes" >YES</label>
						<label><input type="radio" name="next_extension" value="no" checked="checked">NO</label>
					</td>

					
							
					<?php
					}		
						}else{
					?>
					<td><i>Neck Extension:</i>
						<label><input type="radio" name="next_extension" value="yes">YES</label>
						<label><input type="radio" name="next_extension" value="no">NO</label>
					</td>

					<?php 
					}
					?>

				<?php 
						if (!empty($thyromental_distance)) {
							if ($thyromental_distance == "yes") {
						?>

					<td><i><strong>Thyromental distance:</strong></i>
						<label>
							<input type="radio" name="thyromental_distance" value="yes" checked="checked">>6cm</label>
						<label>
							<input type="radio" name="thyromental_distance" value="no"><6cm</label>
					</td>

					<?php	
					}elseif ($thyromental_distance == "no") {
					?>

					<td><i><strong>Thyromental distance:</strong></i>
						<label>
							<input type="radio" name="thyromental_distance" value="yes">>6cm</label>
						<label>
							<input type="radio" name="thyromental_distance" value="no" checked="checked"> <6cm</label>
					</td>

					
							
					<?php
					}		
						}else{
					?>
					<td><i><strong>Thyromental distance:</strong></i>
						<label><input type="radio" name="thyromental_distance" value="yes">>6cm</label>
						<label><input type="radio" name="thyromental_distance" value="no"><6cm</label>
					</td>

					<?php 
					}
					?>
	

				
						

					
					
					<td><i>Teeth:</i>

					<?php 
						if (!empty($loose)) {
					?>
					<label>
						<input type="checkbox" name="loose" value="loose" checked="checked">Loose</label>
					<?php
							
						}else{
					?>
					<label><input type="checkbox" name="loose" value="">Loose</label>
					<?php 
					}
					?>
					
					<?php 
						if (!empty($impant)) {
					?>
						<label>
							<input type="checkbox" name="impant" value="impant" checked="checked">Impant
						</label>
					<?php
							
						}else{
					?>
						<label><input type="checkbox" name="impant" value="impant">Impant</label>
					<?php 
					}
					?>	
					
					<?php 
						if (!empty($normal)) {
					?>
						<label>
							<input type="checkbox" name="normal_teeth" value="normal" checked="checked">Normal
						</label>
					<?php
							
						}else{
					?>
						<label><input type="checkbox" name="impant" value="">Impant</label>
					<?php 
					}
					?>	
						
					</td>

					</tr>
				</table>
				<table width="100%">
					<tr>
						<td style="text-align: right;">CVS</td>
						<td ><input type="text" name="cvs" value="<?php
						 if(!empty($cvs))
						echo $cvs?>"></td>
						<td style="text-align: right;">Lungs</td>
						<td ><input type="text" name="lungs" value="<?php
						 if(!empty($lungs))
						echo $lungs?>"></td>
						<td  style="text-align: right;">System</td>
						<td ><input type="text" name="system" value="<?php
						 if(!empty($system))
						echo $system?>"></td>
					</tr>
			</table>

			<table width="100%">
				<thead>
				
				<tr>
				<th colspan="2">ASA Classification 1,2,3,4,5, E</th>
				<th colspan="2"> MALLAMPATI 1 2 3 4</th>	
				</tr>
				</thead>

				<tr>
					<td style="text-align: right;">Labs:RFT</td>
					<td><input type="text" name="rft" value="<?php
						 if(!empty($rft))
						echo $rft?>"></td>
					<td style="text-align: right;">LFT</td>
					<td><input type="text" name="lft" value="<?php
						 if(!empty($lft))
						echo $lft?>"></td>
					<td style="text-align: right;">Elect</td>
					<td><input type="text" name="elect" value="<?php
						 if(!empty($elect))
						echo $elect?>"></td>
					<td style="text-align: right;">Hb level</td>
					<td><input type="text" name="hb_level" value="<?php
						 if(!empty($hb_level))
						echo $hb_level?>"></td>
					<td style="text-align: right;">Blood Group</td>
					<td><input type="text" name="blood_group" value="<?php
						 if(!empty($blood_group))
						echo $blood_group?>"></td>
				</tr>
				<tr>
					<td style="text-align: right;">Blood Gas:FiO2</td>
					<td><input type="text" name="fio2" value="<?php
						 if(!empty($fio2))
						echo $fio2?>"></td>
					<td style="text-align: right;">SaO2</td>
					<td><input type="text" name="sao2" value="<?php
						 if(!empty($sao2))
						echo $sao2?>"></td>
					<td style="text-align: right;">BE</td>
					<td><input type="text" name="be" value="<?php
						 if(!empty($be))
						echo $be?>"></td>
					<td style="text-align: right;">Bic</td>
					<td><input type="text" name="bic" value="<?php
						 if(!empty($bic))
						echo $bic?>"></td>
					<td style="text-align: right;">PCO2</td>
					<td><input type="text" name="pco2" value="<?php
						 if(!empty($pco2))
						echo $pco2?>"></td>
				</tr>

				<tr>
					<td style="text-align: right;">PH:</td>
					<td><input type="text" name="ph" value="<?php
						 if(!empty($ph))
						echo $ph?>"></td>
					<td style="text-align: right;">CXR</td>
					<td><input type="text" name="cxr" value="<?php
						 if(!empty($cxr))
						echo $cxr?>"></td>
					<td style="text-align: right;">ECG/ECHO/CTC SKAN</td>
					<td><input type="text" name="ecg_echo_ctc_scan" value="<?php
						 if(!empty($ecg_echo_ctc_scan))
						echo $ecg_echo_ctc_scan?>"></td>
					<!-- <td style="text-align: right;">Anethetic Risk:</td>
					<td><input type="text" name="anethetic_risk:"></td>
					<td style="text-align: right;">Proposed Anathesia</td>
					 <!-<td><input type="text" name="proposed_anethesia"></td> -->
				</tr>
				<tr>
					<td style="text-align: right;">Anethetic Risk:</td>
					<td colspan="4">
						<textarea cols="2" rows="2" name="anethetic_risk"><?php
						 if(!empty($anethetic_risk))
						echo $anethetic_risk ?></textarea>
					</td>
						
					<td style="text-align: right;">Proposed Anathesia</td>
					<td colspan="4">
						<textarea cols="2" rows="2" name="proposed_anethesia"><?php
						 if(!empty($proposed_anethesia))
						echo $proposed_anethesia ?>"</textarea>
					</td>
					
				</tr>
				<tr>
					<td style="text-align: right;">Premedicaton/Orders: Fasting For:</td>
					<td colspan="3">
						<input type="text" name="fasting_for" value="<?php
						 if(!empty($fasting_for))
						echo $fasting_for?>">
					</td>

					<td style="text-align: right;">Blood unit</td>
					<td colspan="3">
						<input type="text" name="blood_unit" value="<?php
						 if(!empty($blood_unit))
						echo $blood_unit?>">
					</td>

				</tr>
				<tr>
					<td style="text-align: right;">Reviewed By</td>
					<td colspan="2">
						<input type="text" name="reviewed_by" value="<?php
						 if(!empty($reviewed_by))
						echo $reviewed_by?>">
					</td>
					<td style="text-align: right;">Signature</td>
					<td colspan="2">
						<input type="text" name="">
					</td>
					<td style="text-align: right;">Date</td>
					<td colspan="2">
						<input type="text" id='date2' name="date" value="<?php
						 if(!empty($date))
						echo $date?>">
					</td>
				</tr>

				<tr>
					<td>ANESTHESIOLOGIST OPINION:</td>
					<td colspan="5">
						<textarea rows="4" cols="5" name="anethisiology_opinion"><?php
						 if(!empty($anethisiology_opinion ))
						echo $anethisiology_opinion ?></textarea>
					</td>
					<td style="text-align: right;">name</td>
					<td colspan="2">
						<input type="text" name="name" value="<?php
						 if(!empty($name))
						echo $name?>">
					</td>
				</tr>
			</table>

			<table width="100%">
				<tr>
					<td colspan="1"><strong>INTRAOP RECORD:</strong></td>
					<td style="text-align: right;">Has Patient Fasted</td>

					<?php if (!empty($patient_fasted)) {
					?>


					<td>

					<?php 
						if (!empty($patient_fasted)) {
								if ($patient_fasted =="YES") {
					?>
					<label><input type="radio" name="patient_fasted" value="YES" checked="checked">YES</label>
						<label><input type="radio" name="patient_fasted" value="NO">NO</label>

					<?php
						}else if ($patient_fasted == "NO") {
						?>	

						<label><input type="radio" name="patient_fasted" value="YES" >YES</label>
						<label><input type="radio" name="patient_fasted" value="NO" checked="checked">NO</label>

					<?php	}

					}else{
						?>

					<label><input type="radio" name="patient_fasted" value="YES" >YES</label>
						<label>
							<input type="radio" name="patient_fasted" value="NO" >NO
						</label>	

				<?php
				}	
				?>	
										
					</td>
					<?php }else{
						?>
					<td>
						<label><input type="radio" name="patient_fasted" value="YES">YES</label>
						<label><input type="radio" name="patient_fasted" value="NO">NO</label>
					</td>	
					<?php
				}
					 ?>
					
					


				
					<td style="text-align: right;">
						
						<?php 
						if (!empty($elective_surgery)) {
						 ?>
						<label>ELECTIVE SURGERY
							<input type="checkbox" name="elective_surgery" value="elective surgery" checked="checked" />
						</label>
						 
						 <?php
						}else{
						?>
						<label>
							ELECTIVE SURGERY
							<input type="checkbox" name="elective_surgery" value="elective surgery">
						</label>	
				<?php
				}?>
						

					</td>					


				<?php if (!empty($emergency_surgery)) {
					?>
					<td style="text-align: right;">
						
						<label>EMERGENCY SURGERY<input type="checkbox" name="emergency_surgery" value="elective surgery"></label>
					</td>
					<?php }else{
						?>
					<td style="text-align: right;">
						
						<label>EMERGENCY SURGERY<input type="checkbox" name="emergency_surgery" value="elective surgery"></label>
					</td>	
					<?php
				}
					 ?>
					
					
						
				</tr>
				<tr>
					<td colspan="1"><strong>Intraoperative record:</strong></td>
					<td style="text-align: right;">IV Sites</td>
					<td colspan="2">
						<label><input type="text" name="ivsites" value="<?php
						 if(!empty($ivsites))
						echo $ivsites?>"></label>
						
					</td>
					<td style="text-align: right;">
						central Line
					</td>	
					<td colspan="2"><input type="text" name="central_line" value="<?php
						 if(!empty($central_line))
						echo $central_line?>"></label>
					</td>
				</tr>
				<tr>
					<td><strong>Monitoring</strong></td>


					<?php if (!empty($spo2_morinitoring)) {
					?>
					<label>
					<td style="text-align: right;">Spo2</td>
					<td><input type="checkbox" name="spo2_morinitoring" value="SpO2" checked="checked"></td>
				</label>
					<?php }else{
						?>
					<label>
					<td style="text-align: right;">Spo2</td>
					<td><input type="checkbox" name="spo2_morinitoring" value="SpO2"></td>
				</label>	
					<?php
				}
					 ?>
					

				
				<td style="text-align: right;">NIBP</td>

				<?php if (!empty($nbp)) {
					?>
					<td><input type="checkbox" name="nbp" value="NIBP" checked="checked"></td>
					<?php }else{
						?>
					<td><input type="checkbox" name="nbp" value="NIBP"></td>	
					<?php
				}
					 ?>
					

					<!-- <td><input type="checkbox" name="nbp" value="NIBP"></td> -->

				<?php if (!empty($ecg)) {
					?>
					<td style="text-align: right;">ECG</td>
					<td>
						<input type="checkbox" name="ecg" value="ECG" checked="checked">
					</td>

					<?php }else{
						?>
					<td style="text-align: right;">ECG</td>
					<td><input type="checkbox" name="ecg" value="ECG"></td>	
					<?php
				}
				?>	
					
				
				<?php if (!empty($etco2)) {
					?>
						<td style="text-align: right;">ETCO2</td>
					<td><input type="checkbox" name="etco2" value="ETCO2" checked="checked"></td>

					<?php }else{
						?>
						<td style="text-align: right;">ETCO2</td>
					<td><input type="checkbox" name="etco2" value="ETCO2"></td>	
					<?php
				}
				?>

				<?php if (!empty($gases)) {
					?>
					<td style="text-align: right;">Gases</td>
					<td><input type="checkbox" name="gases" value="Gases"></td>
					<?php }else{
						?>
					<td style="text-align: right;">Gases</td>
					<td><input type="checkbox" name="gases" value="Gases"></td>	
					<?php
				}
				?>

					

					<td style="text-align: right;">Position</td>
					<td><input type="text" name="position" value="<?php
						 if(!empty($position))
						echo $position?>"></td>

			</tr>
			</table>

			<table width="100%">
				<tr>
					<th>
						TYPE OF ANAESTHESIA
					</th>

				</tr>
				<tr>
					<td>
						<strong>1. General anaesthesia</strong>
					</td>
				</tr>

				<tr>
					<td colspan="2">Induction:

						<?php 
						if (!empty($iv)) {
							?>
							<label>
								<input type="checkbox" name="iv" value="IV" checked="checked">IV
							</label>
						<?php }else{
						 ?>
						 <label>
								<input type="checkbox" name="iv" value="IV">IV
						</label>

						<?php } 
						?>
					
					<?php 
						if (!empty($inhalational)) {
							?>
							<label>
								<input type="checkbox" name="inhalational" value="Inhalational" 
								checked="checked">Inhalational
							</label>
						<?php }else{
						 ?>
						 <label><input type="checkbox" name="inhalational" value="Inhalational">Inhalational</label>

						<?php } 
						?>
					
					<?php 
						if (!empty($rsi)) {
							?>
							<label>
								<input type="checkbox" name="rsi" value="rsi" checked="checked">RSI
							</label>
					</td>
						<?php }else{
						 ?>
						 <label><input type="checkbox" name="rsi" value="rsi">RSI</label>
					</td>

						<?php } 
						?>
			
				<?php 
						if (!empty($intubation)) {
							if ($intubation == "Awake") {
								?>
					<td colspan="2">intubation:
						<label><input type="radio" name="intubation" value="Awake" checked="checked">Awake</label>
						<label><input type="radio" name="intubation" value="sleep">Sleep</label>
					</td>
							<?php
						}elseif ($intubation == "Sleep") {
							?>

						<td colspan="2">intubation:
						<label><input type="radio" name="intubation" value="Awake">Awake</label>
						<label><input type="radio" name="intubation" value="sleep" checked="checked">Sleep</label>
					</td>	
							<?php 
						}
							?>
						
					</td>
						<?php }else{
						 ?>
						 <td colspan="2">intubation:
						<label><input type="radio" name="intubation" value="Awake">Awake</label>
						<label><input type="radio" name="intubation" value="sleep">Sleep</label>
					</td>

						<?php } 
						?>
					


					<?php 
						if (!empty($comment)) {
							if ($comment == "Easy") {
							?>

					<td colspan="2">Comment:
						<label>
							<input type="radio" name="comment" value="Easy" checked="checked">Easy</label>
						<label><input type="radio" name="comment" value="Difficult">Difficult</label>
					</td>

						<?php
						}elseif ($comment=="Difficult"){
						?>

						<td colspan="2">Comment:
						<label><input type="radio" name="comment" value="Easy">Easy</label>
						<label><input type="radio" name="comment" value="Difficult" checked="checked">Difficult</label>

						</td>
						
						<?php 
						}
							
						
						 }else{
						 ?>

						<td colspan="2">Comment:
						<label><input type="radio" name="comment" value="Easy">Easy</label>
						<label><input type="radio" name="comment" value="Difficult" >Difficult</label>
						</td>
					

						<?php 
					} 
						?>

				</tr>

				<tr>
					<td colspan="2">Circuit:

					<?php 
						if (!empty($circle)) {
						?>
						<label><input type="checkbox" name="circle" value="Circle" checked="checked">Circle</label>
						<?php
					}else{
					 ?>
					 <label><input type="checkbox" name="circle" value="Circle">Circle</label>
					 <?php
					 } 
					 ?>	
					
					<?php 
						if (!empty($t_iece)) {
						?>
							<label><input type="checkbox" name="t_iece" value="T-piece" checked="checked"> T-piece</label>
						<?php
					}else{
					 ?>
					 	<label><input type="checkbox" name="t_iece" value="T-piece">T-piece</label>
					 <?php
					 } 
					 ?>

					
					
						
					</td>
					<td>Airway:

					<?php 
						if (!empty($mask)) {
						?>
						<label><input type="checkbox" name="mask" value="Mask" checked="checked">Mask</label>
						<?php
					}else{
					 ?>
					 <label><input type="checkbox" name="mask" value="Mask">Mask</label>
					 <?php
					 } 
					 ?>
						
					<?php 
						if (!empty($lma)) {
						?>
						<label><input type="checkbox" name="lma" value="LMA" checked="checked">LMA</label>
						<?php
					}else{
					 ?>
					 <label><input type="checkbox" name="lma" value="LMA">LMA</label>
					 <?php
					 } 
					 ?> 
						

					</td>
					<td style="text-align: right">Nasal</td>
							<td><input type="text" name="nasal" value="<?php
						 if(!empty($nasal))
						echo $nasal?>"></td>
					
					<td >ETT:</td>
					<td style="text-align: right">Type</td>
					<td>
						<input type="text" name="type" value="" value="<?php
						 if(!empty($type))
						echo $type?>">
					</td>
					<td style="text-align: right">Size</td>
					<td>
						<input type="text" name="size" value="<?php
						 if(!empty($size))
						echo $size?>">
					</td>
					
				</tr>

			</table>
			<table width="100%">
				<tr>
					<td colspan="2">Ventillation:

					<?php 
					if (!empty($spont)) {
					?>
					<label><input type="checkbox" name="spont" value="Spont" checked="checked">Spont</label>
					<?php 
				}else{
					?>
					<label><input type="checkbox" name="spont" value="Spont">Spont</label>
					<?php 
				}
					 ?>
					

				<?php 
					if (!empty($cont)) {
					?>
					<label><input type="checkbox" name="cont" value="Cont" checked="checked">Cont</label>
					<?php 
				}else{
					?>
					<label><input type="checkbox" name="cont" value="Cont">Cont</label>
					<?php 
				}
					 ?>

					
						
						
					</td>
					
					<td style="text-align: right ">RR</td>
							<td style="width:20%">
								<input type="text" name="rr_last" value="<?php
						 if(!empty($rr_last))
						echo $rr_last?>"></td>
					
					
					<td style="text-align: right">TV</td>
					<td>
						<input type="text" name="tv" value="<?php
						 if(!empty($tv))
						echo $tv?>">
					</td>
					<td style="text-align: right">Press</td>
					<td>
						<input type="text" name="press" value="<?php
						 if(!empty($press))
						echo $press?>">
					</td>
					<td style="text-align: right">PEEP</td>
					<td>
						<input type="text" name="peep" value="<?php
						 if(!empty($peep))
						echo $peep?>">
					</td>
					<td style="text-align: right">I:E</td>
					<td>
						<input type="text" name="ie" value="<?php
						 if(!empty($ie))
						echo $ie?>">
					</td>	
				</tr>

			</table>
			<table width="100%">
				<tr>
					<td colspan="2">Anesth-Maintainance:

					<?php 
					if (!empty($air)) {
					?>
					<label><input type="checkbox" name="air" value="air" checked="checked">Air</label>
					<?php }else{
					 ?>	
					<label><input type="checkbox" name="air" value="air">Air</label>
					<?php

					 }?>
					
					<?php 
					if (!empty($o2)) {
					?>
					<label><input type="checkbox" name="o2" value="O2">O2</label>
					<?php }else{
					 ?>	
					<label><input type="checkbox" name="o2" value="O2">O2</label>
					<?php

					 }?>
					
					<?php 
					if (!empty($haloth)) {
					?>
					<label><input type="checkbox" name="haloth" value="Haloth" checked="checked">Haloth</label>
					<?php }else{
					 ?>	
					<label><input type="checkbox" name="haloth" value="Haloth">Haloth</label>
					<?php

					 }?>
						
		
					<?php 
					if (!empty($isofl)) {
					?>
					<label><input type="checkbox" name="isofl" value="Isofl" checked="checked">Isofl</label>
					<?php }else{
					 ?>	
					<label><input type="checkbox" name="isofl" value="Isofl">Isofl</label>
					<?php

					 }?>
						
					<?php 
					if (!empty($sevofl)) {
					?>
					<label><input type="checkbox" name="sevofl" value="Sevofl" checked="checked">Sevofl</label>
					<?php }else{
					 ?>	
					<label><input type="checkbox" name="sevofl" value="Sevofl">Sevofl</label>
					<?php

					 }?>	
						
					</td>
					
					<td style="text-align: right">Others</td>
					<td>
						<input type="text" name="others" value="<?php
						 if(!empty($others))
						echo $others?>">
					</td>
					
				</tr>

			</table>


			<table width="100%">
				<tr>
					<th>
					<strong>2.Local/Regional:</strong>
				</th>
			</tr>

			<tr>
			<td style="text-align: right;">
					Type
					
				</td>				
				<td>
				<input type="text" name="local_type" value="<?php
						 if(!empty($local_type))
						echo $local_type?>">
			</td>
			<td style="text-align: right;">
					Agent:		
				</td>				
				<td>
				<input type="text" name="local_type" value="<?php
						 if(!empty($agent))
						echo $agent?>">
			</td>
			
			<td style="text-align: right;">
					Conc
					
				</td>				
				<td>
				<input type="text" name="conc" value="<?php
						 if(!empty($conc))
						echo $conc?>">
			</td>
				
			<td style="text-align: right;">
					amount
					
				</td>				
				<td>
				<input type="text" name="amount" value="<?php
						 if(!empty($amount))
						echo $amount?>">
			</td>
			<td style="text-align: right;">
					Position
				</td>				
				<td>
				<input type="text" name="position" value="<?php
						 if(!empty($position))
						echo $position?>">
			</td>

			</tr>
			<tr>
				<td style="text-align: right;">Comments</td>
				<td colspan="5">
					<textarea name="comments" cols="7" rows="3"><?php
						 if(!empty($comments))
						echo $comments?></textarea>
				</td>

				<td style="text-align: right;" >Done By</td>
				<td colspan="4">
				<input type="text" name="done_by" value="<?php
						 if(!empty($done_by))
						echo $done_by?>"> 
			</td>
			</tr>
			</table>

			<div>
				<span>
					<input type="submit" name="submit" value="SAVE" class="art-button-green">	
				</span>
				<span>
					<a href="preview_anethesia.php?registration_id=<?=$Registration_ID; ?>" target="_blank" class="art-button-green">PREVIEW FILE</a>

					<!-- <input type="submit" id="preview" name="submit" value="Preview" class="art-button-green"> -->

				</span>
				
			</div>
		</form>

	</fieldset>
</center>


<script type="text/javascript" src="jquery-3.2.1.min.js"> </script>

<script src="css/jquery.datetimepicker.js"></script>
<script type="text/javascript">
	

	$(document).ready(function(e){

        $('#date2').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
         });
        

    });
	

	$("#anethesia_form").submit(function(e){
		e.preventDefault()
		var form_data = $("#anethesia_form").serialize();

		// alert(form_data);

		$.ajax({
			url:"save_anethesia.php",
			type:"post",
			dataType:'text',
			data:form_data,
			dataType:"text",
			success:function(data){
				alert(data)
			},error: function (data, textStatus, jqXHR) { alert(data); }
		})
		
	})


$("#preview").click(function(e){
	e.preventDefault()

	var registration_id = $("#registration").val();
	alert(registration_id)
})

</script>

