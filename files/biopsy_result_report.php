<?php
include("./includes/connection.php");
include 'Patient_Record_Review_out_frame_print.php';
include 'Patient_Record_Review_in_frame_print.php';
@session_start();
 $Employee_Name_Print = $_SESSION['userinfo']['Employee_Name'];


    $Biopsy_ID = trim($_GET['Biopsy_ID']);
    $Payment_Item_Cache_List_ID = trim($_GET['ppil']);
    $Registration_ID = trim($_GET['Registration_ID']);

//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information

    $Registration_ID = $_GET['Registration_ID'];

    $select_Patient = mysqli_query($conn,"SELECT pr.Patient_Name,pr.Sponsor_ID,pr.Registration_ID,pr.Date_Of_Birth, pr.Gender,pr.Region,pr.Country,pr.Diseased,pr.District,pr.Ward, pr.Member_Number,pr.Member_Card_Expire_Date, pr.Phone_Number, pr.Religion_ID, pr.Tribe,sp.Guarantor_Name,sp.Claim_Number_Status, sp.Postal_Address,sp.Benefit_Limit from tbl_patient_registration pr, tbl_sponsor sp where pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
   // $no = mysqli_num_rows($select_Patient);
    if (mysqli_num_rows($select_Patient)> 0) {
        while ($row = mysqli_fetch_assoc($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
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
            $Religion_Name = $row['Religion_Name'];
            $Tribe = $row['Tribe'];
            // $data .= $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
    } else {
        $Registration_ID = 'NO DATA';
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
        $Tribe = '';
        $Religion_Name = '';
        $age = 0;
    }


$consultation_ID = 0;
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = trim($_GET['consultation_ID']);
}
//die($consultation_ID);
$docConsultation = mysqli_query($conn,"SELECT c.employee_ID,Consultation_Date_And_Time,e.Employee_Name,e.Employee_Type FROM tbl_consultation c JOIN tbl_employee e ON e.Employee_ID=c.employee_ID  WHERE c.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
$docResult = mysqli_fetch_assoc($docConsultation);
//die($consultation_ID);
$Consultation_Date_And_Time = $docResult['Consultation_Date_And_Time'];
$Employee_Name = $docResult['Employee_Name'];
$Employee_Title = ucfirst(strtolower($docResult['Employee_Type']));
//$Consultation_Date_And_Time=$docResult['Consultation_Date_And_Time'];

$rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

$docConsultation = mysqli_query($conn,"SELECT c.employee_ID,Consultation_Date_And_Time,e.Employee_Name,e.Employee_Type FROM tbl_consultation c JOIN tbl_employee e ON e.Employee_ID=c.employee_ID  WHERE c.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
$docResult = mysqli_fetch_assoc($docConsultation);
//die($consultation_ID);
$Consultation_Date_And_Time = $docResult['Consultation_Date_And_Time'];
$Employee_Name = $docResult['Employee_Name'];
$Employee_Title = ucfirst(strtolower($docResult['Employee_Type']));
//$Consultation_Date_And_Time=$docResult['Consultation_Date_And_Time'];

$rsDoc = mysqli_query($conn,"SELECT Employee_Name,ch.employee_ID,ch.maincomplain,ch.firstsymptom_date,ch.cons_hist_Date,ch.consultation_histry_ID,course_injury FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID LEFT JOIN tbl_hospital_course_injuries ci ON ci.hosp_course_injury_ID= ch.course_of_injuries WHERE ch.consultation_ID='" . $_GET['consultation_ID'] . "' AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));


if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sponsoDetails = '';
if (strtolower($Guarantor_Name) != 'cash') {
    $sponsoDetails = ' ';
}



                        


                //   die("SELECT * FROM tbl_histological_examination WHERE Biopsy_ID = '$Biopsy_ID'");
                $select_biopsy = mysqli_query($conn, "SELECT * FROM tbl_histological_examination WHERE Biopsy_ID = '$Biopsy_ID'") or die(mysqli_error($conn));
                    while($rows = mysqli_fetch_array($select_biopsy)){
                        $autospy = $rows['autospy'];
                        $Priority2 = $rows['Priority'];
                        $birth_region = $rows['birth_region'];
                        $birth_village = $rows['birth_village'];
                        $birth_district = $rows['birth_district'];
                        $birth_year = $rows['birth_year'];
                        $resident_year = $rows['resident_year'];
                        $Doctor_collected = $rows['Employee_ID'];
                        $Requested_Datetime = $rows['Requested_Datetime'];
                        $New_Case = $rows['New_Case'];
                        $relevant_clinical_data = $rows['relevant_clinical_data'];
                        $Laboratory_Number = $rows['Laboratory_Number'];
                        $Site_Biopsy = $rows['Site_Biopsy'];
                        $Previous_Laboratory = $rows['Previous_Laboratory'];
                        $Duration_Condition = $rows['Duration_Condition'];
                        $Comments = $rows['Comments'];
                        $Referred_From = $rows['Referred_From'];
                        $Submitted_By = $rows['Submitted_By'];
                        
                        $Doctor_collected_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Doctor_collected'"))['Employee_Name'];
                        $Submitted_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Submitted_By'"))['Employee_Name'];

                        
                    }

                    $select_biopsy_data = mysqli_query($conn, "SELECT specimen_results_Employee_ID, result_ID, TimeCollected, time_received, Employee_ID_receive, sample_quality FROM tbl_specimen_results WHERE payment_item_ID='$Payment_Item_Cache_List_ID'");
                    while($data = mysqli_fetch_array($select_biopsy_data)){
                        $specimen_results_Employee_ID = $data['specimen_results_Employee_ID'];
                        $TimeCollected = $data['TimeCollected'];
                        $time_received = $data['time_received'];
                        $Employee_ID_receive = $data['Employee_ID_receive'];
                        $sample_quality = $data['sample_quality'];
                        $result_ID = $data['result_ID'];

                        
                        if($sample_quality == 'Suitable'){
                            $quality = 'Satisfactory';
                        }else{
                            $quality = 'Unsatisfactory';
                        }

                    $Employee_collected_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$specimen_results_Employee_ID'"))['Employee_Name'];
                    $Employee_received_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID_receive'"))['Employee_Name'];
                    

                }


$data = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$data .= '
        <table class="hv_table" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr style=" background: #dedede;">
                <th style="text-align: center; font-size: 16px;"colspan="6">PATIENT HISTOLOGICAL EXAMINATION REPORT</th>
            </tr>

            <tr>
                <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan="">' . $Patient_Name . '</td>
                <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan="">' . $Country . '</td>
                <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan="">' . $Region . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Registration #:</b></td><td>' . $Registration_ID . '</td><td style="text-align:right"><b>Phone #:</b></td><td style="">' . $Phone_Number . '</td>
                <td style="text-align:right"><b>Residence:</b></td><td style="">' . $District . ',&nbsp;'. $Ward .'</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td style="">' . date("j F, Y", strtotime($Date_Of_Birth)) . ' </td>
                <td style="width:10%;text-align:right" ><b>Tribe:</b></td><td colspan=""> ' . $Tribe . '</td>
                <td style="text-align:right"><b>Gender:</b></td><td style="">' . $Gender . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right" ><b>Insurance Details:</b></td><td colspan=""> ' . $Guarantor_Name . $sponsoDetails . '</td>
                <td style="width:10%;text-align:right" ><b>Relative Name:</b></td><td colspan=""> ' . $Emergence_Contact_Name . '</td>
                <td style="text-align:right"><b>Relative Number:</b></td><td style="">' . $Emergence_Contact_Number . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right" ><b>Requested & Collected By:</b></td><td colspan="2">' . ucfirst($Doctor_collected_name) . '</td>
                <td style="width:10%;text-align:right" ><b>Collected Date:</b></td><td colspan="2">' . $Requested_Datetime . '</td>
            </tr>
        </table>
        <table class="hv_table" border="0" style="border:none !important" width="100%" style="margin-left:2px">
        <tr style=" background: #dedede;">
            <th style="text-align: center; font-size: 16px;"colspan="4">SPECIMENT DETAILS</th>
        </tr>
        <tr>
            <td style="width:16%;text-align:right "><b>Laboratory No:</b></td><td colspan="">' . $Laboratory_Number . '</td>
            <td style="width:16%;text-align:right "><b>Code No:</b></td><td colspan="">' . $Biopsy_ID . '</td>
        </tr>
        <tr>
            <td style="width:16%;text-align:right "><b>Submitted By:</b></td><td colspan="">' . $Employee_collected_name . '</td>
            <td style="width:16%;text-align:right "><b>Date & Time:</b></td><td colspan="">' . $TimeCollected . '</td>
        </tr>
        <tr>
            <td style="width:16%;text-align:right"><b>Received By:</b></td><td>' . $Employee_received_name . '</td>
            <td style="width:16%;text-align:right "><b>Date & Time:</b></td><td colspan="">' . $time_received . '</td>
        </tr>
        <tr>
            <td style="width:16%;text-align:right"><b>Processed By:</b></td><td style="">' . $Submitted_Name . ' </td>
            <td style="width:16%;text-align:right" ><b>Time & Date:</b></td><td colspan=""> ' . $Time_Filled . '</td>
        </tr>
    </table>
    <table class="table" border="0" style="border:none !important" width="100%" style="margin-left:2px">
    <tr style=" background: #dedede;">
        <th style="text-align: center; font-size: 16px;"colspan="4">LABORATORY SECTION</th>
    </tr>
    <tr>
        <td style="width:16%;text-align:right "><b>New Case:</b></td><td colspan="">' . $New_Case . '</td>
        <td style="width:16%;text-align:right "><b>Referred From:</b></td><td colspan="">' . $Referred_From . '</td>
    </tr>
    <tr>
        <td style="width:16%;text-align:right "><b>Site of Biopsy:</b></td><td colspan="">' . $Site_Biopsy . '</td>
        <td style="width:16%;text-align:right "><b>Biopsy/Autospy:</b></td><td colspan="">' . $autospy . '</td>
    </tr>
    <tr>
        <td style="width:16%;text-align:right "><b>Duration of Condition:</b></td><td colspan="">' . $Duration_Condition . '</td>
        <td style="width:16%;text-align:right "><b>Previous Lab No:</b></td><td colspan="">' . $Previous_Laboratory . '</td>
    </tr>
    <tr>
        <td style="width:16%;text-align:right "><b>Relevant Clinical Data:</b></td><td colspan="3">' . $relevant_clinical_data . '</td>
    </tr>
    <tr>
        <td style="width:16%;text-align:right "><b>Specimen Quality:</b></td><td colspan="3">' . $quality . '</td>
    </tr>
    <tr>
        <td style="width:16%;text-align:right "><b>Comments:</b></td><td colspan="3">' . $Comments . '</td>
    </tr>

</table>
            ';



$data .= '</fieldset>';





// include("MPDF/mpdf.php");
// $mpdf = new mPDF('', 'A4');

// $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// // LOAD a stylesheet
// $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name_Print)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');

// $stylesheet = file_get_contents('patient_file.css');
// $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
// $mpdf->WriteHTML($data, 2);

// $mpdf->Output();


include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');
$mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name_Print)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output('echocardiogram Report.pdf','I');
