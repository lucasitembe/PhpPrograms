<?php

@session_start();
include("../includes/connection.php");
include("../includes/cleaninput.php");
include("./database.php");
$employee_id = $_SESSION['userinfo']['Employee_ID'];

if (isset($_POST['referral_from'])) {
    $_POST = sanitize_input($_POST);
    $regid = $_POST['regid'];
    $referral_from = $_POST['referral_from'];
    $referral_to = $_POST['referral_to'];
    $transfer_date = $_POST['transfer_date'];
    $diagnosis = $_POST['diagnosis'];
    $temp = $_POST['temp'];
    $heatrate = $_POST['heatrate'];
    $resprate = $_POST['resprate'];
    $bloodpressure = $_POST['bloodpressure'];
    $mental_status = $_POST['mental_status'];
    $alert = $_POST['alert'];
    $patienthist = $_POST['patienthist'];
    $chrnicmed = $_POST['chrnicmed'];
    $medalergy = $_POST['medalergy'];
    $pertnetfindings = $_POST['pertnetfindings'];
    $labresult = $_POST['labresult'];
    $radresult = $_POST['radresult'];
    $treatmentrendered = $_POST['treatmentrendered'];
    $reasonfortransfer = $_POST['reasonfortransfer'];
    $doct_phone_number = $_POST['doct_phone_number'];
    $call_phone_number = $_POST['call_phone_number'];

    $admission_ID = 'NULL';
    $consID = 'NULL';
    $ppID = 'NULL';
    $pplID = 'NULL';

    if (isset($_POST['src']) && $_POST['src'] == 'inpat') { //admission_ID, consultation_ID
        $admission_ID = $_POST['admID'];
        $consID = $_POST['consID'];
    } else {
        $ppID = $_POST['ppID'];
        $pplID = $_POST['pplID'];
    }



    $insert = "INSERT INTO tbl_referral_patient 
              ( Registration_ID, Patient_Payment_Item_List_ID,admission_ID, consultation_ID, referral_from, 
               referral_to, transfer_date, diagnosis, temp, heatrate, resprate, 
               bloodpressure, mental_status, alert, patienthist, chrnicmed, medalergy, 
               pertnetfindings, labresult, radresult, treatmentrendered, reasonfortransfer, 
               doct_phone_number, call_phone_number,employee_id,trans_date) 
               VALUES ( '$regid', $pplID,$admission_ID,$consID,'$referral_from', 
               '$referral_to', '$transfer_date', '$diagnosis', '$temp', '$heatrate', '$resprate', 
               '$bloodpressure', '$mental_status', '$alert', '$patienthist', '$chrnicmed', '$medalergy', 
               '$pertnetfindings', '$labresult', '$radresult', '$treatmentrendered', '$reasonfortransfer', 
               '$doct_phone_number', '$call_phone_number','$employee_id',NOW())
            ";

    $error = false;

    Start_Transaction();

    $result = mysql_query($insert) or die(mysql_error());

    if ($result) {
        if (isset($_POST['src']) && $_POST['src'] == 'inpat') { //admission_ID, consultation_ID
            $remfromdoct = mysql_query("UPDATE tbl_admission SET Admission_Status='pending' WHERE  Admision_ID='$admission_ID'") or die(mysql_error());
            if (!$remfromdoct) {
                $error = true;
            }
        } else {
            //Sign off the patient 
            $querySignoff = "UPDATE tbl_patient_payment_item_list ppl SET Process_Status = 'signedoff',Signedoff_Date_And_Time =NOW() 
                  WHERE Patient_Payment_ID = '$ppID' AND
                  ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')
		";
            $result2 = mysql_query($querySignoff) or die(mysql_error());

            if (!$result2) {
                $error = true;
            }
        }
    } else {
        $error = true;
    }

    if (!$error) {
        Commit_Transaction();
        echo 1;
    } else {
        Rollback_Transaction();
        echo 0;
    }
} elseif (isset($_POST['actionref']) && $_POST['actionref'] == 'process') {
    $_POST = sanitize_input($_POST);
    $id = $_POST['id'];

    $result = mysql_query("UPDATE tbl_referral_patient set status='served',proccessed_by='$employee_id',date_processed=NOW() where referr_id='$id'") or die(mysql_error());

    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
} else {


    $_GET = sanitize_input($_GET);

    $regid = $_GET['regid'];

    $where = '';
    $rst = '';
    if (isset($_GET['src']) && $_GET['src'] == 'inpat') { //src=inpat admID, consID
        $admID = $_GET['admID'];
        $consID = $_GET['consID'];

        $rst = "src=inpat&consID=$consID";

        $getPatientDetails = mysql_query("SELECT Patient_Name,Gender,TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age FROM tbl_patient_registration WHERE Registration_ID='$regid'") or die(mysql_error());
        $pat = mysql_fetch_assoc($getPatientDetails);

        $getCompanyList = mysql_query("SELECT * FROM tbl_company") or die(mysql_error());

        $getPatientDiagnopsis = mysql_query("SELECT d.disease_name FROM tbl_ward_round w JOIN tbl_ward_round_disease wd
                                 ON wd.Round_ID=w.Round_ID AND wd.diagnosis_type='diagnosis'
                                 JOIN tbl_disease d ON d.disease_ID=wd.disease_ID
                                 WHERE w.consultation_ID='$consID'") or die(mysql_error());

        $getPatienclinicdetaiil = mysql_query("SELECT w.Findings AS maincomplain,'' AS history_present_illness FROM tbl_ward_round w
                                 WHERE w.consultation_ID='$consID' AND Process_Status='served' ORDER BY Round_ID DESC LIMIT 1") or die(mysql_error());

        $patclin = mysql_fetch_assoc($getPatienclinicdetaiil);

        $getMedics = mysql_query("SELECT Product_Name,Doctor_Comment FROM tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_items i
				WHERE pc.consultation_id = '$consID'
				AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID
				AND i.Item_ID = ilc.Item_ID
                                AND ilc.Status = 'dispensed'
				") or die(mysql_error());
    } else {
        $ppID = $_GET['ppID'];
        $pplID = $_GET['pplID'];
        $rst = "ppilid=$pplID";

        $getPatientDetails = mysql_query("SELECT Patient_Name,Gender,TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age FROM tbl_patient_registration WHERE Registration_ID='$regid'") or die(mysql_error());
        $pat = mysql_fetch_assoc($getPatientDetails);

        $getCompanyList = mysql_query("SELECT * FROM tbl_company") or die(mysql_error());

        $getPatientDiagnopsis = mysql_query("SELECT d.disease_name FROM tbl_consultation c JOIN tbl_disease_consultation dc
                                 ON dc.consultation_ID=c.consultation_ID AND dc.diagnosis_type='diagnosis'
                                 JOIN tbl_disease d ON d.disease_id=dc.disease_id
                                 WHERE Patient_Payment_Item_List_ID='$pplID'") or die(mysql_error());

        $getPatienclinicdetaiil = mysql_query("SELECT ch.maincomplain,ch.history_present_illness FROM tbl_consultation c JOIN  tbl_consultation_history ch
                                 ON ch.consultation_ID=c.consultation_ID 
                                 WHERE Patient_Payment_Item_List_ID='$pplID'") or die(mysql_error());

        $patclin = mysql_fetch_assoc($getPatienclinicdetaiil);

        $getMedics = mysql_query("SELECT Product_Name,Doctor_Comment FROM tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_items i
				WHERE pc.consultation_id = (SELECT consultation_ID FROM tbl_consultation where Patient_Payment_Item_List_ID='$pplID')
				AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID
				AND i.Item_ID = ilc.Item_ID
                                AND ilc.Status = 'dispensed'
				") or die(mysql_error());
    }



    $Pharmacy = '';
    while ($phar = mysql_fetch_array($getMedics)) {
        $Pharmacy.= ' ' . $phar['Product_Name'] . '[ ' . $phar['Doctor_Comment'] . ' ]' . ';   ';
    }

    $diagnosis = '';
    $i = 1;
    while ($dign = mysql_fetch_array($getPatientDiagnopsis)) {
        if ($i == 1) {
            $diagnosis = $dign['disease_name'];
        } else {
            $diagnosis .=', ' . $dign['disease_name'];
        }

        $i++;
    }

    $comp = '';
    $comp .='<select name="referral_from" id="referral_from">';
    while ($row = mysql_fetch_array($getCompanyList)) {
        $comp .='<option value="' . $row['Company_ID'] . '">' . $row['Company_Name'] . '</option>';
    }
    $comp .='</select>';

    $qry = mysql_query("SELECT hosp_ID,ref_hosp_name FROM tbl_referral_hosp") or die(mysql_error());
    $sn = 1;
    $refr = '';
    $refr .='<select name="referral_to" id="referral_to"><option value="">Select</option>';
    while ($row = mysql_fetch_array($qry)) {
        $refr .='<option value="' . $row['hosp_ID'] . '">' . $row['ref_hosp_name'] . '</option>';
    }

    $refr .='</select>';

    $html = '<table border="0">';
    $html .= '<tr>'
            . '<td style="width:40%">'
            . '<strong>Reffering From:</strong>&nbsp;&nbsp;' . $comp
            . '</td>'
            . '<td colspan="3">'
            . '<strong>Reffering To:</strong>&nbsp;&nbsp;' . $refr
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3">'
            . '<strong>Date:</strong>&nbsp;&nbsp;<input type="text" style="width:30%" id="transfer_date" name="transfer_date" value="' . date('Y-m-d') . '">&nbsp;&nbsp;&nbsp;&nbsp;'
            . '<strong>age:</strong>&nbsp;&nbsp;<input type="text"  style="width:20%"  name="age" value="' . $pat['age'] . '"/>&nbsp;&nbsp;&nbsp;&nbsp;'
            . '<strong>Gender:</strong>&nbsp;&nbsp;<input type="text" style="width:13%"  name="gender" value="' . $pat['Gender'] . '"/>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3">'
            . '<strong>Diagnosis:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<textarea  style="width:100%;display:inline;margin:0" id="diagnosis" name="diagnosis">' . $diagnosis . '</textarea>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="4">'
            . '<strong>Temperature:</strong>&nbsp;&nbsp;<input type="text" id="temp" style="width:13%" name="temp" value="">&nbsp;&nbsp;&nbsp;'
            . '<strong>Heart Rate:</strong>&nbsp;&nbsp;<input type="text" id="heatrate"  name="heatrate" style="width:13%" value=""/>&nbsp;&nbsp;&nbsp;'
            . '<strong>Respiratory rate:</strong>&nbsp;&nbsp;<input type="text" id="resprate"  style="width:13%" name="resprate" value=""/>&nbsp;&nbsp;&nbsp;'
            . '<strong>Blood Pressure:</strong>&nbsp;&nbsp;<input type="text" id="bloodpressure"  style="width:13%" name="bloodpressure" value=""/>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td style="width:40%">'
            . '<strong>Mental status (Circle):</strong>&nbsp;&nbsp;'
            . '<input type="text" name="mental_status" id="mental_status" style="width:30%;"/>'
            . '</td>'
            . '<td colspan="3">'
            . '<strong>Alert:</strong>&nbsp;&nbsp;<select id="alert">'
            . '<option value="">select</option>'
            . '<option>Respond to voice</option>'
            . '<option>Respond only to pain</option>'
            . '<option>Unresponsive</option>'
            . '</select>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3">'
            . '<strong>Patient History:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<textarea  style="width:100%;display:inline;margin:0" id="patienthist" name="patienthist">' . $patclin['history_present_illness'] . '</textarea>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3">'
            . '<strong>Chronic medications:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="art-button-green" value="Select" onclick="getChrnMedics()"/>&nbsp;&nbsp;&nbsp;&nbsp;<textarea  style="width:100%;display:inline;margin:0" id="chrnicmed" name="chrnicmed"></textarea>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3">'
            . '<strong>Medication allergies:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<textarea  style="width:100%;display:inline;margin:0" id="medalergy" name="medalergy"></textarea>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3">'
            . '<strong>Pertinent exam findings:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<textarea  style="width:100%;display:inline;margin:0" id="pertnetfindings" name="pertnetfindings">' . $patclin['maincomplain'] . '</textarea>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="2">'
            . '<strong>Laboratory results:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<a href="privewlabresult.php?' . $rst . '&regid=' . $regid . '" target="_blank"><input type="button" class="art-button-green" value="Preview"/></a>&nbsp;&nbsp;&nbsp;&nbsp;<textarea  style="width:100%;display:inline;margin:0" id="labresult" name="labresult"></textarea>'
            . '</td>'
            . '<td>'
            . '<strong>Radiology results:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<a href="privewradresult.php?' . $rst . '&regid=' . $regid . '" target="_blank"><input type="button" class="art-button-green" value="Preview"/></a>&nbsp;&nbsp;&nbsp;&nbsp;<textarea  style="width:100%;display:inline;margin:0" id="radresult" name="radresult"></textarea>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="2">'
            . '<strong>Treatment rendered prior to transfer:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<textarea  style="width:100%;display:inline;margin:0" id="treatmentrendered" name="treatmentrendered">' . $Pharmacy . '</textarea>'
            . '</td>'
            . '<td>'
            . '<strong>Medical reason for transfer:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<textarea  style="width:100%;display:inline;margin:0" id="reasonfortransfer" name="reasonfortransfer"></textarea>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3">'
            . '<strong>Doctor Name:</strong>&nbsp;&nbsp;&nbsp;&nbsp;' . $_SESSION['userinfo']['Employee_Name'] . ' '
            . '&nbsp;&nbsp;&nbsp;&nbsp;<strong>Phone number</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="width:15%;display:inline;margin:0" id="doct_phone_number" name="doct_phone_number" value="">&nbsp;&nbsp;&nbsp;&nbsp;'
            . '<strong>To faciltate transfer of care, call :</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="width:15%;" id="call_phone_number" name="call_phone_number" value="">'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="3"><br/><hr style="width:100%"/></br/></td>'
            . '</tr>';

    $html .= '</table>';

    echo $html;
}










