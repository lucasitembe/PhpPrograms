<style>
    .userinfo td,tr{
        height:20px ;
        border:none !important; 
    }
    .userinfo tr{
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
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");

if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Admission_Works'])) {
//        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        } else {
//            @session_start();
//            if (!isset($_SESSION['Admission_Supervisor'])) {
//                header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
//            }
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


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
    if ($no > 0) {
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
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Deseased = '';
        $Sponsor_Postal_Address = '';
        $Benefit_Limit = '';
        $Patient_Picture = '';
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
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Country = '';
    $Deseased = '';
    $Sponsor_Postal_Address = '';
    $Benefit_Limit = '';
    $Patient_Picture = '';
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
    $age = 0;
}
?>
<a href="print_patient_discharge_summery.php?consultation_ID=<?php echo $_GET['consultation_ID'] ?>&Registration_ID=<?php echo $_GET['Registration_ID'] ?>" target="_blank" class="art-button-green">PRINT PATIENT DISCHARGE SUMMARY</a>

<!-- get employee id-->
<?php
$consultation_ID = 0;
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = trim($_GET['consultation_ID']);
}
//die($consultation_ID);
$docConsultation = mysqli_query($conn,"SELECT c.employee_ID,Consultation_Date_And_Time,e.Employee_Name,e.Employee_Type FROM tbl_consultation c JOIN tbl_employee e ON e.Employee_ID=c.employee_ID  WHERE c.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
$docResult = mysqli_fetch_assoc($docConsultation);
//die($consultation_ID);
$Consultation_Date_And_Time = $docResult['Consultation_Date_And_Time'];
$Employee_Name = $docResult['Employee_Name'];
$Employee_Title = ucfirst(strtolower($docResult['Employee_Type']));
//$Consultation_Date_And_Time=$docResult['Consultation_Date_And_Time'];

$rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
$data = '';

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sponsoDetails = '';
if (strtolower($Guarantor_Name) != 'cash') {
    $sponsoDetails = ',&nbsp;&nbsp;<b>Address:</b>  ' . $Sponsor_Postal_Address . ' ,&nbsp;&nbsp;<b>Benefit Limit:</b>' . $Benefit_Limit . '';
}

$showItemStatus = true;
$display = "";

if (isset($_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file']) && $_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file'] == '1') {
    $showItemStatus = false;
    $display = "style='display:none' class='display-remove'";
}

echo '<fieldset style="width:99%;height:460px ;padding:5px;background-color:white;margin-top:20px;overflow-x:hidden;overflow-y:scroll">
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center  " id="outpatient">
        <b align="center">PATIENT DISCHARGE SUMMARY</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan="">' . $Patient_Name . '</td>
                <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan="">' . $Country . '</td>
                <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan="">' . $Region . '</td>
                <td rowspan="4" width="100">
                    <img width="120" height="90" name="Patient_Picture" id="Patient_Pictured" src="./patientImages/' . $Patient_Picture . '">
                </td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Registration #:</b></td><td>' . $Registration_ID . '</td><td style="text-align:right"><b>Phone #:</b></td><td style="">' . $Phone_Number . '</td><td style="text-align:right"><b>District:</b></td><td style="">' . $District . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td style="">' . date("j F, Y", strtotime($Date_Of_Birth)) . ' </td><td style="text-align:right"><b>Gender:</b></td><td style="">' . $Gender . '</td><td style="text-align:right"><b>Diseased:</b></td><td style="">' . $Deseased . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right" ><b>Insurance Details:</b></td><td colspan=""> ' . $Guarantor_Name . $sponsoDetails . '</td>
                <td style="width:10%;text-align:right" ><b>Consultation Date:</b></td><td colspan=""> ' . $Consultation_Date_And_Time . '</td>
                <td style="width:10%;text-align:right" ><b>Consultant :</b></td><td colspan=""> ' . $Employee_Title . ' . ' . ucfirst($Employee_Name) . '</td>
            </tr>
        </table>
    </div>';


echo displayInpantientInfo($hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display);

echo '<br/><br/>
</fieldset>';

function displayInpantientInfo($hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display) {
    $patientInfo_qr = mysqli_query($conn,"SELECT Gender, TIMESTAMPDIFF(YEAR, Date_Of_Birth,CURDATE()) AS age FROM  tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

    $patInfo = mysqli_fetch_assoc($patientInfo_qr);

    $patgender = $patInfo['Gender'];
    $patage = (int) $patInfo['age'];
    // if ($hasInpatientDetails) {
    //get patient info Dicharged
    $result = mysqli_query($conn,"SELECT Admission_Date_Time,Admission_Employee_ID,Employee_Name,Admission_Status,Hospital_Ward_Name,ad.Bed_Name,Kin_Name,ToBe_Admitted_Reason,TIMESTAMPDIFF(DAY,Admission_Date_Time,Discharge_Date_Time) AS no_of_days FROM tbl_admission ad
                    INNER JOIN tbl_check_in_details cd ON cd.Admission_ID = ad.Admision_ID 
                    JOIN tbl_hospital_ward hw ON hw.Hospital_Ward_ID=ad.Hospital_Ward_ID 
                     
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
    $no_of_days = $info['no_of_days'];
    $continuation_sheet = $info['ToBe_Admitted_Reason'];

    if ($Admission_Status == 'pending') {
        $Admission_Status = 'Discharge State';
    }

    $discharge_details = '';

    if (trim($Admission_Status) == 'Discharged') {
        // die( 'basdfasdf' );
        $result_disc = mysqli_query($conn,"SELECT Discharge_Date_Time,Employee_Name,Discharge_Reason FROM tbl_admission ad
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
                <td style="width:10%;text-align:right "><b># Days</b></td>
                <td>' . $no_of_days . '</td>
                <td style="width:10%;text-align:right "><b>Discharge Reason</b></td>
                <td>' . $Discharge_Reason . '</td>
                <td style="width:10%;text-align:right "><b>Discharge Date</b></td>
                <td>' . $Discharge_Date_Time . '</td>
            </tr>';
    }

    $data .= '
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center " id="inpatient">
        <b align="center">INPATIENT DETAILS</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" width="100%" border="0" style="border:none !important">
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
                <td colspan="5" style="border:1px solid #ccc">' . $continuation_sheet . '</td>
           </tr>  
        </table>
    </div> 
    <br/><br/>
    ';
    $sn = 1;


    $rscomp = mysqli_query($conn,"SELECT chiefcomplain,summary FROM tbl_ip_op_pat_notes WHERE consultation_ID='$consultation_ID'") or die(mysqli_error($conn));

    if (mysqli_num_rows($rscomp) > 0) {
        $row_comp = mysqli_fetch_assoc($rscomp);
        $data .= '<div  width="100%" id="findings-remarks">
        <div style="padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  ">
            <b>CHIEF COMPLAINS</b>
        </div>
        ';
        $data .= '<table id="userCemmnts" border="1" width="100%" style="margin-left:2px">
            <tr>
                <td style="width: 10%;text-align:right"><b>Chief Complains</b></td><td>' . $row_comp['chiefcomplain'] . '</td></td>
            </tr>';

        $data .= '</table></div>';

        $data .= '<div  width="100%" id="summery">
        <div style="padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  ">
            <b>SUMMARY</b>
        </div>
        ';
        $data .= '<table id="userCemmnts" border="1" width="100%" style="margin-left:2px">
            <tr>
                <td style="width: 10%;text-align:right"><b>Summary</b></td><td>' . $row_comp['summary'] . '</td></td>
            </tr>';

        $data .= '</table></div>';
    }

    $diagnosis_qr = "SELECT  diagnosis_type,disease_name,Round_Disease_Date_And_Time,disease_code FROM tbl_ward_round_disease wd,tbl_ward_round wr, tbl_disease d
		    WHERE 
                    wd.disease_ID = d.disease_ID AND
                    wr.Round_ID = wd.Round_ID AND
                    wr.consultation_ID ='$consultation_ID' AND
                    diagnosis_type = 'diagnosis' order by   Ward_Round_Disease_ID desc limit 1 
		    ";
    $result_diag = mysqli_query($conn,$diagnosis_qr) or die(mysqli_error($conn));
    if (mysqli_num_rows($result_diag) > 0) {
        $row_diag = mysqli_fetch_assoc($result_diag);
        $data .= "
    <div width='100%' id='diagonsis'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>DIAGNOSIS</b>
        </div>";
        $data .= '<table id="userCemmnts" border="1" width="100%" style="margin-left:2px">
            <tr>
                <td style="width: 10%;text-align:right"><b>Final Diagnosis</b></td><td>' . $row_diag['disease_name'] . '</td></td>
            </tr>';

        $data .= '</table></div>';
    }


    //vital signs
//
//    $vt_qr_op = "SELECT   bp, pr, rp, tp, spo2, rgb, muac, hc, height, weight FROM  tbl_consultation_history 
//		    WHERE 
//                    consultation_ID ='$consultation_ID'
//                        order by consultation_histry_ID desc limit 1 
//		    ";
//    $result_vt_op = mysqli_query($conn,$vt_qr_op) or die(mysqli_error($conn)); //
//
//    $vt_qr_ip = "SELECT  bp, pr, rp, tp, spo2, rgb, muac, hc, height, weight FROM tbl_ward_round wd
//		    WHERE 
//                    wd.consultation_ID ='$consultation_ID'
//                        order by Round_ID desc limit 1 
//		    ";
//    
//    $result_vt_ip = mysqli_query($conn,$vt_qr_ip) or die(mysqli_error($conn));
//
//
//    $vt_qr_bf = "SELECT  bp, pr, rp, tp, spo2, rgb, muac, hc, height, weight FROM tbl_ip_op_pat_notes
//		    WHERE 
//                    consultation_ID ='$consultation_ID'
//		    ";
//    $result_vt_bf = mysqli_query($conn,$vt_qr_bf) or die(mysqli_error($conn));
//
//    $row_vt_ip = mysqli_fetch_assoc($result_vt_ip);
//    $row_vt_op = mysqli_fetch_assoc($result_vt_op);
//    $row_vt_bf = mysqli_fetch_assoc($result_vt_bf);
//    $filterage=($patage<=12)?"":"style='display:none'";
//    $data .= "
//        <div width='100%' id='diagonsis'>
//        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
//            <b>VITALS</b>
//        </div>";
//    $data .= '<table id="userCemmnts" border="1" width="100%" style="margin-left:2px">
//            <tr>
//               <td style="width: 10%;text-align:right"><b>OPD Vitals</b></td>
//                   <td>
//                       <table>
//                            <tr><td>BP<input name="bp" id="bp" value="'.$row_vt_op['bp'].'"></td><td>PR<input name="pr" id="pr" value="'.$row_vt_op['pr'].'"></td><td>RR<input name="rp" id="rp" value="'.$row_vt_op['rp'].'"></td><td>TP<input name="tp" id="tp" value="'.$row_vt_op['tp'].'"></td><td>SpO2<input name="spo2" id="spo2" value="'.$row_vt_op['spo2'].'"></td></tr>
//                            <tr><td>RBG <input name="rgb" id="rgb" value="'.$row_vt_op['rgb'].'"></td><td  '.$filterage.'>MUAC<input name="muac" id="muac" value="'.$row_vt_op['muac'].'"></td><td  '.$filterage.'>HC<input name="hc" id="hc" value="' . $row_vt_op['hc'] . '"></td><td>HEIGHT <input name="height" id="height"  value="' . $row_vt_op['height'] . '"></td><td>WEIGHT <input name="weight" id="weight" value="' . $row_vt_op['weight'] . '"></td></tr>
//                         </table>
//                   </td>
//            </tr>
//             <tr>
//               <td style="width: 10%;text-align:right"><b>IPD Vitals</b></td><td>
//                <table>
//                            <tr><td>BP<input name="bp" id="bp" value="'.$row_vt_ip['bp'].'"></td><td>PR<input name="pr" id="pr" value="'.$row_vt_ip['pr'].'"></td><td>RR<input name="rp" id="rp" value="'.$row_vt_ip['rp'].'"></td><td>TP<input name="tp" id="tp" value="'.$row_vt_ip['tp'].'"></td><td>SpO2<input name="spo2" id="spo2" value="'.$row_vt_ip['spo2'].'"></td></tr>
//                            <tr><td>RBG <input name="rgb" id="rgb" value="'.$row_vt_ip['rgb'].'"></td><td  '.$filterage.'>MUAC<input name="muac" id="muac" value="'.$row_vt_ip['muac'].'"></td><td  '.$filterage.'>HC<input name="hc" id="hc" value="' . $row_vt_ip['hc'] . '"></td><td>HEIGHT <input name="height" id="height"  value="' . $row_vt_ip['height'] . '"></td><td>WEIGHT <input name="weight" id="weight" value="' . $row_vt_ip['weight'] . '"></td></tr>
//                         </table>
//              </td></td>
//            </tr>
//             <tr>
//               <td style="width: 10%;text-align:right"><b>Basic Info Vitals</b></td><td>
//                 <table>
//                            <tr><td>BP<input name="bp" id="bp" value="'.$row_vt_bf['bp'].'"></td><td>PR<input name="pr" id="pr" value="'.$row_vt_bf['pr'].'"></td><td>RR<input name="rp" id="rp" value="'.$row_vt_bf['rp'].'"></td><td>TP<input name="tp" id="tp" value="'.$row_vt_bf['tp'].'"></td><td>SpO2<input name="spo2" id="spo2" value="'.$row_vt_bf['spo2'].'"></td></tr>
//                            <tr><td>RBG <input name="rgb" id="rgb" value="'.$row_vt_bf['rgb'].'"></td><td  '.$filterage.'>MUAC<input name="muac" id="muac" value="'.$row_vt_bf['muac'].'"></td><td  '.$filterage.'>HC<input name="hc" id="hc" value="' . $row_vt_bf['hc'] . '"></td><td>HEIGHT <input name="height" id="height"  value="' . $row_vt_bf['height'] . '"></td><td>WEIGHT <input name="weight" id="weight" value="' . $row_vt_bf['weight'] . '"></td></tr>
//                         </table>
//              </td></td>
//            </tr>
//            ';
//
//    $data .= '</table></div>';
//
    $sn = 1;


    $qrLab = "SELECT Payment_Item_Cache_List_ID,tbl_items.Item_ID,test_result_ID,Employee_Name,Product_Name,tbl_item_list_cache.remarks,Doctor_Comment,Transaction_Date_And_Time FROM tbl_item_list_cache 
        INNER JOIN tbl_test_results AS trs ON Payment_Item_Cache_List_ID=payment_item_ID 
		INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID
		JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID
                JOIN tbl_employee e ON e.Employee_ID=tbl_item_list_cache.Consultant_ID
         JOIN tbl_consultation tc ON  tc.consultation_ID=tbl_payment_cache.consultation_id		
		WHERE tc.Registration_ID='" . $Registration_ID . "' AND tbl_payment_cache.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%' AND tbl_item_list_cache.Check_In_Type='Laboratory'";

    $qrLabWithoutResult = "SELECT Payment_Item_Cache_List_ID,tbl_items.Item_ID,Employee_Name,Product_Name,tbl_item_list_cache.remarks,Doctor_Comment,Transaction_Date_And_Time FROM tbl_item_list_cache 
        INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
        JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID
                JOIN tbl_employee e ON e.Employee_ID=tbl_item_list_cache.Consultant_ID
         JOIN tbl_consultation tc ON  tc.consultation_ID=tbl_payment_cache.consultation_id      
        WHERE tbl_item_list_cache.Status != 'notsaved' AND tc.Registration_ID='" . $Registration_ID . "' AND tbl_payment_cache.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%' AND tbl_item_list_cache.Check_In_Type='Laboratory'";


    $result = mysqli_query($conn,$qrLab) or die(mysqli_error($conn));
    $resultWithout = mysqli_query($conn,$qrLabWithoutResult) or die(mysqli_error($conn));
    $tempIlc = array();
    $temp = 1;


    $numtotal = mysqli_num_rows($result) + mysqli_num_rows($resultWithout);

    if ($numtotal > 0) {
        $data .= "
    <div width='100%' id='Laboratory_Results'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>LABORATORY</b>
        </div><table  border='1' width='100%' style='margin-left:2px'>";

        while ($row = mysqli_fetch_array($result)) {
            $tempIlc[] = $row['Payment_Item_Cache_List_ID'];
            $st = '';
            $ppil = $row['Payment_Item_Cache_List_ID'];
            $item_ID = $row['Item_ID'];


            $data .= '
            <tr><td colspan="8"><b>' . $temp++ . '.</b> <b>' . $row['Product_Name'] . '</b></td></tr>';
        }

        if (mysqli_num_rows($result) == 0) {

            $data1 = '';
            while ($row = mysqli_fetch_array($resultWithout)) {
                if (!in_array($row['Payment_Item_Cache_List_ID'], $tempIlc)) {
                    $st = '';

                    $data .= '<tr><td colspan="8"><b>' . $temp++ . '.</b> <b>' . $row['Product_Name'] . '</b></td></tr>';
                }
            }//endof withought result 
        }
        $data .= "</table></div>";
        $tbrad = " <table id='radiology' border='1' width='100%' style='margin-left:2px'>
            <tr>
                <td style='width:3%;'><b>SN</b></td>	
                <td><b>Test Name</b></b></td>
            </tr>";

        $qr = "SELECT rpt.Status,pc.Registration_ID,i.Product_Name,rpt.Remarks,Payment_Item_Cache_List_ID,
                          rpt.Date_Time,Radiologist_ID,Sonographer_ID,Patient_Payment_Item_List_ID,i.Item_ID,ilc.Transaction_Date_And_Time FROM
			tbl_radiology_patient_tests rpt INNER JOIN tbl_items i
			ON rpt.Item_ID = i.Item_ID 
                        JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID
                        JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
                        
			WHERE rpt.Registration_ID = '$Registration_ID' AND
			pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%'
			";

        $qrnotdone = "SELECT ilc.Status,ilc.Transaction_Date_And_Time,ilc.Doctor_Comment,pc.Registration_ID,i.Product_Name
                          ,Payment_Item_Cache_List_ID,i.Item_ID FROM
			tbl_item_list_cache ilc 
                        JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
                        INNER JOIN tbl_items i ON ilc.Item_ID = i.Item_ID 
                        
			WHERE   ilc.Status != 'notsaved' AND  pc.Registration_ID = '$Registration_ID' AND
			pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%' AND ilc.Check_In_Type='Radiology'
			";
        //  die($qrnotdone);

        $select_patients_qry = mysqli_query($conn,$qr) or die(mysqli_error($conn));

        $select_patients_notdone_qry = mysqli_query($conn,$qrnotdone) or die(mysqli_error($conn));


        $numtotalrad = mysqli_num_rows($select_patients_qry) + mysqli_num_rows($select_patients_notdone_qry);

        if ($numtotalrad > 0) {
            $tempIlc = array();
            $sn = 1;
            $data .= "
        <div width='100%' id='radiology'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                <b>RADIOLOGY</b>
            </div>
       ";
            $data .= $tbrad;
            while ($patient = mysqli_fetch_assoc($select_patients_qry)) {
                $status = $patient['Status'];
                $patient_numeber = $patient['Registration_ID'];
                $test_name = $patient['Product_Name'];

                $data .= '<tr>';
                $data .= '<td>' . $sn . '</td>';
                $data .= '<td>' . $test_name . '</td>';
                $data .= '</tr>';
                ;
                $sn++;
            }


            while ($patient = mysqli_fetch_assoc($select_patients_notdone_qry)) {

                if (!in_array($patient['Payment_Item_Cache_List_ID'], $tempIlc)) {
                    $status = $patient['Status'];
                    $patient_numeber = $patient['Registration_ID'];
                    $test_name = $patient['Product_Name'];

                    $data .= '<tr>';
                    $data .= '<td>' . $sn . '</td>';
                    $data .= '<td>' . $test_name . '</td>';
                    $data .= '</tr>';
                    $sn++;
                }
            }
            $data .= "</table>";
            if (mysqli_num_rows($select_patients_qry) == 0) {
                $data .= "</div>";
            }
        }
    }

    $qr = "SELECT  ilc.Payment_Item_Cache_List_ID,ilc.Status,Product_Name,Doctor_Comment,ilc.Remarks,i.Item_ID,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.Consultant_ID) AS sentby,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.ServedBy) AS servedby,ilc.Transaction_Date_And_Time AS sentOn,ServedDateTime  AS servedOn FROM
                     tbl_item_list_cache ilc 
                          JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
                           
                          JOIN tbl_items i
                ON ilc.Item_ID = i.Item_ID 
                WHERE    ilc.Status != 'notsaved' AND pc.Registration_ID = '$Registration_ID' AND
                pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%' AND ilc.Check_In_Type='Surgery'";

    $select_qr = mysqli_query($conn,$qr) or die(mysqli_error($conn));

    if (mysqli_num_rows($select_qr) > 0) {

        $data .= "
    
    <div width='100%' id='departments_comments'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>SURGERY</b>
        </div>";

        $data .= ' <table  border="1" width="100%" style="margin-left:2px">
                    <tr>
                        <td style="width:3%;"><b>SN</b></td>    
                        <td><b>Test Name</b></b></td>
                    </tr>';

        $sn = 1;
        while ($patient = mysqli_fetch_assoc($select_qr)) {

            $test_name = $patient['Product_Name'];

            //if (strtolower($patient['Status'] != 'served')) {
            $data .= '<tr>';
            $data .= '<td>' . $sn . '</td>';
            $data .= '<td>' . $test_name . '</td>';

            $data .= '</tr>';
            ;
            $sn++;
            //}
        }
        $data .= '</table>';
    }



    $qry = "SELECT tit.Product_Name,ilc.Status,ilc.Doctor_Comment,ilc.remarks,em.Employee_Name,em.Employee_Type,ilc.ServedDateTime,ilc.ServedBy,ilc.Transaction_Date_And_Time AS DateTime,'doctor' as origin FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
		
                JOIN tbl_items tit ON tit.Item_ID=ilc.Item_ID 
		JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID
		JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
		WHERE  ilc.Status != 'notsaved' AND  pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%' AND 
                    ilc.Check_In_Type='Procedure' 
	
		";


    $rs = mysqli_query($conn,$qry)or die(mysqli_error($conn));
    $sn = 1;

    if (mysqli_num_rows($rs) > 0) {
        $data .= " </div>
        <div width='100%' id='departments_comments'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left'>
                <b>PROCEDURE</b>
            </div>";

        $data .= '<table width="100%" style="margin-left:2px">';
        $data .= '<tr style="font-weight:bold;" id="thead">';
        $data .= '<td style="width:3%;">SN</td>';
        $data .= '<td>Procedure Name</td>';

        $data .= '</tr>';
        while ($row = mysqli_fetch_assoc($rs)) {
            $test_name = $row['Product_Name'];

            $data .= '<tr style="text-align:left">';
            $data .= '<td>' . $sn . '</td>';
            $data .= '<td>' . $test_name . '</td>';
            $data .= '</tr>';
            ;
            $sn++;
        }

        $data .= '</table></div> ';
    }



    $subqr = "SELECT ilc.Employee_Created,ilc.Status,Product_Name,Quantity,Edited_Quantity,Dispense_Date_Time,Employee_Name,Doctor_Comment,Transaction_Date_And_Time,Dispensor FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID = pc.Payment_Cache_ID JOIN tbl_items i ON i.item_ID = ilc.item_ID
           	   
           JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID
	   JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
	   WHERE   ilc.Status != 'notsaved' AND  Check_In_Type='Pharmacy' AND pc.Registration_ID='$Registration_ID' AND pc.consultation_id ='$consultation_ID' AND Billing_Type LIKE '%Inpatient%'";


    $result = mysqli_query($conn,$subqr) or die(mysqli_error($conn));

    $sn = 1;

    if (mysqli_num_rows($result) > 0) {
        $data .= "  
        <div width='100%' id='departments_comments'>
            <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                <b>PHARMACY</b>
            </div>";
        $data .= '<table width="100%" style="margin-left:2px">';
        $data .= '<tr style="font-weight:bold;" id="thead">';
        $data .= '<td style="width:3%;">SN</td>';
        $data .= '<td style="text-align:left">Medication Name</td>';
        $data .= '</tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            $Pharmacy = $row['Product_Name'];
            $data .= '<tr>';
            $data .= '<td style="width:3%;">' . $sn . '</td>';
            $data .= '<td style="text-align:left">' . $Pharmacy . '</td>';
            $data .= '</tr>';

            $sn++;
        }

        $data .= '</table></div>';
    }

    $rm_qr = "SELECT  remarks FROM tbl_ward_round wd
		    WHERE 
                    wd.consultation_ID ='$consultation_ID'
                        order by Round_ID desc limit 1 
		    ";
    $result_rm = mysqli_query($conn,$rm_qr) or die(mysqli_error($conn));
    if (mysqli_num_rows($result_rm) > 0) {
        $row_rm = mysqli_fetch_assoc($result_rm);
        $data .= "
        <div width='100%' id='diagonsis'>
        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
            <b>REMARKS AND FOLLOW UP</b>
        </div>";
        $data .= '<table id="userCemmnts" border="1" width="100%" style="margin-left:2px">
            <tr>
                <td style="width: 10%;text-align:right"><b>Remarks</b></td><td>' . $row_rm['remarks'] . '</td></td>
            </tr>';

        $data .= '</table></div>';
    }

    return $data;
}

include("./includes/footer.php");
?>