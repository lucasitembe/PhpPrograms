<style>
    #userinfo td,tr{
        height:20px ;
        border:none !important; 
    }
    #userinfo tr{
        border:none !important;
    }
    .headerTitle{
        background:#ccc;padding:5px;font-size: x-large;font-weight:bold;text-align:left;  
        width:100%;    
    }
    .modificationStats:hover{
        text-decoration: underline;
        cursor:pointer;
        color: rgb(145,0,0);
    }

    .prevHistory:hover{
        text-decoration: underline;
        cursor:pointer;
        color: rgb(145,0,0); 
    }
    .no_color{
        color:inherit;
        text-decoration:none;  
    }

    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style> 

<?php
	include("./includes/header_general.php");
	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}

	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}
	echo '<a href="Patient_Record_Review_General.php?consultation_ID='.$consultation_ID.'&Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&Patient_Payment_Item_List_ID='.$Patient_Payment_Item_List_ID.'" class="art-button-green">BACK</a><br/><br/>';

	$doc = new DOMDocument;
	$doc->load("http://192.168.1.233/ehms_migration/oracle/patient_file?id=$Registration_ID");

	//patient details
	$errorMessage = $doc->getElementsByTagName('errorMessage');
	if(strtolower($errorMessage->item(0)->nodeValue) != 'nothing found.'){
		$FULL_NAME = $doc->getElementsByTagName('FULL_NAME');
		$AGI = $doc->getElementsByTagName('AGI');
		$GENDER = $doc->getElementsByTagName('GENDER');
		$GUARANTOR = $doc->getElementsByTagName('GUARANTOR');
?>
		<fieldset>
			<table width="100%">
				<tr>
					<td width="10%" style="text-align: right;"><b>Patient Name </b></td>
					<td><?php echo $FULL_NAME->item(0)->nodeValue; ?></td>
					<td width="10%" style="text-align: right;"><b>Patient Age </b></td>
					<td width="10%"><?php echo $AGI->item(0)->nodeValue; ?></td>
					<td width="10%" style="text-align: right;"><b>Gender </b></td>
					<td width="10%"><?php echo $GENDER->item(0)->nodeValue; ?></td>
					<td width="10%" style="text-align: right;"><b>Sponsor Name </b></td>
					<td width="15%"><?php echo $GUARANTOR->item(0)->nodeValue; ?></td>
					<td width="7%">
						<input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Preview_Report(<?php echo $Registration_ID; ?>)">
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset style='overflow-y: scroll; height: 400px;' id='Items_Fieldset'>
			<?php
				$data_object = $doc->getElementsByTagName('data_object');
				$POSTING_NO = $doc->getElementsByTagName('POSTING_NO');
				$POSTING_DATE = $doc->getElementsByTagName('POSTING_DATE');
				$MAIN_COMPLAIN = $doc->getElementsByTagName('MAIN_COMPLAIN');
				$PRESENT_ILNESS = $doc->getElementsByTagName('PRESENT_ILNESS');
				$G_OBSERVATION = $doc->getElementsByTagName('G_OBSERVATION');
				$S_OBSERVATION = $doc->getElementsByTagName('S_OBSERVATION');
				$P_DIGNOSIS = $doc->getElementsByTagName('P_DIGNOSIS');
				$D_DIGNOSIS = $doc->getElementsByTagName('D_DIGNOSIS');
				$INVEST = $doc->getElementsByTagName('INVEST');
				$R_INVEST = $doc->getElementsByTagName('R_INVEST');
				$F_DIAGNOSIS = $doc->getElementsByTagName('F_DIAGNOSIS');
				$TREATMENT = $doc->getElementsByTagName('TREATMENT');
				$OTHERS = $doc->getElementsByTagName('OTHERS');
				$AMPLITUTE = $doc->getElementsByTagName('AMPLITUTE');
				$PROCIJA = $doc->getElementsByTagName('PROCIJA');
				$BILL_NO = $doc->getElementsByTagName('BILL_NO');
				$DOCT_CODE = $doc->getElementsByTagName('DOCT_CODE');
				$DOCT_NAME = $doc->getElementsByTagName('DOCT_NAME');
				$PROC_COMMENT = $doc->getElementsByTagName('PROC_COMMENT');
				$INVE_COMMENT = $doc->getElementsByTagName('INVE_COMMENT');
				$R_RADI = $doc->getElementsByTagName('R_RADI');
				$OUTIN = $doc->getElementsByTagName('OUTIN');
				$D_CODES = $doc->getElementsByTagName('D_CODES');
				$OTHERS = $doc->getElementsByTagName('OTHERS');
				$R_PROC = $doc->getElementsByTagName('R_PROC');
				
				for ($i = 0; $i < $data_object->length; $i++) {
					$Title = '<b>VISIT DATE - '.$POSTING_DATE->item($i)->nodeValue.', [code#='.$POSTING_NO->item($i)->nodeValue.'],/'.$DOCT_NAME->item($i)->nodeValue.' ('.$OUTIN->item($i)->nodeValue.'-'.$GUARANTOR->item($i)->nodeValue.')</b>';
			?>
				<table width="100%">
					<tr><td><?php echo $Title; ?></td></tr>
					<?php
						if(substr($MAIN_COMPLAIN->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($MAIN_COMPLAIN->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[Main Complain]'.substr($MAIN_COMPLAIN->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $MAIN_COMPLAIN->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[Main Complain]'.$MAIN_COMPLAIN->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<?php
						if(substr($PRESENT_ILNESS->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($PRESENT_ILNESS->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[History of Present Illness]'.substr($PRESENT_ILNESS->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $PRESENT_ILNESS->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[History of Present Illness]'.$PRESENT_ILNESS->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<?php
						if(substr($G_OBSERVATION->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($G_OBSERVATION->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[General Examination /Observation]'.substr($G_OBSERVATION->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $G_OBSERVATION->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[General Examination /Observation]'.$G_OBSERVATION->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<?php
						if(substr($INVEST->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($INVEST->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[Investigation]'.substr($INVEST->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $INVEST->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[Investigation]'.$INVEST->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<?php
						if(substr($R_RADI->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($R_RADI->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[Radiology Result]'.substr($R_RADI->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $R_RADI->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[Radiology Result]'.$R_RADI->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<?php
						if(substr($R_INVEST->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($R_INVEST->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[Laboratory Result]'.substr($R_INVEST->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $R_INVEST->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[[Laboratory Result]'.$R_INVEST->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<tr>
						<td>
							[Final Diagnosis]
						<?php
							if(substr($F_DIAGNOSIS->item($i)->nodeValue, 1,2) == '#;'){
								echo substr($F_DIAGNOSIS->item($i)->nodeValue, 3); 
							}else{
								echo $F_DIAGNOSIS->item($i)->nodeValue; 
							}
						?>
						</td>
					</tr>
					<?php
						if(substr($PROCIJA->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($PROCIJA->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[Procedure]'.substr($PROCIJA->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $PROCIJA->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[Procedure]'.$PROCIJA->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<?php
						if(substr($R_PROC->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($R_PROC->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[Procedure Results]'.substr($R_PROC->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $R_PROC->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[Procedure Results]'.$R_PROC->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<?php
						if(substr($PROC_COMMENT->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($PROC_COMMENT->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[Procedure Comment]'.substr($PROC_COMMENT->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $PROC_COMMENT->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[Procedure Comment]'.$PROC_COMMENT->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<?php
						if(substr($TREATMENT->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($TREATMENT->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[Treatment]'.substr($TREATMENT->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $TREATMENT->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[Treatment]'.$TREATMENT->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
					<?php
						if(substr($OTHERS->item($i)->nodeValue, 1,2) == '#;'){
							$value = substr($OTHERS->item($i)->nodeValue, 3);
							if($value != null && $value != ''){
								echo '<tr><td>[Remarks]'.substr($OTHERS->item($i)->nodeValue, 3).'</td></tr>'; 
							}
						}else{
							$value = $OTHERS->item($i)->nodeValue;
							if($value != null && $value != ''){
								echo '<tr><td>[Remarks]'.$OTHERS->item($i)->nodeValue.'</td></tr>'; 
							}
						}
					?>
				</table><br/>
			<?php
				}
			?>
		</fieldset>
<?php
	}else{
?>
	<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
	<fieldset style="background-color: white;">
		<center>
			<h3><b>NO RECORD FOUND!</b></h3>
		</center>
	</fieldset>
<?php
	}
?>
<script type="text/javascript">
    function Preview_Report(Registration_ID){
        var winClose=popupwindow('previouspatientfilereport.php?Registration_ID='+Registration_ID, 'PREVIOUS PATIENT FILE', 1200, 500);
    }
    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>