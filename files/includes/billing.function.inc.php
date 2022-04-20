<?php

function getPatientFileSummery($consultation_ID, $Registration_ID, $Bill_ID, $typecode) {
    //get the current date
    global $conn;
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
	<div style='text-align:right;margin-top:-10px;'>Folio No: ".$Folio_No."  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Bill No: ".$Bill_ID."</div>
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


    $New_Check_In_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Check_In_ID FROM `tbl_patient_payments` WHERE Bill_ID = '$Bill_ID'"))['Check_In_ID'];





	$select_patients = "SELECT 0 as Patient_Payment_Item_List_ID, 0 as Patient_Payment_ID,Employee_Name, c.consultation_ID,ch.employee_ID, ch.cons_hist_Date,consultation_histry_ID,ch.course_of_injuries, c.Registration_ID FROM tbl_consultation c,tbl_consultation_history ch, tbl_patient_payment_item_list ppl,tbl_employee e, tbl_patient_payments pp WHERE c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID and c.consultation_ID=ch.consultation_ID and e.Employee_ID=ch.employee_ID and pp.Check_In_ID = '$New_Check_In_ID' and pp.Patient_Payment_ID=ppl.Patient_Payment_ID ";

	/*$select_patients = "SELECT ch.consultation_ID, ch.maincomplain, ch.course_of_injuries, c.Registration_ID, ch.consultation_histry_ID, ch.cons_hist_Date, ch.employee_ID, e.Employee_Name FROM tbl_consultation_history ch, tbl_patient_payments pp, tbl_patient_payment_item_list ppil, tbl_consultation c, tbl_employee e WHERE ch.consultation_id = c.consultation_ID AND ppil.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID AND pp.Patient_Payment_ID = ppil.Patient_Payment_ID AND e.Employee_ID = ch.employee_ID AND pp.Bill_ID = '$Bill_ID' ";*/


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
        if (@mysqli_num_rows($cache_result) > 0) {
            while ($cache_row = mysqli_fetch_assoc($cache_result)) {
                if ($cache_row['Check_In_Type'] == 'Radiology') {
                    $Radiology .= ' ' . $cache_row['Product_Name'] . ';';
                    if ($cache_row['Status'] == 'served') {
                        $Radiology .= ' ' . $cache_row['Product_Name'] . '=( ' . strtoupper('DONE') . ' );';
                    } else {
                        $Radiology .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                }
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
                        $Laboratory .= ' ' . $cache_row['Product_Name'] . '=( ' . strtoupper($result) . ' );';
                    } else {
                        $Laboratory .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                }
                if ($cache_row['Check_In_Type'] == 'Pharmacy') {
                    $Pharmacy .= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                }
                if ($cache_row['Check_In_Type'] == 'Procedure') {
                    if ($cache_row['Status'] == 'served') {
                        $Procedure .= ' ' . $cache_row['Product_Name'] . '=( ' . strtoupper('DONE') . ' );';
                    } else {
                        $Procedure .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                }
                if ($cache_row['Check_In_Type'] == 'Surgery') {
                    if ($cache_row['Status'] == 'served') {
                        $Surgery .= ' ' . $cache_row['Product_Name'] . '=( ' . strtoupper('DONE') . ' );';
                    } else {
                        $Surgery .= ' ' . $cache_row['Product_Name'] . ';';
                    }
                }
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
                                ' . strlen($systemic_observation) . '
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
        if (mysqli_num_rows($cache_result) > 0) {
            while ($cache_row = mysqli_fetch_assoc($cache_result)) {
                if ($cache_row['Check_In_Type'] == 'Radiology') {
                    $Radiology .= ' ' . $cache_row['Product_Name'] . ';';
                }
                if ($cache_row['Check_In_Type'] == 'Laboratory') {
                    $Laboratory .= ' ' . $cache_row['Product_Name'] . ';';
                }
                if ($cache_row['Check_In_Type'] == 'Pharmacy') {
                    $Pharmacy .= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
                }
                if ($cache_row['Check_In_Type'] == 'Procedure') {
                    $Procedure .= ' ' . $cache_row['Product_Name'] . ';';
                }
                if ($cache_row['Check_In_Type'] == 'Surgery') {
                    $Surgery .= ' ' . $cache_row['Product_Name'] . ';';
                }
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
            $employee_ID = $round_row['employee_ID'];
            $clinical_history = $round_row['clinical_history'];
        } else {
            $Findings = '';
            $Comment_For_Laboratory = '';
            $Comment_For_Radiology = '';
            $Comment_For_Procedure = '';
            $Comment_For_Surgery = '';
            $investigation_comments = '';
            $clinical_history='';
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
            if (!empty($clinical_history)) {
                $data .= '<tr>
                                <td width="15%" style="text-align:right;">
                                    <!--<div style="margin:10px auto auto">-->
                                     Clinical History
                                  </div>
                                </td>
                                <td>' . $clinical_history . '</td>
                      </tr>';
            }
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
    require_once "./MPDF/mpdf.php";
    $mpdf = new mPDF('c', 'A4');
//$mpdf->SetDisplayMode('fullpage');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
//    $stylesheet = file_get_contents('patient_file.css');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($data, 2);
    return $mpdf->Output('', 'S');
}


function getPatientClaimFile($Bill_ID) {
    global $conn;
    $temp = 1;
    $GrandTotal = 0;
    $Sub_Total = 0;
    $claim_details_array = array();
    
    $select_credentials  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT pp.Registration_ID, pp.Folio_Number, pp.Check_In_ID, pp.Patient_Bill_ID, pp.Sponsor_ID FROM tbl_patient_payments pp, tbl_patient_payment_item_list ppl WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.Bill_ID = '$Bill_ID' LIMIT 1 "));
    
    $Folio_Number = $select_credentials['Folio_Number'];
    $Patient_Bill_ID = $select_credentials['Patient_Bill_ID'];
    $Sponsor_ID = $select_credentials['Sponsor_ID'];
    $Registration_ID = $select_credentials['Registration_ID'];
    $Check_In_ID = $select_credentials['Check_In_ID'];

//get insurance name
$select = mysqli_query($conn,"select Guarantor_Name from tbl_patient_registration pr, tbl_sponsor sp where
    						sp.Sponsor_ID = pr.Sponsor_ID and pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Guarantor_Name = $data['Guarantor_Name'];
    }
} else {
    $Guarantor_Name = '';
}

//select printing date and time
$select_Time_and_date = mysqli_query($conn,"select now() as datetime");
while ($row = mysqli_fetch_array($select_Time_and_date)) {
    $Date_Time = $row['datetime'];
}
//select folio date to be used in creating a serial no
$foliodate = mysqli_fetch_assoc(mysqli_query($conn,"SELECT DATE(Payment_Date_And_Time) AS foliodate FROM tbl_patient_payments WHERE Folio_Number = '$Folio_Number' AND Patient_Bill_ID='$Patient_Bill_ID' ORDER BY Payment_Date_And_Time ASC LIMIT 1"))['foliodate'];
$timestamp = strtotime($foliodate);
$month = date("m", $timestamp);
$year = date("Y", $timestamp);

    $facility_code = mysqli_fetch_array(mysqli_query($conn,"SELECT facility_code FROM tbl_system_configuration"))['facility_code'];
//nhif folio
	$display_folio = '';

$check_folio = mysqli_query($conn,"SELECT Folio_No , claim_month, claim_year, sent_date FROM tbl_claim_folio WHERE  Bill_ID = '$Bill_ID'");
$display_folio = '';
$claim_month = '';
$claim_year = '';
if(mysqli_num_rows($check_folio) > 0){
  $claim_info = mysqli_fetch_assoc($check_folio);
  $last_folio = $claim_info['Folio_No'];
  $claim_month = $claim_info['claim_month'];
  $claim_year = $claim_info['claim_year'];}else{
    die("no folio no");
}
	$display_folio = $last_folio;
    $last_folio = sprintf("%'.07d\n",$last_folio);

    $serial_data = $facility_code.'/'.$claim_month.'/'.$claim_year.'/'.$display_folio;
//die("ona  ".mysqli_num_rows($check_folio)." and ".$Bill_ID." ands ".$Patient_Bill_ID);

// end of nhif folio



$htm = "<table width ='100%'>
            <tr>
            <td width='20%'><img src='images/stamp.jpeg' width='120' height='120' style='float:left;'></td>
            <td>

            <center>

            <span style='font-size: small;'>
        <b>" . strtoupper($Guarantor_Name) . " CONFIDENTIAL<br>
            HEALTH PROVIDER IN / OUT PATIENT CLAIM FORM</b></span>
            </center>
            </td>
            <td width='20%' style='text-align: right'>
            <span style='font-size: x-small;'>Form 2A&B<br>
            Regulation 18(1)</span>
            </td></tr>
 <tr><td style='text-align: right;padding-top:-40px;' colspan='3'><span style='font-size: small;'>Folio No: ".$display_folio."&emsp;&emsp;&emsp;&emsp; Bill No: ".$Bill_ID."&emsp;&emsp;&emsp;&emsp; Serial no:" . $serial_data. "</span></td></tr>e
</table>";
$select_Transaction_Items = mysqli_query($conn,"
            select * from
                tbl_patient_registration pr,tbl_check_in ch, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
                tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				ch.registration_id = pr.registration_id and
				(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
				pr.registration_id = pp.registration_id and
				pp.Patient_Bill_ID = '$Patient_Bill_ID' and
				e.employee_id = pp.employee_id and
				ic.item_category_id = ts.item_category_id and ppl.Status<>'removed' and
				ts.item_subcategory_id = t.item_subcategory_id and
				t.item_id = ppl.item_id and
				pp.folio_number = '$Folio_Number' and
				pr.registration_id = '$Registration_ID' and Billing_Process_Employee_ID is not null group by pp.Patient_Payment_ID, pp.Patient_Bill_ID order by pp.Patient_Payment_ID limit 1") or die(mysqli_error($conn));
$htm .= "<table width=100% style='padding-top:-25px;'>";
$htm .= "<tr><td colspan=12><hr style='color: #000000'></td></tr>";
while ($row = mysqli_fetch_array($select_Transaction_Items)) {
    $Patient_Name = $row['Patient_Name'];
    $Folio_Number = $row['Folio_Number'];
    $Sponsor_Name = $row['Sponsor_Name'];
    if (isset($row['patient_signature'])) {
        $p_signature = "<img width='120' height='60' style='padding: 0;' src='../esign/patients_signature/" . $row['patient_signature'] . "' >";
    } else {
        $p_signature = '________________';
    }

    $Gender = $row['Gender'];
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $Member_Number = $row['Member_Number'];
    $Billing_Type = $row['Billing_Type'];
    $Occupation = $row['Occupation'];
    $Visit_Date = $row['Visit_Date'];
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Employee_Vote_Number = $row['Employee_Vote_Number'];
    $Phone_Number = $row['Phone_Number'];
    $Billing_Process_Employee_ID = $row['Billing_Process_Employee_ID'];
}

$date1 = new DateTime(Date("Y-m-d"));
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days";

//get visit date
$select_visit = mysqli_query($conn,"select Visit_Date from tbl_check_in where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select_visit);
if ($num > 0) {
    while ($row = mysqli_fetch_array($select_visit)) {
        $Visit_Date = $row['Visit_Date'];
    }
} else {
    $Visit_Date = '';
}


//get authorisation number
$AuthorizationNo = '';
	/*
$select_visit = mysqli_query($conn,"select c.AuthorizationNo from tbl_patient_payments pp, tbl_check_in c where
									c.Check_In_ID = pp.Check_In_ID and
									pp.Folio_Number = '$Folio_Number' and
									pp.Registration_ID = '$Registration_ID' and
					                pp.Patient_Bill_ID = '$Patient_Bill_ID' and
									pp.Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));*/
	$select_visit = mysqli_query($conn,"SELECT AuthorizationNo FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));

$num = mysqli_num_rows($select_visit);
if ($num > 0) {
    while ($row = mysqli_fetch_array($select_visit)) {
        if ($row['AuthorizationNo'] != null && $row['AuthorizationNo'] != '') {
            $AuthorizationNo = $row['AuthorizationNo'];
        }
    }
} else {
    $AuthorizationNo = '';
}
/*
if ($Billing_Type == "Outpatient Credit") {
    $patient_type = "Outpatient";
    $patient_status = "OUT";
} else {
    $patient_type = "Inpatient";
    $patient_status = "IN";
}
*/

    $select111 = mysqli_query($conn,"SELECT cd.Admission_ID , cd.consultation_ID FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
    if(mysqli_num_rows($select111) > 0){
        $patient_type ='Inpatient';
        $patient_status = "IN";
        $consultation_ID = mysqli_fetch_assoc($select111)['consultation_ID'];
    }else{
        $patient_type ='Outpatient';
        $patient_status = "OUT";
    }
    $diagnosis_query = mysqli_query($conn,"SELECT efd.Disease_ID, efd.Disease_Code AS disease_code, efd.Consultant_ID as Employee_ID, efd.Consultation_Time AS Disease_Consultation_Date_And_Time, efd.Consultant_Name AS Consultant_Name, diagnosis_type FROM tbl_edited_folio_diseases efd WHERE Bill_ID = $Bill_ID AND efd.diagnosis_type IN('diagnosis','provisional_diagnosis' GROUP BY dc.Disease_ID) ");
    if(mysqli_num_rows($diagnosis_query) > 0){
        $diagnosis_result = $diagnosis_query;
    }else{


    if($patient_type =='Inpatient'){
        $diagnosis_query = "SELECT wd.Disease_ID, wr.Employee_ID,  diagnosis_type,disease_name,Round_Disease_Date_And_Time,disease_code FROM tbl_ward_round_disease wd,tbl_ward_round wr, tbl_disease d    WHERE   wd.disease_ID = d.disease_ID AND    wr.Round_ID = wd.Round_ID AND    wr.consultation_ID ='$consultation_ID' GROUP BY wd.Disease_ID";
        
    }else{
        $diagnosis_query = "SELECT d.nhif_code as disease_code,dc.diagnosis_type,c.Employee_ID, (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = dc.Employee_ID) as Consultant_Name     FROM tbl_disease d,tbl_consultation c JOIN tbl_disease_consultation dc ON dc.Consultation_ID=c.consultation_ID WHERE d.Disease_ID = dc.Disease_ID     AND c.Registration_ID = '$Registration_ID'     AND dc.diagnosis_type IN ('provisional_diagnosis', 'diagnosis')     AND date(dc.Disease_Consultation_Date_And_Time) >= (SELECT Visit_Date FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID' GROUP BY dc.Disease_ID)";
    }    
    $diagnosis_result = mysqli_query($conn,$diagnosis_query) or die(mysqli_error($conn));
}
$diagnosis = "";
$provisional_diagnosis = "";
$Consultant_Name = "";
$consut_id = '';
	
	
$consultant_list = [];
$consultant_list_kada = [];
	
	
while ($diagnosis_row = mysqli_fetch_assoc($diagnosis_result)) {
    $diagnosis_type = $diagnosis_row['diagnosis_type'];

    //categorize diseases
    if($diagnosis_type == 'diagnosis'){
      $diagnosis .= $diagnosis_row['disease_code'] . "; ";
    }elseif ($diagnosis_type == 'provisional_diagnosis') {
      $provisional_diagnosis .= $diagnosis_row['disease_code'] . "; ";
    }
    $consut_id = $diagnosis_row['Employee_ID'];
	
	if(!in_array($consut_id, $consultant_list)){
      $kada =  mysqli_fetch_assoc(mysqli_query($conn,"SELECT kada FROM tbl_employee WHERE Employee_ID = '$consut_id'"))['kada'];
      array_push($consultant_list, $consut_id);
      array_push($consultant_list_kada, array('ConsultantID' => $consut_id,'ConsultantKada' => $kada));

    }

}
	
/* prioritize the consultants from high to bottom */

  $general_practitioner = [];
  $specialist_consultant = [];
  $super_specialist_consultant = [];

  foreach ($consultant_list_kada as $consultant_kada) {
    if($consultant_kada['ConsultantKada'] == 'super_specialist'){

      array_push($super_specialist_consultant, $consultant_kada['ConsultantID']);

    }else if($consultant_kada['ConsultantKada'] == 'specialist'){

      array_push($specialist_consultant, $consultant_kada['ConsultantID']);

    }else{

      array_push($general_practitioner, $consultant_kada['ConsultantID']);

    }
  }

  if(sizeof($super_specialist_consultant) > 0){

    $consut_id = $super_specialist_consultant[0];

  }else if(sizeof($specialist_consultant) > 0){

    $consut_id = $specialist_consultant[0];

  }else if(sizeof($general_practitioner) > 0){

    $consut_id = $general_practitioner[0];

  }else{

    die("Doctor Final Diagnosis is Missing pls check");
    
  }	

//get consaltant details
$select_consult_details = mysqli_query($conn,"SELECT emp.Employee_Name AS Consultant_Name,emp.employee_signature,emp.kada,emp.Phone_Number,emp.doctor_reg_no, doctor_license FROM tbl_employee emp WHERE emp.Employee_ID = '$consut_id'");
$consult_details = mysqli_fetch_assoc($select_consult_details);

$Consultant_Name = $consult_details['Consultant_Name'] . " ";
$qualification = $consult_details['kada'] . " ";
$doctor_reg_no = $consult_details['doctor_license'] . " ";
$Phone_Number = $consult_details['Phone_Number'] . " ";
if (isset($consult_details['employee_signature'])) {
    $Consultant_signature = "<img width='120' height='25' style='padding: 0;' src='../esign/employee_signatures/" . $consult_details['employee_signature'] . "' >";
} else {
    $Consultant_signature = '________________';
}

//mvungi anafanya marekebisho ya inpatient SO NINAIFUNGA HII PROCESS KWA MDA
//select diagnosis details inpatients
/* $select_con = mysqli_query($conn,"SELECT d.disease_code, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name
  FROM tbl_ward_round c,tbl_ward_round_disease dc, tbl_disease d
  WHERE c.Round_ID =dc.Round_ID AND d.Disease_ID = dc.Disease_ID
  AND dc.diagnosis_type = 'diagnosis'
  AND c.Patient_Payment_Item_List_ID IN (
  SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE
  ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
  pp.Folio_Number = '$Folio_Number' and
  pp.Registration_ID = '$Registration_ID' and pp.Patient_Bill_ID = '$Patient_Bill_ID')") or die(mysqli_error($conn));
  $no_of_rows = mysqli_num_rows($select_con);
  if($no_of_rows > 0){
  while($diagnosis_row = mysqli_fetch_array($select_con)){
  $diagnosis.=$diagnosis_row['disease_code']."; ";
  //$Consultant_Name = $diagnosis_row['Consultant_Name'];
  }
  } */
$$diagnosis = '';
//$company_query = "SELECT Company_Name FROM tbl_company";
$hospital_details = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Hospital_Name,Box_Address,Fax FROM tbl_system_configuration"));
//$company_result = mysqli_query($conn,$company_query);
//$company_row = mysqli_fetch_assoc($company_result);
//$company = $company_row['Company_Name'];
$htm .= "<tr><td colspan=4 width=33%><span style='font-size: small;'><b>A. PARTICULAR</b></span><br><span style='font-size: small;'>
    1. Name : ".$hospital_details['Hospital_Name']."<br>
    2. Accreditation No : ".$hospital_details['Fax']."<br>
    3. Address : ".$hospital_details['Box_Address']."<br>
    4. Patient Name : " . ucwords($Patient_Name) . "<br>
    5. Age : $age &nbsp;&nbsp;&nbsp; <br></span>
    </td>
    <td colspan=4 valign='top' width='33%'><br><span style='font-size: small;'>6 .Sex : $Gender<br>
        7. Membership No : $Member_Number<br>
        8. Occupation : $Occupation<br>
        9. Date of attendance : $Visit_Date<br>
      10.Preliminary Diagnosis(Code): $provisional_diagnosis<br></span>
    </td>
    <td colspan=4 valign='top' width='33%'><br><span style='font-size: small;'>11. Final Diagnosis(Code): $diagnosis<br>
    12. Patient Status: $patient_status<br>
    13. Patient's Vote No: $Employee_Vote_Number<br>
    14. Authorization No: $AuthorizationNo</span></td></tr>";

$htm .= "<tr><td colspan=4><span><b><span style='font-size: x-small;'>B. COST OF SERVICES :</span></b></span></td></tr></table>";

$select_category_details = mysqli_query($conn,"
                select ic.Item_Category_ID, ic.Item_Category_Name from
                tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
                tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
                pr.registration_id = pp.registration_id and
                e.employee_id = pp.employee_id and
                ic.item_category_id = ts.item_category_id and ppl.Status<>'removed' and
                ts.item_subcategory_id = t.item_subcategory_id and
                (pp. Billing_Process_Status = 'Approved' ||  Billing_Process_Status = 'billed') and
                t.item_id = ppl.item_id and 
                pp.folio_number = '$Folio_Number' and
                pp.registration_id = '$Registration_ID' and
                pp.Check_In_ID = '$Check_In_ID' and
                pp.Patient_Bill_ID = '$Patient_Bill_ID' group by ic.Item_category_ID  order by pp.Payment_Date_And_Time") or die(mysqli_error($conn));

	    if(!(mysqli_num_rows($select_category_details)>0)){
        //$category_status =  false;
    		mysqli_query($conn,"UPDATE tbl_bills SET claim_status = 1 WHERE Bill_ID = $Bill_ID");
		}

while ($cat = mysqli_fetch_array($select_category_details)) {
    $Item_Category_ID = $cat['Item_Category_ID'];
    $Item_Category_Name = $cat['Item_Category_Name'];
    $htm .= "<span style='font-size: x-small;'>" . $cat['Item_Category_Name'] . "</span><br/>";

    //display details
    $htm .= '<table width=100% border=1 style="border-collapse: collapse;">';
    $htm .= '<tr>
			    <td width=4%><span style="font-size: x-small;">SN</span></td>
			    <td width=4%><span style="font-size: x-small;">Codes</span></td>
			    <td width=52%><span style="font-size: x-small;">Item Description</span></td>
			    <td width=10% style="text-align: left;"><span style="font-size: x-small;">Receipt N<u>o</u></span></td>
			    <td width=7% style="text-align: right;"><span style="font-size: x-small;">Price</span></td>
			    <td width=7% style="text-align: center;"><span style="font-size: x-small;">Quantity</span></td>
			    <td width=7% style="text-align: right;"><span style="font-size: x-small;">Discount</span></td>
			    <td width=7% style="text-align: right;"><span style="font-size: x-small;">Amount</span></td>
			</tr>';

    $select_Transaction_Items = mysqli_query($conn,"
            select ppl.Patient_Payment_Item_List_ID,t.Item_ID, t.item_kind, t.Generic_ID, t.Product_Code, t.Consultation_Type,ic.Item_Category_Name, pp.Patient_Payment_ID,pp.Patient_Signature,t.Product_Name,ppl.Billing_approval_status, pp.Claim_Form_Number, pp.Receipt_Date, ppl.Price, ppl.Quantity, ppl.Discount from
            tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
            tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
            pr.registration_id = pp.registration_id and
            e.employee_id = pp.employee_id and
            ic.item_category_id = ts.item_category_id and ppl.Status<>'removed' and
            ts.item_subcategory_id = t.item_subcategory_id and
            t.item_id = ppl.item_id and
            pp.folio_number = '$Folio_Number' and
            pp.registration_id = '$Registration_ID' and
            pp.Patient_Bill_ID = '$Patient_Bill_ID' and
            (pp. Billing_Process_Status = 'Approved'||  Billing_Process_Status = 'billed') and
            pp.Check_In_ID = '$Check_In_ID' and
            ic.Item_category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
    $num_items = mysqli_num_rows($select_Transaction_Items);
    if ($num_items > 0) {
        while ($dat = mysqli_fetch_array($select_Transaction_Items)) {
            $Consultation_Type = $dat['Consultation_Type'];
            $Billing_approval_status = $dat['Billing_approval_status'];
            $Patient_Signature = $dat['Patient_Signature'];
            $Patient_Payment_ID = $dat['Patient_Payment_ID'];
            $Patient_Payment_Item_List_ID = $dat['Patient_Payment_Item_List_ID'];
            $Item_ID = $dat['Item_ID'];
            $total = (($dat['Price'] - $dat['Discount']) * $dat['Quantity']);

            $Product_Code = $dat['Product_Code'];
            $Product_Name = $dat['Product_Name'];
            $item_kind = $dat['item_kind'];
            $Generic_ID = $dat['Generic_ID'];
            if($item_kind == 'brand'){
              $generic_result = mysqli_query($conn,"SELECT Product_Code, Product_Name FROM tbl_items WHERE Item_ID = '$Generic_ID'");
              $generic_data = mysqli_fetch_assoc($generic_result);
              $Product_Code = $generic_data['Product_Code'];
              $Product_Name = $generic_data['Product_Name'];
              //die(json_encode(array('code' => $Product_Code)));
            }

            $dosage = '';
            $select_dosage = mysqli_query($conn,"SELECT Doctor_Comment FROM tbl_item_list_cache WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = 'Pharmacy' AND Status='dispensed'");
            if(mysqli_num_rows($select_dosage) > 0){
              $dosage = mysqli_fetch_assoc($select_dosage)['Doctor_Comment'];
              if(trim($dosage) != ''){
                $dosage = " :Dosage(".$dosage.")";
              }
            }

            array_push($claim_details_array,array('Product_Code'=>$Product_Code, 'Quantity'=>$dat['Quantity'], 'Amount'=>$total));

            $htm .= "<tr>";
            $htm .= "<td style='text-align: center;'><span style='font-size: x-small;'>" . $temp . "</span></td>";
            $htm .= "<td><span style='font-size: x-small;'>" . $Product_Code . "</span></td>";
            $htm .= "<td>";
            $initial_name="";
            if($Consultation_Type=="Surgery"){
                $initial_name="Surgery Name--";
            }
            if($Consultation_Type=="Procedure"){
               $initial_name="Procedure Name--";
            }
            if ($Billing_approval_status == 'yes') {
                $htm .= "<span style='font-size: x-small;'>$initial_name<b>" . $Product_Name. $dosage."</b></span>";
            } else {
                $htm .= "<span style='font-size: x-small;'>$initial_name<b>" . $Product_Name .$dosage. "</b></span>";
            }

            $get_details = mysqli_query($conn,"select Post_operative_ID,type_of_surgery,duration_of_surgery,Type_Of_Anesthetic
                                from tbl_post_operative_notes where
                                Payment_Item_Cache_List_ID IN (SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID') and
                                Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

            if(mysqli_num_rows($get_details)>0){
                $get_details_result_rows=mysqli_fetch_assoc($get_details);
                $type_of_surgery=$get_details_result_rows['type_of_surgery'];
                $duration_of_surgery=$get_details_result_rows['duration_of_surgery'];
                $Type_Of_Anesthetic=$get_details_result_rows['Type_Of_Anesthetic'];
                $Post_operative_ID=$get_details_result_rows['Post_operative_ID'];

                $selected_assistant_surgeons = mysqli_query($conn,"select kada,Employee_Name, pop.Employee_Type from tbl_employee emp, tbl_post_operative_participant pop where
                                            emp.Employee_ID = pop.Employee_ID and
                                            pop.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
        $nmz = mysqli_num_rows($selected_assistant_surgeons);
        $Surgeons_selected="";
        if($nmz > 0){
            while ($row = mysqli_fetch_array($selected_assistant_surgeons)) {
                $Employee_Type = $row['Employee_Type'];

                if($Employee_Type == 'Nurse'){
                    $Nurses_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }else if($Employee_Type == 'Assistant Surgeon'){
                    $Assistant_surgeons_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }else if($Employee_Type == 'Surgeon'){
                     $kada = $row['kada'];
                    $Surgeons_selected .= ucwords(strtolower($row['Employee_Name'])).' <span style="font-weight:normal">Qualification--</span>'.$kada.' ;    ';

                }else if($Employee_Type == 'Anaesthetics'){
                    $Anaesthetics_selected .= ucwords(strtolower($row['Employee_Name'])).' <span style="font-weight:normal">Qualification--</span>'.$kada.' ;    ';
                }
            }
        }
            }
            if($Consultation_Type=="Surgery"){
                $htm .= "
                        <br/><span style='font-size: x-small;'>Type Of Surgery--<b>$type_of_surgery</b></span>
                        <br/><span style='font-size: x-small;'>Duration Of Surgery--<b>$duration_of_surgery</b></span>
                        <br/><span style='font-size: x-small;'>Type of Anesthetic--<b>$Type_Of_Anesthetic</b></span>
                        <br/><br/><span style='font-size: x-small;'>Surgeon--<b>$Surgeons_selected</b></span>
                        <br/><br/><span style='font-size: x-small;'>Anaesthesiologist--<b>$Anaesthetics_selected</b></span>


                           ";
            }
            if($Consultation_Type=="Procedure"){
                $sql_select_procedure_detail_result=mysqli_query($conn,"SELECT type_of_procedure,duration_of_procedure,Type_Of_Anesthetic FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
                $proc_details_rows=mysqli_fetch_assoc($sql_select_procedure_detail_result);
                $type_of_procedure=$proc_details_rows['type_of_procedure'];
                $duration_of_procedure=$proc_details_rows['duration_of_procedure'];
                $Type_Of_Anesthetic=$proc_details_rows['Type_Of_Anesthetic'];
                $htm .= "
                        <br/><span style='font-size: x-small;'>Type Of Procedure--<b>$type_of_procedure</b></span>
                        <br/><span style='font-size: x-small;'>Duration Of Procedure--<b>$duration_of_procedure</b></span>
                        <br/><span style='font-size: x-small;'>Type of Anesthetic--<b>$Type_Of_Anesthetic</b></span>";
            }
            $htm .= "</td>";
            $htm .= "<td><span style='font-size: x-small;'>" . $dat['Patient_Payment_ID'] . "</span></td>";
            $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($dat['Price']) . "</span></td>";
            $htm .= "<td style='text-align: center;'><span style='font-size: x-small;'>" . $dat['Quantity'] . "</span></td>";
            $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($dat['Discount']) . "</span></td>";
            $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($total) . "</span></td></tr>";

            $Sub_Total += $total;
            $GrandTotal = $GrandTotal + $total;
            $temp++;
        }
    }
    $htm .= '<tr><td colspan=8 style="text-align: right;"><span style="font-size: x-small;"><b>Sub Total : ' . number_format($Sub_Total) . '</b></span></td></tr>';
    $Sub_Total = 0;
    $total = 0;
    $temp = 1;
    $htm .= '</table><br/>';
}
$htm .= "<table width=100% border=1 style='border-collapse: collapse;'>
		<tr>
		    <td colspan=7 style='text-align: right;'>
			<span style='font-size: x-small;'>
			    <b>Grand Total : " . number_format($GrandTotal) . "</b></span>
		    </td>
		</tr>
	    </table>";

//Authenticating officer name
//$query = mysqli_query($conn,"SELECT Employee_Name,employee_signature FROM tbl_employee WHERE Employee_ID=$Billing_Process_Employee_ID");

    $query = mysqli_query($conn,"SELECT e.Employee_Name, e.employee_signature FROM tbl_employee e, tbl_bills b WHERE e.Employee_ID=b.Employee_ID AND b.Bill_ID = $Bill_ID");

$result = mysqli_fetch_assoc($query);
$Approved_Employee_Name = $result['Employee_Name'];
if (isset($result['employee_signature'])) {
    $emp_signature = "<img width='120' height='60' style='padding: 0;' src='../esign/employee_signatures/" . $result['employee_signature'] . "' >";
} else {
    $emp_signature = '________________';
}

$htm .= "<table width ='100%'>
		    <tr>
		       <td><b>C. Name of attending Clinician : </b>$Consultant_Name &emsp;<b><b>Qualifications: </b> ".str_replace('_',' ',$qualification)." &emsp;<b>Reg No: </b>$doctor_reg_no &emsp;<br><br><b>Signature:</b>" . $Consultant_signature . " &emsp;<b>Mob No:</b> $Phone_Number</td>

		    </tr>
		    <tr>
			<td><br>
			<b>D: Patient Certification</b> <br/>
			I certify that I received the above named services. Name : <b>" . ucwords($Patient_Name) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature :<b>" . $p_signature . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tel $Phone_Number <br/><br/>
			<b>E: Description of Out/In-patient Management / any other additional information </b><br><br>
			----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
			<br>
			<b>F: Claimant Certification</b><br/>
			I certify that I provided the above services. Name  : <b>" . ucwords($Approved_Employee_Name) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature" . $emp_signature . "Official Stamp <br>
			NB: Fill in the Triplicate and please submit the original form on monthly basis, and the claim should be attached with Monthly Report.<br>
			Any falsified information may subject you to prosecution in accordance.<br>

            </td>
            </tr>
    </table>
        <br>
        <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u> End Of Document <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u><br>
        <!--span style='font-size:11px;'>
        Printed By  <b>". strtoupper($Employee_Name)."</b> at ".date('m/d/Y h:i:s a', time())."&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span-->
        <span style='font-size:9px;'>Powered By GPITG Ltd</span>
    ";
//echo $htm;
//include("./MPDF/mpdf.php");
    $mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
    $claim_file = $mpdf->WriteHTML($htm);
    $claim_file = base64_encode($mpdf->Output('', 's'));

    $ClaimFilePath = $Bill_ID.'_claim_file.txt';
    $file2 = fopen('/var/www/html/ehmsbmc/NHIF_FILE/'.$ClaimFilePath,'wb');
    fwrite($file2,$claim_file);
    fclose($file2);

    return $claim_details_array;
}


function getAllSignatures($consultation_ID, $Registration_ID,$Bill_ID,$typecode,$conn){
    global $conn;
        $signturearray = array();
        // die( "SELECT doctor_license, employee_signature  FROM tbl_consultation_history ch, tbl_employee e WHERE consultation_ID = '$consultation_ID'  AND e.Employee_ID=ch.employee_ID");
        $selectConsulttion = mysqli_query($conn, "SELECT doctor_license, employee_signature  FROM tbl_consultation_history ch, tbl_employee e WHERE consultation_ID = '$consultation_ID'  AND e.Employee_ID=ch.employee_ID") or die(mysqli_error($conn));
        if(mysqli_num_rows($selectConsulttion)>0){
            while($crw = mysqli_fetch_assoc($selectConsulttion)){
                $doctor_license =  $crw['doctor_license'];
                $kada = $crw['kada'];
                $employee_signature = $crw['employee_signature'];
                if (isset($employee_signature)) {
                    $Consultant_signature = "<img width='120' height='25' style='padding: 0;' src='../esign/employee_signatures/" . $employee_signature . "' >";
                    $Encodedsignature = base64_encode($Consultant_signature);
                } else {
                    $Consultant_signature = '________________';
                    $Encodedsignature= base64_encode($Consultant_signature);
                }
                $empsignatory1 =array(
                    "Signatory"=>$kada,
                    "SignatoryID"=>$doctor_license,
                    "SignatureData"=>$Encodedsignature
                );
                
            }
        }
        array_push($signturearray, $empsignatory1);
    

    return $Registration_ID;
}