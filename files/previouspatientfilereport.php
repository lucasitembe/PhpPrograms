<?php
	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}
	$htm = '';

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

	$doc = new DOMDocument;
	$doc->load("http://192.168.1.233/ehms_migration/oracle/patient_file?id=$Registration_ID");

	//patient details
	$errorMessage = $doc->getElementsByTagName('errorMessage');
	if(strtolower($errorMessage->item(0)->nodeValue) != 'nothing found.'){
		$FULL_NAME = $doc->getElementsByTagName('FULL_NAME');
		$AGI = $doc->getElementsByTagName('AGI');
		$GENDER = $doc->getElementsByTagName('GENDER');
		$GUARANTOR = $doc->getElementsByTagName('GUARANTOR');

		$htm .=	'<table width="100%">
					<tr>
						<td width="50%" style="text-align: left;"><b>Patient Name </b>'.$FULL_NAME->item(0)->nodeValue.'</td>
						<td width="50%" style="text-align: left;"><b>Patient Age </b>'.$AGI->item(0)->nodeValue.'</td>
					</tr>
					<tr>
						<td style="text-align: left;"><b>Gender </b>'.$GENDER->item(0)->nodeValue.'</td>
						<td style="text-align: left;"><b>Sponsor Name </b>'.$GUARANTOR->item(0)->nodeValue.'</td>
					</tr>
				</table><br/>';

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
				
				for ($i = 0; $i < $data_object->length; $i++) {
					$Title = '<b>VISIT DATE - '.$POSTING_DATE->item($i)->nodeValue.', [code#='.$POSTING_NO->item($i)->nodeValue.'],/'.$DOCT_NAME->item($i)->nodeValue.' ('.$OUTIN->item($i)->nodeValue.'-'.$GUARANTOR->item($i)->nodeValue.')</b>';
			
					$htm .=	'<table width="100%"><tr><td><u>'.$Title.'</u></td></tr><tr><td>[Main Complain]';
					if(substr($MAIN_COMPLAIN->item($i)->nodeValue, 1,2) == '#;'){
						$htm .= substr($MAIN_COMPLAIN->item($i)->nodeValue, 3); 
					}else{
						$htm .= $MAIN_COMPLAIN->item($i)->nodeValue; 
					}

					$htm .= '</td></tr>';
					
					if(substr($PRESENT_ILNESS->item($i)->nodeValue, 1,2) == '#;'){
						$value = substr($PRESENT_ILNESS->item($i)->nodeValue, 3);
						if($value != null && $value != ''){
							$htm .= '<tr><td>[History of Present Illness]'.substr($PRESENT_ILNESS->item($i)->nodeValue, 3).'</td></tr>'; 
						}
					}else{
						$value = $PRESENT_ILNESS->item($i)->nodeValue;
						$htm .= '<tr><td>[History of Present Illness]'.$PRESENT_ILNESS->item($i)->nodeValue.'</td></tr>'; 
					}
					
					if(substr($G_OBSERVATION->item($i)->nodeValue, 1,2) == '#;'){
						$value = substr($G_OBSERVATION->item($i)->nodeValue, 3);
						if($value != null && $value != ''){
							$htm .= '<tr><td>[General Examination /Observation]'.substr($G_OBSERVATION->item($i)->nodeValue, 3).'</td></tr>'; 
						}
					}else{
						$value = $G_OBSERVATION->item($i)->nodeValue;
						$htm .= '<tr><td>[General Examination /Observation]'.$G_OBSERVATION->item($i)->nodeValue.'</td></tr>'; 
					}
					
					$htm .=	'<tr><td>[Investigation]';
					if(substr($INVEST->item($i)->nodeValue, 1,2) == '#;'){
						$htm .= substr($INVEST->item($i)->nodeValue, 3); 
					}else{
						$htm .= $INVEST->item($i)->nodeValue; 
					}
					$htm .= '</td></tr>';
					if(substr($R_RADI->item($i)->nodeValue, 1,2) == '#;'){
						$value = substr($R_RADI->item($i)->nodeValue, 3);
						if($value != null && $value != ''){
							$htm .= '<tr><td>[Radiology Result]'.substr($R_RADI->item($i)->nodeValue, 3).'</td></tr>'; 
						}
					}else{
						$value = $R_RADI->item($i)->nodeValue;
						$htm .= '<tr><td>[Radiology Result]'.$R_RADI->item($i)->nodeValue.'</td></tr>'; 
					}
					
					if(substr($R_INVEST->item($i)->nodeValue, 1,2) == '#;'){
						$value = substr($R_INVEST->item($i)->nodeValue, 3);
						if($value != null && $value != ''){
							$htm .= '<tr><td>[Laboratory Result]'.substr($R_INVEST->item($i)->nodeValue, 3).'</td></tr>'; 
						}
					}else{
						$value = $R_INVEST->item($i)->nodeValue;
						$htm .= '<tr><td>[[Laboratory Result]'.$R_INVEST->item($i)->nodeValue.'</td></tr>'; 
					}
					
					$htm .= '<tr><td>[Final Diagnosis]';
					if(substr($F_DIAGNOSIS->item($i)->nodeValue, 1,2) == '#;'){
						$htm .= substr($F_DIAGNOSIS->item($i)->nodeValue, 3); 
					}else{
						$htm .= $F_DIAGNOSIS->item($i)->nodeValue; 
					}
					$htm .= '</td></tr>';
					if(substr($TREATMENT->item($i)->nodeValue, 1,2) == '#;'){
						$value = substr($TREATMENT->item($i)->nodeValue, 3);
						if($value != null && $value != ''){
							$htm .= '<tr><td>[Treatment]'.substr($TREATMENT->item($i)->nodeValue, 3).'</td></tr>'; 
						}
					}else{
						$value = $TREATMENT->item($i)->nodeValue;
						$htm .= '<tr><td>[Treatment]'.$TREATMENT->item($i)->nodeValue.'</td></tr>'; 
					}
					$htm .= '</table><br/>';
				}
		}else{
			$htm .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
			$htm .= '<center><h3><b>NO RECORD FOUND!</b></h3></center>';
	}

	include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>