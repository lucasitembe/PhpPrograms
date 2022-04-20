<?php
include("./includes/connection.php");
$consultation_ID = $_GET['consultation_ID'];
$Registration_ID = $_GET['Registration_ID'];
$Bill_ID = $_GET['Bill_ID'];
$typecode = $_GET['typecode'];
    //get the current date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

//    select patient information

    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,pr.patient_signature,
                                    Gender,pr.Region,pr.Country,pr.Diseased,pr.District,pr.Ward,pr.Patient_Picture,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID,sp.Postal_Address,sp.Benefit_Limit
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    while ($row = mysqli_fetch_array($select_Patient)) {
        $Registration_ID = $row['Registration_ID'];
        $Old_Registration_Number = $row['Old_Registration_Number'];
        $Title = $row['Title'];
        $Patient_Name = $row['Patient_Name'];
        $Sponsor_ID = $row['Sponsor_ID'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Gender = $row['Gender'];
        $Country = $row['Country'];
        $Patient_Picture = $row['Patient_Picture'];
        $Deseased = ucfirst(strtolower($row['Diseased']));
        $Sponsor_Postal_Address = $row['Postal_Address'];
        $Benefit_Limit = $row['Benefit_Limit'];
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
        // echo $Ward."  ".$District."  ".$Ward; exit;
        if (isset($row['patient_signature'])) {
            $p_signature = "<img width='120' height='40' style='padding: 0;' src='../esign/patients_signature/" . $row['patient_signature'] . "' >";
        } else {
            $p_signature = '________________';
        }
    }

    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";


    if (isset($_SESSION['userinfo']['Employee_ID'])) {
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }

$Folio_No = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Folio_No FROM tbl_claim_folio WHERE  Bill_ID = '$Bill_ID'"))['Folio_No'];

    $data = "<center>
	<p style='text-align:right;'>Folio No: ".$Folio_No." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bill No: ".$Bill_ID."</p>
	<table width='100%' style='background: #006400 !important;color: white;'>
	    <tr>
		<td>
		    <center>
			" . strtoupper($Patient_Name) . ", " . strtoupper($Gender) . ", " . ($age) . ", " . strtoupper($Guarantor_Name) . ", Reg #:" . $Registration_ID . "
		    </center>
		</td>
	    </tr>

	</table>
        </center><br/>";

	$inpatient_outpatient_status = 'OUTPATIENT DETAILS';

	if($typecode == 'IN'){
		$inpatient_outpatient_status = 'INPATIENT DETAILS';
	}else if($typecode == 'OUT'){
		$inpatient_outpatient_status = 'OUTPATIENT DETAILS';
	}
    $data .= '<div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center " id="inpatient">
        <b align="center">'.$inpatient_outpatient_status.'</b>
    </div><br/>';

/*
	$select_patients = "SELECT 0 as Patient_Payment_Item_List_ID, 0 as Patient_Payment_ID,Employee_Name, c.consultation_ID,ch.employee_ID, ch.cons_hist_Date,consultation_histry_ID,ch.course_of_injuries, c.Registration_ID
			    FROM tbl_consultation c,tbl_consultation_history ch, tbl_patient_payment_item_list ppl,tbl_employee e WHERE
			    c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and
                            c.consultation_ID=ch.consultation_ID and
                            e.Employee_ID=ch.employee_ID and
			    c.consultation_ID = '$consultation_ID'";*/

					// $select_patients = "SELECT ch.consultation_ID, ch.maincomplain, ch.course_of_injuries, c.Registration_ID, ch.consultation_histry_ID, ch.cons_hist_Date, ch.employee_ID, e.Employee_Name FROM tbl_consultation_history ch, tbl_patient_payments pp, tbl_patient_payment_item_list ppil, tbl_consultation c, tbl_employee e WHERE ch.consultation_id = c.consultation_ID AND ppil.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID AND pp.Patient_Payment_ID = ppil.Patient_Payment_ID AND e.Employee_ID = ch.employee_ID AND pp.Bill_ID = '$Bill_ID' ";
    $New_Check_In_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Check_In_ID FROM `tbl_patient_payments` WHERE Bill_ID = '$Bill_ID'"))['Check_In_ID'];





                    $select_patients = "SELECT 0 as Patient_Payment_Item_List_ID, 0 as Patient_Payment_ID,Employee_Name, c.consultation_ID,ch.employee_ID, ch.cons_hist_Date,consultation_histry_ID,ch.course_of_injuries, c.Registration_ID FROM tbl_consultation c,tbl_consultation_history ch, tbl_patient_payment_item_list ppl,tbl_employee e, tbl_patient_payments pp WHERE c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and c.consultation_ID=ch.consultation_ID and e.Employee_ID=ch.employee_ID and pp.Check_In_ID = '$New_Check_In_ID' and pp.Patient_Payment_ID=ppl.Patient_Payment_ID ";


    $consultResults = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));

    if(!(mysqli_num_rows($consultResults) > 0)){
        $consultResults = mysqli_query($conn,"SELECT e.Employee_Name, ch.consultation_ID, e.employee_ID, ch.cons_hist_Date,ch.consultation_histry_ID,ch.course_of_injuries, cn.Registration_ID FROM tbl_check_in cn, tbl_check_in_details cd, tbl_consultation_history ch, tbl_employee e WHERE e.Employee_ID = ch.employee_ID AND ch.consultation_ID = cd.consultation_ID AND cn.Check_In_ID = cd.Check_In_ID AND cn.Check_In_ID = '$New_Check_In_ID'");
    }

    while ($doctorsInfo = mysqli_fetch_array($consultResults)) {
        $doctorsName = $doctorsInfo['Employee_Name'];
        $doctorsID = $doctorsInfo['employee_ID'];
        $cons_hist_Date = $doctorsInfo['cons_hist_Date'];
        $consultation_histry_ID = $doctorsInfo['consultation_histry_ID'];
        $course_of_injuries = $doctorsInfo['course_of_injuries'];
        $consultation_ID = $doctorsInfo['consultation_ID'];
//          $doctorsID=$doctorsInfo['Employee_Name'];
        // echo $course_of_injuries.' '.$doctorsName;
        $opt = '';
        if (empty($course_of_injuries)) {
            $opt = 'None';
        } else {
            $courseinjury = mysqli_query($conn,"SELECT course_injury FROM tbl_hospital_course_injuries WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "' AND hosp_course_injury_ID='$course_of_injuries' ") or die(mysqli_error($conn));

            $opt = mysqli_fetch_assoc($courseinjury)['course_injury'];
        }

        //Selecting Submitted Tests,Procedures, Drugs
        $select_payment_cache = "SELECT *,ilc.Status FROM tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_items i
				WHERE pc.consultation_id = $consultation_ID
				AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID
				AND i.Item_ID = ilc.Item_ID
                                AND ilc.Consultant_ID=$doctorsID
				";
        $cache_result = mysqli_query($conn,$select_payment_cache);
        $Radiology = '';
        $Laboratory = '';
        $Pharmacy = "";
        $Procedure = "";
        $Surgery = "";
        $i=1;
        $j=1;
        $r=1;
        $l=1;
        $p=1;
        $s=1;
        $c=1;
        if (@mysqli_num_rows($cache_result) > 0) {
            while ($cache_row = mysqli_fetch_assoc($cache_result)) {
                
                if ($cache_row['Check_In_Type'] == 'Radiology') {
                    // $Radiology .= $i.'---- ' . $cache_row['Product_Name'] . '<br/>';
                    if ($cache_row['Status'] == 'served') {
                        $Radiology .= $i.' ' . $cache_row['Product_Name'] . '=( ' . strtoupper('DONE') . ' )<br/>';
                    } else {
                        $Radiology .= $i.' ' . $cache_row['Product_Name'] . '<br/>';
                    }
                    
                }$i++;
                if ($cache_row['Check_In_Type'] == 'Laboratory') {
        $Query = "SELECT test_result_ID FROM tbl_item_list_cache
                  INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID
		  INNER JOIN tbl_tests_parameters_results AS tpr ON tpr.ref_test_result_ID=tbl_test_results.test_result_ID
		  WHERE Payment_Item_Cache_List_ID='" . $cache_row['Payment_Item_Cache_List_ID'] . "' AND tpr.Submitted='Yes' GROUP BY tpr.ref_test_result_ID";

                    //echo $Query.'<br/><br/><br/>';

                    $QueryResults = mysqli_query($conn,$Query) or die(mysqli_error($conn));

                    $rowRes = mysqli_fetch_assoc($QueryResults);

                    $RS = mysqli_query($conn,"SELECT Submitted,Validated FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $rowRes['test_result_ID'] . "'")or die(mysqli_error($conn));
                    $rowSt = mysqli_fetch_assoc($RS);

                    $totalParm = mysqli_num_rows($RS);
                    $result = "";

                    $postvQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'positive' AND ref_test_result_ID='" . $rowRes['test_result_ID'] . "'")or die(mysqli_error($conn));
                    $positive = mysqli_num_rows($postvQry);

                    $negveQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'negative' AND ref_test_result_ID='" . $rowRes['test_result_ID'] . "'")or die(mysqli_error($conn));
                    $negative = mysqli_num_rows($negveQry);

                    $abnormalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'abnormal' AND ref_test_result_ID='" . $rowRes['test_result_ID'] . "'")or die(mysqli_error($conn));
                    $abnormal = mysqli_num_rows($abnormalQry);

                    $normalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'normal' AND ref_test_result_ID='" . $rowRes['test_result_ID'] . "'")or die(mysqli_error($conn));
                    $normal = mysqli_num_rows($normalQry);

                    $highQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'high' AND ref_test_result_ID='" . $rowRes['test_result_ID'] . "'")or die(mysqli_error($conn));
                    $high = mysqli_num_rows($highQry);

                    $lowQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'low' AND ref_test_result_ID='" . $rowRes['test_result_ID'] . "'")or die(mysqli_error($conn));
                    $low = mysqli_num_rows($lowQry);

                    if ($positive == $totalParm && $positive > 0) {
                        $result = "positive";
                    } elseif ($negative == $totalParm && $negative > 0) {
                        $result = "Negative";
                    } elseif ($abnormal == $totalParm && $abnormal > 0) {
                        $result = "Abnormal";
                    } elseif ($normal == $totalParm && $normal > 0) {
                        $result = "Normal";
                    } elseif ($high == $totalParm && $high > 0) {
                        $result = "High";
                    } elseif ($low == $totalParm && $low > 0) {
                        $result = "Low";
                    }
                    
                    if (!empty($result)) {
                        
                        $Laboratory .= $l.'. ' . $cache_row['Product_Name'] . '=( ' . strtoupper($result) . ' )<br/>';
                        
                    } else {
                        $Laboratory .=$l. '. ' . $cache_row['Product_Name'] . '<br/>';
                        
                    }
                    $l++;
                }
                
                if ($cache_row['Check_In_Type'] == 'Pharmacy') {
                    $Pharmacy .= $c.' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . '<br/>   ';
                   
                } $c++;

                if ($cache_row['Check_In_Type'] == 'Procedure') {
                    
                    if ($cache_row['Status'] == 'served') {
                        
                        $Procedure .= $p.' ' . $cache_row['Product_Name'] . '=( ' . strtoupper('DONE') . ' )<br/>';
                        
                    } else {
                        $Procedure .= $p.' ' . $cache_row['Product_Name'] . '<br/>';
                        
                    }
                }
                $p++;
                if ($cache_row['Check_In_Type'] == 'Surgery') {
                    if ($cache_row['Status'] == 'served') {
                        $Surgery .= $s.' ' . $cache_row['Product_Name'] . '=( ' . strtoupper('DONE') . ' )<br/>';
                    } else {
                        $Surgery .= $s.' ' . $cache_row['Product_Name'] . '<br/>';
                    }
                    
                }$s++;
            }
        }

        //End
        //Selesting Previously written consultation note for this consultation
        $select_consultation = "SELECT * FROM tbl_consultation_history  c WHERE c.consultation_ID=$consultation_ID AND c.employee_ID='$doctorsID'";
        $consultation_result = mysqli_query($conn,$select_consultation);

        if (@mysqli_num_rows($consultation_result) > 0) {
            $consultation_row = @mysqli_fetch_assoc($consultation_result);
            $maincomplain = $consultation_row['maincomplain'];
            $firstsymptom_date = $consultation_row['firstsymptom_date'];
            $history_present_illness = $consultation_row['history_present_illness'];
            $review_of_other_systems = $consultation_row['review_of_other_systems'];
            $general_observation = $consultation_row['general_observation'];
            $systemic_observation = trim($consultation_row['systemic_observation']);
            $Comment_For_Laboratory = $consultation_row['Comment_For_Laboratory'];
            $Comment_For_Radiology = $consultation_row['Comment_For_Radiology'];
            $investigation_comments = $consultation_row['investigation_comments'];
            $Comments_For_Procedure = $consultation_row['Comments_For_Procedure'];
            $Comments_For_Surgery = $consultation_row['Comments_For_Surgery'];
            $remarks = $consultation_row['remarks'];
            //$Patient_Type = $consultation_row['Patient_Type'];
        } else {
            $maincomplain = '';
            $firstsymptom_date = '';
            $history_present_illness = '';
            $review_of_other_systems = '';
            $general_observation = '';
            $systemic_observation = '';
            $Comment_For_Laboratory = '';
            $Comment_For_Radiology = '';
            $Comments_For_Procedure = '';
            $Comments_For_Surgery = '';

            $investigation_comments = '';
            $remarks = '';
            $Patient_Type = '';
        }

        //End
        //selecting diagnosois
        $diagnosis_qr = "SELECT * FROM tbl_disease_consultation dc,tbl_disease d
		    WHERE dc.consultation_ID =$consultation_ID AND dc.employee_ID='$doctorsID' AND
		    dc.disease_ID = d.disease_ID";
        $result = mysqli_query($conn,$diagnosis_qr) or die(mysqli_error($conn));
        $provisional_diagnosis = '';
        $diferential_diagnosis = '';
        $diagnosis = '';
        if (@mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                    $provisional_diagnosis .= ' ' . $row['disease_name'] . ';';
                }
                if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                    $diferential_diagnosis .= ' ' . $row['disease_name'] . ';';
                }
                if ($row['diagnosis_type'] == 'diagnosis') {
                    $diagnosis .= ' ' . $row['disease_name'] . ';';
                }
            }
			$diagnosis_qr2 = "SELECT * FROM tbl_edited_folio_diseases efd, tbl_disease d WHERE efd.Disease_ID=d.disease_ID AND efd.Bill_ID ='$Bill_ID' AND efd.Edit_Status='Yes'";
				$result2 = mysqli_query($conn,$diagnosis_qr2) or die(mysqli_error($conn));
				if (@mysqli_num_rows($result2) > 0) {
					while ($row = mysqli_fetch_assoc($result2)) {
						if ($row['diagnosis_type'] == 'provisional_diagnosis') {
							$provisional_diagnosis .= ' ' . $row['disease_name'] . ';';
						}
						if ($row['diagnosis_type'] == 'diferential_diagnosis') {
							$diferential_diagnosis .= ' ' . $row['disease_name'] . ';';
						}
						if ($row['diagnosis_type'] == 'diagnosis') {
							$diagnosis .= ' ' . $row['disease_name'] . ';';
						}
					}
				}
        }
        //End

        $data .= '<div id="tabsInfo">
                        <table width="100%" style="background: #006400 !important;color: white;">
                        <tr>
                            <td style="text-align:right">
                             <b>Doctor</b>
                            </td>
                            <td>
                            ' . $doctorsName . '
                            </td>
                            <td style="text-align:right">
                              <b>Date:</b>
                            </td>
                            <td>
                            ' . $cons_hist_Date . '
                            </td>
                        </tr>
                    </table>
                  <div id="complain">
            <table width=100% style="border: 0px;">';
        if (!empty($maincomplain)) {
            $data .= '<tr>
                            <td width="25%" style="text-align:right;">
                                <!--<div style="margin:10px auto auto">-->
                                 Main Complain
                              </div>
                            </td>
                            <td>
                            ' . $maincomplain . '</td>
	              </tr>';
        } if (!empty($firstsymptom_date)) {
            $data .= ' <tr><td  width="25%"  style="text-align:right;">First Date Of Symptoms</td><td>' . $firstsymptom_date . '</td></tr>';
        }if (!empty($opt)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">Course Injuries</td>
                    <td>
                        ' . $opt . '
                     </td>
                </tr>';
        }if (!empty($history_present_illness)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">History Of Present Illness</td><td>' . $history_present_illness . '</td></tr>';
        }if (!empty($review_of_other_systems)) {
            $data .= '<tr><td style="text-align:right;">Review Of Other Systems</td><td>' . $review_of_other_systems . '</td></tr>';
        }
        $data .= '  </table>
        </div>
        <div id="observation">
            <table width=100% style="border: 0px;">';
        if (!empty($general_observation)) {
            $data .= '<tr>
                    <td  width="25%"  style="text-align:right;">General Examination Observation</td>
                    <td>
                       ' . $general_observation . '
                    </td>
                </tr>';
        }if (!empty($systemic_observation)) {
            $data .= ' <tr>
                            <td  width="25%"  style="text-align:right;">Systemic Examination Observation</td>
                            </td>
                            <td>
                                ' . ($systemic_observation) . '
                            </td>
                </tr>';
        }if (!empty($provisional_diagnosis)) {
            $data .= '<tr>
                            <td  width="25%"  style="text-align:right;">Provisional Diagnosis</td>
                            <td>' . $provisional_diagnosis . '
                          </tr>';
        }if (!empty($diferential_diagnosis)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">Differential Diagnosis</td><td>' . $diferential_diagnosis . '';
        }
        $data .= '</table>
        </div>
         <div id="investigation">
            <table width=100% style="border: 0px;">';
        if (!empty($Laboratory)) {
            $data .= ' <tr><td  width="25%"  style="text-align:right;">Laboratory</td><td>' . $Laboratory . '</tr>';
        }if (!empty($Comment_For_Laboratory)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">Comments For Laboratory</td><td>' . $Comment_For_Laboratory . '</tr>';
        }if (!empty($Radiology)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">Radiology</td><td>' . $Radiology . '</tr>';
        }if (!empty($Comment_For_Radiology)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">Comments For Radiology</td><td>' . $Comment_For_Radiology . '</td></tr>';
        }if (!empty($investigation_comments)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">Doctor\'s Investigation Comments</td><td>' . $investigation_comments . '</td></tr>';
        }
        $data .= ' </table>
        </div>
        <div id="diagnosis_treatment">
            <table width=100% style="border: 0px;">';
        if (!empty($diagnosis)) {
            $data .= ' <tr><td  width="25%"  style="text-align:right;"><b>Final Diagnosis </b></td><td>' . $diagnosis . '';
        }if (!empty($Procedure)) {
            $data .= ' <tr><td  width="25%"  style="text-align:right;">Procedure</td><td>' . $Procedure . '</tr>';
        }if (!empty($Comments_For_Procedure)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">Procedure Comments</td><td>' . $Comments_For_Procedure . '</td></tr>';
        }
        if (!empty($Surgery)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">Surgery</td><td>' . $Surgery . '</tr>';
        }if (!empty($Comments_For_Surgery)) {
            $data .= '<tr>
                                <td  width="25%"  style="text-align:right;">Sugery Comments</td>
                                <td>' . $Comments_For_Surgery . '
                                </td>
                          </tr>';
        }

        if (!empty($Pharmacy)) {
            $data .= '<tr><td  width="25%"  style="text-align:right;">Pharmacy</td><td>' . $Pharmacy . '';
        }
        $data .= ' </table>
        </div>
        <div id="remarks">
            <table width=100% style="border: 0px;">';
        if (!empty($remarks)) {
            $data .= ' <tr>
                <td  width="25%"  style="text-align:right;">Remarks</td>
                <td>
                ' . $remarks . '
                </td>
	    </tr>';
        }
        $data .= '</table>
        </div><br/><br/>
                 ';
    }

