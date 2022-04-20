<?php

include("./includes/connection.php");
include 'Patient_Record_Review_out_frame_print.php';
include 'Patient_Record_Review_in_frame_print.php';
@session_start();

$data .= "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";

$consultation_ID = 0;
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = trim($_GET['consultation_ID']);
}if (isset($_GET['Registration_ID'])) {
    $Registration_ID = trim($_GET['Registration_ID']);
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
            // $data .= $Ward."  ".$District."  ".$Ward; exit;
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

$rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury,ch.past_medical_history,ch.review_of_other_systems,ch.family_social_history,ch.doctor_plan_suggestion,ch.Gynocological_history FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));

$docConsultation = mysqli_query($conn,"SELECT c.employee_ID,Consultation_Date_And_Time,e.Employee_Name,e.Employee_Type FROM tbl_consultation c JOIN tbl_employee e ON e.Employee_ID=c.employee_ID  WHERE c.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));
$docResult = mysqli_fetch_assoc($docConsultation);
//die($consultation_ID);
$Consultation_Date_And_Time = $docResult['Consultation_Date_And_Time'];
$Employee_Name = $docResult['Employee_Name'];
$Employee_Title = ucfirst(strtolower($docResult['Employee_Type']));
//$Consultation_Date_And_Time=$docResult['Consultation_Date_And_Time'];

$rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury,ch.past_medical_history,ch.review_of_other_systems,ch.family_social_history,ch.doctor_plan_suggestion,ch.Gynocological_history FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID=$Registration_ID ") or die(mysqli_error($conn));


if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sponsoDetails = '';
if (strtolower($Guarantor_Name) != 'cash') {
    $sponsoDetails = ',&nbsp;&nbsp;<b>Address:</b>  ' . $Sponsor_Postal_Address . ' ,&nbsp;&nbsp;<b>Benefit Limit:</b>' . $Benefit_Limit . '';
}

$hasOutpatientDetails = false;
$hasInpatientDetails = false;


$check_was_inpatient = mysqli_query($conn,"SELECT consultation_ID FROM tbl_check_in_details WHERE consultation_ID = '" . $_GET['consultation_ID'] . "' AND Registration_ID='" . $_GET['Registration_ID'] . "' AND Admit_Status='admitted'") or die(mysqli_error($conn));

if (mysqli_num_rows($check_was_inpatient) > 0) {
    $hasInpatientDetails = true;
}

$showItemStatus = true;
 $display = "";

if (isset($_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file']) && $_SESSION['hospitalConsultaioninfo']['en_item_status_pat_file'] == '1') {
    $showItemStatus = false;
    $display = "style='display:none' class='display-remove'";
   
}

$check_was_outpatient = mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE consultation_ID = '" . $_GET['consultation_ID'] . "' AND Patient_Payment_Item_List_ID IS NULL") or die(mysqli_error($conn));

if (mysqli_num_rows($check_was_outpatient) == 0) {
    $hasOutpatientDetails = true;
}

$data .= '<fieldset style="width:100%;padding:5px;background-color:white;margin-top:20px;">
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center  ">
        <b align="center">PATIENT MEDICATION RECORD</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="" width="100%" style="margin-left:2px" cellspacing="0" cellpadding="0">
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

$data .= displayOutpatientInfos($hasOutpatientDetails,$hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID, $display);

$data .= '<br/><br/>';

$data .= displayInpantientInfo($hasInpatientDetails, $rsDoc, $Registration_ID, $consultation_ID,$display);

$data .= '<br/><br/>';
$date = $_GET['date'];
// $data .= $date;
$data .= displayTbInfoReport($Registration_ID,$date);

$data .= '</fieldset>
<div style="margin-top:10px">';


$data .= '</div>';


function displayTbInfoReport($registreationId,$date){
    $today = Date('Y-m-d');
    $sql = "SELECT * FROM tbl_bt_diagnosis WHERE Registration_ID='$registreationId' AND date_time=DATE('$date')";
    $result = mysqli_query($conn,$sql);
    $num = mysqli_num_rows($result);
    if($num > 0){
    $d .= "<table id='table' style='width:100%;'>
    <tr style='text-align:left; font-weight:bold; background:#C0C0C0;' colspan='2'>TB SCREENING</tr>";
        while($row=mysqli_fetch_assoc($result)){
        extract($row);

        $d .= '
        <tr style="text-align:left; font-weight:bold; background:#C0C0C0;" colspan="2">TB SCREENING</tr>
        <tr>
        <td>Cough for two weeks or more.</td>
        <td style="text-align:center;">'.$cough.'</td>
        </tr>
        
        <tr>
        <td>Cough for less than two weeks.</td>
        <td style="text-align:center">'.$household_history.'</td>
        
        </tr>
        <tr>
        <td>Sputum production.</td>
        <td style="text-align:center">'.$fever.'</td>
        
        </tr>
        <tr>
        <td>Coughing up blood.</td>
        <td style="text-align:center">'.$irritability.'</td>
        
        </tr>
        <tr>
        <td>History for household contact with TB.</td>
        <td style="text-align:center">'.$weight_change.'</td>
        
        </tr>
        <tr>
        <td>Fever of any duration.</td>
        <td style="text-align:center">'.$past_treatment.'</td>
        
        </tr>
        <tr>
        <td>Radical activities or irritability for two weeks or more.</td>
        <td style="text-align:center">'.$excessive_sweat.'</td>
        
        </tr>
        <tr>
        <td>Inadequate weight gain,faltering or loss.</td>
        <td style="text-align:center">'.$other_symptoms.'</td>
        
        </tr>
        <tr>
        <td>Past history of TB treatment.</td>
        <td style="text-align:center">'.$cough_less.'</td>
        
        </tr>
        <tr>
        <td>Excessive night sweats.</td>
        <td style="text-align:center">'.$sputum_yes.'</td>
        </tr>
        <tr>
        <td>Any other symptoms (chest pain, chest tightness).</td>
        <td style="text-align:center">'.$cough_blood.'</td>
        </tr>
        <tr>
        <td style="text-align:center"><b>Total Score.</b></td>
        <td style="text-align:center; background:#C0C0C0;"><b>'.$total_score.'</b></td>
        </tr>
        <tr>
        ';
    }
    $d.= "</table>";
    }

    return $d;
}

//echo $data;

include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4-L');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output();