//INPATIENT DETAILS
    $result = mysqli_query($conn,"SELECT Admission_Date_Time,Admission_Employee_ID,Employee_Name,Admission_Status,Hospital_Ward_Name,ad.Bed_Name,Kin_Name,ToBe_Admitted_Reason FROM tbl_admission ad
                    INNER JOIN tbl_check_in_details cd ON cd.Admission_ID = ad.Admision_ID
                    JOIN tbl_hospital_ward hw ON hw.Hospital_Ward_ID=ad.Hospital_Ward_ID
                    JOIN tbl_beds bd ON bd.Bed_ID = ad.Bed_ID
                    JOIN tbl_employee e ON e.Employee_ID=ad.Admission_Employee_ID
                    WHERE consultation_ID = '" . $consultation_ID . "' AND ad.Registration_ID='" . $Registration_ID . "' AND cd.Admit_Status='admitted'
                    ") or die(mysqli_error($conn));

    $info = mysqli_fetch_array($result);
    $Admission_Date_Time = $info['Admission_Date_Time'];
    $Admission_Employee_ID = $info['Admission_Employee_ID'];
    $Admission_Status = $info['Admission_Status'];
    $Hospital_Ward_Name = $info['Hospital_Ward_Name'];
    $Bed_Name = $info['Bed_Name'];
    $Kin_Name = $info['Kin_Name'];
    $Admit_Employee_Name = $info['Employee_Name'];
    $continuation_sheet = $info['ToBe_Admitted_Reason'];

    if ($Admission_Status == 'pending') {
        $Admission_Status = 'Discharge State';
    }

    $discharge_details = '';
    //die( $Admission_Status );
    if ($Admission_Status == 'Dicharged') {
        // die( 'basdfasdf' );
        $result_disc = mysqli_query($conn,"SELECT Discharge_Date_Time,Employee_Name,Discharge_Reason,cd.Admit_Status FROM tbl_admission ad
                    INNER JOIN tbl_discharge_reason dr ON dr.Discharge_Reason_ID = ad.Discharge_Reason_ID
                    JOIN tbl_employee e ON e.Employee_ID=ad.Discharge_Employee_ID
                    JOIN tbl_check_in_details cd ON cd.Admission_ID = ad.Admision_ID
                    WHERE consultation_ID = '" . $consultation_ID . "' AND ad.Registration_ID='" . $Registration_ID . "' AND cd.Admit_Status='admitted'
                    ") or die(mysqli_error($conn));

        $info_disc = mysqli_fetch_array($result_disc);
        $Discharge_Date_Time = $info_disc['Discharge_Date_Time'];
        $Discharge_Employee_Name = $info_disc['Employee_Name'];
        $Discharge_Reason = $info_disc['Discharge_Reason'];

        $discharge_details = '<tr>
                <td style="width:10%;text-align:right "><b>Discharged By</b></td>
                <td>' . $Discharge_Employee_Name . '</td>
                <td style="width:10%;text-align:right "><b>Discharge Reason</b></td>
                <td>' . $Discharge_Reason . '</td>
                <td style="width:10%;text-align:right "><b>Discharge Date</b></td>
                <td>' . $Discharge_Date_Time . '</td>
            </tr>';
    }
   if($Admission_Status=="Discharged"){
        $data .= '
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center " id="inpatient">
        <b align="center">INPATIENT DETAILS</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" width="100%" border="0" style="border:none;font-size:12px; !important">
            <tr>
                <td style="width:15%;text-align:right "><b>Admission Date</b></td>
                <td>' . $Admission_Date_Time . '</td>
                <td style="width:10%;text-align:right "><b>Admitted By</b></td>
                <td>' . $Admit_Employee_Name . '</td>
                <td style="width:10%;text-align:right "><b>Patient Status</b></td>
                <td>' . $Admission_Status . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right "><b>Ward</b></td>
                <td>' . $Hospital_Ward_Name . '</td>
                <td style="width:10%;text-align:right "><b>Bed #</b></td>
                <td>' . $Bed_Name . '</td>
                <td style="width:10%;text-align:right "><b>Kin Name</b></td>
                <td>' . $Kin_Name . '</td>
            </tr>
            ' . $discharge_details . '
            <tr>
                <td style="width:10%;text-align:right "><b>Continuation Sheet:</b></td>
                <td colspan="5" >' . $continuation_sheet . '</td>
           </tr>
        </table>
    </div>
    <br/><br/>
    ';
    }
    $rsDoc = mysqli_query($conn,"SELECT *, TIMESTAMPDIFF(DAY,Ward_Round_Date_And_Time,NOW()) AS days_past  FROM tbl_ward_round wr JOIN tbl_employee e ON wr.employee_ID=e.employee_ID  WHERE wr.consultation_ID='$consultation_ID' AND wr.Registration_ID='$Registration_ID' AND Process_Status ='served' ORDER BY wr.Round_ID DESC LIMIT 50") or die(mysqli_error($conn));

    while ($doctorsInfo = mysqli_fetch_array($rsDoc)) {
        $doctorsName = $doctorsInfo['Employee_Name'];
        $doctorsID = $doctorsInfo['Employee_ID'];
        $Round_ID = $doctorsInfo['Round_ID'];
        $Days_Past = $doctorsInfo['days_past'];
        $Ward_Round_Date_And_Time = $doctorsInfo['Ward_Round_Date_And_Time'];

//          $doctorsID=$doctorsInfo['Employee_Name'];
        //Selecting Submitted Tests,Procedures, Drugs
        $select_payment_cache = "SELECT  Check_In_Type,Product_Name ,Doctor_Comment FROM tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_items i
				WHERE pc.consultation_id = $consultation_ID
                                AND pc.Round_ID='$Round_ID'
				AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID
				AND i.Item_ID = ilc.Item_ID
				AND ilc.Status !='notsaved'
                AND ilc.Consultant_ID=$doctorsID
				";
        $cache_result = mysqli_query($conn,$select_payment_cache);
        $Radiology = '';
        $Laboratory = '';
        $Pharmacy = "";
        $Procedure = "";
        $Surgery = "";
        $i=1;
        $j=1;
        $r=1;
        $l=1;
        $p=1;
        $s=1;
        $c=1;
        if (mysqli_num_rows($cache_result) > 0) {
            while ($cache_row = mysqli_fetch_assoc($cache_result)) {
                
                if ($cache_row['Check_In_Type'] == 'Radiology') {
                    $Radiology .= $i.'= ' . $cache_row['Product_Name'] . '<br/>';
                    
                }
                $i++;
                if ($cache_row['Check_In_Type'] == 'Laboratory') {
                    $Laboratory .= $l.'= ' . $cache_row['Product_Name'] . '<br/>';
                    
                }
                $l++;
                if ($cache_row['Check_In_Type'] == 'Pharmacy') {
                    $Pharmacy .= $c.'= ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . '<br/>   ';
                    
                }
                $c++;
                if ($cache_row['Check_In_Type'] == 'Procedure') {
                    $Procedure .= $p.'= ' . $cache_row['Product_Name'] . '<br/>';
                   
                }
                 $p++;
                if ($cache_row['Check_In_Type'] == 'Surgery') {
                    $Surgery .= $s.' ' . $cache_row['Product_Name'] . '<br/>';
                   
                } $s++;
            }
        }

        //End
        //Selesting Previously written consultation note for this consultation
        $select_consultation = "SELECT * FROM  tbl_ward_round wr WHERE wr.consultation_ID=$consultation_ID AND wr.employee_ID='$doctorsID' AND wr.Round_ID='$Round_ID'";
        $consultation_result = mysqli_query($conn,$select_consultation);

        if (@mysqli_num_rows($consultation_result) > 0) {
            $round_row = @mysqli_fetch_assoc($consultation_result);

            $Findings = $round_row['Findings'];
            $Comment_For_Laboratory = $round_row['Comment_For_Laboratory'];
            $Comment_For_Radiology = $round_row['Comment_For_Radiology'];
            $Comment_For_Procedure = $round_row['Comment_For_Procedure'];
            $Comments_For_Surgery = $round_row['Comment_For_Surgery'];
            $investigation_comments = $round_row['investigation_comments'];
            $remarks = $round_row['remarks'];
            //$Patient_Type = $consultation_row['Patient_Type'];
        } else {
            $Findings = '';
            $Comment_For_Laboratory = '';
            $Comment_For_Radiology = '';
            $Comment_For_Procedure = '';
            $Comment_For_Surgery = '';
            $investigation_comments = '';
            $remarks = '';
        }

        //End
        //selecting diagnosois
        $diagnosis_qr = "SELECT diagnosis_type,disease_name FROM tbl_ward_round_disease dc,tbl_disease d
		    WHERE Round_ID='$Round_ID' AND
		    dc.disease_ID = d.disease_ID";
        $result = mysqli_query($conn,$diagnosis_qr) or die(mysqli_error($conn));
        $provisional_diagnosis = '';
        $diferential_diagnosis = '';
        $diagnosis = '';
        if (@mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                    $provisional_diagnosis .= ' ' . $row['disease_name'] . ';';
                }
                if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                    $diferential_diagnosis .= ' ' . $row['disease_name'] . ';';
                }
                if ($row['diagnosis_type'] == 'diagnosis') {
                    $diagnosis .= ' ' . $row['disease_name'] . ';';
                }
            }
        }
        //End
        $editRound = '';
        if ($employee_ID == $doctorsID) {

            if ($Days_Past <= 2) {

                $editRound = ' <a style="width:60%" href="inpatientclinicalnotes.php?src=edit&Registration_ID=' . $Registration_ID . '&consultation_ID=' . $consultation_ID . '&Round_ID=' . $Round_ID . '" class="art-button-green">Edit</a>';
                $Round_ID = $doctorsInfo['Round_ID'];
            }
        }

        $data .= '<div id="tabsInfo">
                        <table width="100%" style="background: #006400 !important;color: white;">
                        <tr>
                            <td style="text-align:right">
                             <b>Doctor</b>
                            </td>
                            <td>
                            ' . $doctorsName . '
                            </td>
                            <td style="text-align:right">
                              <b>Date:</b>
                            </td>
                            <td>
                            ' . $Ward_Round_Date_And_Time . '
                            </td>
                            <td width="10%" style="text-align:center">
                               ' . $editRound . '
                            </td>
                        </tr>
                    </table>
                  <div id="complain">
            <table width=100% style="border: 0px;">';
        if (!empty($Findings)) {
            $data .= '<tr>
                            <td width="15%" style="text-align:right;">
                                <!--<div style="margin:10px auto auto">-->
                                 Findings
                              </div>
                            </td>
                            <td>' . $Findings . '</td>
	              </tr>';
        }
        $data .= '  </table>
        </div>
        <div id="observation">
            <table width=100% style="border: 0px;">';
        if (!empty($provisional_diagnosis)) {
            $data .= '<tr>
                            <td width="15%" style="text-align:right;">Provisional Diagnosis</td>
                            <td>' . $provisional_diagnosis . '</td>
                          </tr>';
        }if (!empty($diferential_diagnosis)) {
            $data .= '<tr><td width="15%" style="text-align:right;">Differential Diagnosis</td><td>' . $diferential_diagnosis . '</td></tr>';
        }
        $data .= '</table>
        </div>
         <div id="investigation">
            <table width=100% style="border: 0px;">';
        if (!empty($Laboratory)) {
            $data .= ' <tr><td width="15%" style="text-align:right;">Laboratory</td><td>' . $Laboratory . '</td></tr>';
        }if (!empty($Comment_For_Laboratory)) {
            $data .= '<tr><td width="15%" style="text-align:right;">Comments For Laboratory</td><td>' . $Comment_For_Laboratory . '</td></tr>';
        }if (!empty($Radiology)) {
            $data .= '<tr><td width="15%" style="text-align:right;">Radiology</td><td>' . $Radiology . '</td></tr>';
        }if (!empty($Comment_For_Radiology)) {
            $data .= '<tr><td width="15%" style="text-align:right;">Comments For Radiology</td><td>' . $Comment_For_Radiology . '</td></tr>';
        }if (!empty($investigation_comments)) {
            $data .= '<tr><td width="15%" style="text-align:right;">Doctor\'s Investigation Comments</td><td>' . $investigation_comments . '</td></tr>';
        }
        $data .= ' </table>
        </div>
        <div id="diagnosis_treatment">
            <table width=100% style="border: 0px;">';
        if (!empty($diagnosis)) {
            $data .= ' <tr><td width="15%" style="text-align:right;"><b>Final Diagnosis </b></td><td>' . $diagnosis . '</td></tr>';
        }if (!empty($Procedure)) {
            $data .= ' <tr><td width="15%" style="text-align:right;">Procedure</td><td>' . $Procedure . '</td></tr>';
        }if (!empty($Comments_For_Procedure)) {
            $data .= '<tr><td width="15%" style="text-align:right;">Procedure Comments</td><td>' . $Comments_For_Procedure . '</td></tr>';
        }
        if (!empty($Surgery)) {
            $data .= '<tr><td width="15%" style="text-align:right;">Surgery</td><td>' . $Surgery . '</td></tr>';
        }if (!empty($Comments_For_Surgery)) {
            $data .= '<tr>
                                <td width="15%" style="text-align:right;">Sugery Comments</td>
                                <td>' . $Comments_For_Surgery . '
                                </td>
                          </tr>';
        }

        if (!empty($Pharmacy)) {
            $data .= '<tr><td width="15%" style="text-align:right;">Pharmacy</td><td>' . $Pharmacy . '</td></tr>';
        }
        $data .= ' </table>
        </div>
        <div id="remarks">
            <table width=100% style="border: 0px;">';
        if (!empty($remarks)) {
            $data .= ' <tr>
                <td width="15%" style="text-align:right;">Remarks</td>
                <td>
                ' . $remarks . '
                </td>
	    </tr>';
        }
        $data .= '</table>
        </div><br/><br/>
                 ';
    }
    if (!empty($Findings)) {
        $data .= '
        <div style="margin: 10px;text-align: center;background: #ccc;padding:2px ;">End Inpatient Details</div>
                 <br/><br/>';
    }
    $data .= "Patient Signature: " . $p_signature;
    //echo $data;

include("./MPDF/mpdf.php");

$mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 1, 12.7, 8, 8);
//$mpdf->SetFooter('Printed By ' . strtoupper($Employee_Name) . '|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($data);
$mpdf->Output();
exit;
?>
