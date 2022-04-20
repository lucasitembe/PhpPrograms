<?php

include("./includes/connection.php");
@session_start();

 $Employee_Name_Print = $_SESSION['userinfo']['Employee_Name'];

 if (isset($_GET['duty_ID'])) {
    $duty_ID = $_GET['duty_ID'];
}
$fromDate = $_GET['Date_From'];
$toDate = $_GET['Date_To'];

//get employee name
if (isset($_SESSION['userinfo'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_Name = 'Unknown Officer';
    $Employee_ID = 0;
}

$Sub_Department_Name = $_SESSION['Admission'];

$sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
while($date = mysqli_fetch_array($sql_date_time)){
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time,0,11);
$Date_From = $Filter_Value.' 00:00';
$Date_To = $Current_Date_Time;


if (isset($_SESSION['Ward_Info'])) {
    $Current_Store_ID = $_SESSION['Ward_Info']['Hospital_Ward_ID'];
    $Current_Store_Name = $_SESSION['Ward_Info']['Hospital_Ward_Name'];
}
if(!empty($fromDate) && !(empty($toDate))){
    $Date_From = $fromDate;
    $Date_To = $toDate;
}else{
    $Today_Date = mysqli_query($conn,"select now() as today");
        while ($row = mysqli_fetch_array($Today_Date)) {
            $original_Date = $row['today'];
            $new_Date = date("Y-m-d", strtotime($original_Date));
            $Today = $new_Date;
        }
}

$qr = "SELECT * FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
$ward_results = mysqli_query($conn,$qr);
while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
$Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
$Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
$down_Hospital_Ward_Name = $Hospital_Ward_Name;
}

$data = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";

$get_details = mysqli_query($conn,"SELECT * from tbl_nurse_duties WHERE duty_handled BETWEEN $Date_From AND $Date_To AND duty_ward = '$Hospital_Ward_ID' AND Process_Status = 'Submitted' ORDER BY Ward_Type, duty_handled ASC") or die(mysqli_error($conn));
$no = mysqli_num_rows($get_details);
    if ($no > 0) {
    while ($data2 = mysqli_fetch_assoc($get_details)) {
        $duty_ID = $data2['duty_ID'];
        $Refugees=$data2['Refugees'];
        $current_nurse=$data2['current_nurse'];
        $duty_nurse=$data2['duty_nurse'];
        $duty_ward=$data2['duty_ward'];
        $Doctor_round=$data2['Doctor_round'];
        $select_round=$data2['select_round'];
        $current_inpatient=$data2['current_inpatient'];
        $received_inpatient=$data2['received_inpatient'];
        $discharged_inpatient=$data2['discharged_inpatient'];
        $death_inpatient=$data2['death_inpatient'];
        $Abscondees=$data2['Abscondees'];
        $debt_inpatient=$data2['debt_inpatient'];
        $transferIn=$data2['transferIn'];
        $transferOut=$data2['transferOut'];
        $lodgers=$data2['lodgers'];
        $major_round=$data2['major_round'];
        $nurse_notes=$data2['nurse_notes'];
        $duty_handled=$data2['Saved_Date_Time'];
        $serious_inpatient=$data2['serious_inpatient'];
        $serious_inpatient=$data2['serious_inpatient'];
        $Ward_Type = $data2['Ward_Type'];
        $Refugees = $data2['Refugees'];
        $taarifa ='';

        $employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$duty_nurse'"))['Employee_Name'];
        $ward = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$duty_ward'"))['Hospital_Ward_Name'];
        $doctor = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Doctor_round'"))['Employee_Name'];
        if($current_nurse > 0){
            $current_nurse = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$current_nurse'"))['Employee_Name'];
        }else{
            $current_nurse = $current_nurse;
        }

        $select_notes = mysqli_query($conn, "SELECT Nurse_Notes, Updated_Date_Time, Employee_Name FROM tbl_employee em, tbl_nurse_duty_nurse nd WHERE nd.duty_ID = '$duty_ID' AND em.Employee_ID = nd.Employee_ID ORDER BY Notes_ID ASC");
        if(mysqli_num_rows($select_notes)>0){
            while($ns = mysqli_fetch_assoc($select_notes)){
                $Employee_Name_Nurse = $ns['Employee_Name'];
                $Nurse_Notes = $ns['Nurse_Notes'];
                $Updated_Date_Time = $ns['Updated_Date_Time'];
    
                $details = $Nurse_Notes."<br><br>
            Reported By: <b>".ucfirst($Employee_Name_Nurse)."</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Time: <b>".$Updated_Date_Time."</b><br>
            ----------------------------------------------------------------------------------------------------------------------------------------------------------<br>";
    
            $taarifa .= $details;
    
            }   
        }else{
            $taarifa = $nurse_notes;
        }
        if($duty_handled == ''){
            $duty_handled = "<b>NOT YET SUBMITTED</b>";
        }else{
            $duty_handled = date('j F, Y H:i:s', strtotime($duty_handled));
        }

    $data .= '
        <div style="padding:5px; width:99%;font-size:12px;border:1px solid #000;  background:#ccc;text-align:center  " id="outpatient">
            <b align="center">NURSE HANDLING DUTY REPORT FOR '.strtoupper($Hospital_Ward_Name).' - '.strtoupper($Ward_Type).' - '.strtoupper($select_round).'</b>
        </div>
        <div style="border:1px solid #000">
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
                <tr>
                    <td style="width:10%;text-align:right; font-size: 10px;"><b>Nurse On Duty</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($current_nurse) . '</td>
                    <td style="width:10%;text-align:right; font-size: 10px;"><b>Nurse Taking Over</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($employee). '</td>
                    <td style="width:10%;text-align:right; font-size: 10px;"><b>Time Submitted</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . $Hospital_Ward_Name . '</td>
                </tr>
                <tr>
                    <td style="width:10%;text-align:right; font-size: 10px;"><b>Shift</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($select_round) . '</td>
                    <td style="width:10%;text-align:right; font-size: 10px;"><b>Time Submitted</b></td style="font-size: 10px;"><td colspan="3" style="font-size: 10px;">' .$duty_handled. '</td>
                </tr>
            </table>
            <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr style="background: #dedede;">
                <th colspan="6" style="text-align: center; font-size: 12px;">DUTY&#39;S DETAILS</th>
            </tr>
            <tr>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Total Current Patients</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($current_inpatient) . '</td>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Total Admitted Patients</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($received_inpatient). '</td>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Total Discharged Patients</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . $discharged_inpatient . '</td>
            </tr>
            <tr>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Refugees</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($Refugees) . '</td>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Admitted Not Paid</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($debt_inpatient). '</td>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Total Deaths</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . $death_inpatient . '</td>
            </tr>
            <tr>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Total Serious Patients</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($serious_inpatient) . '</td>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Lodgers</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($lodgers). '</td>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Abscondees</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . $Abscondees . '</td>
            </tr>
            <tr>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Total Transfer INs</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($transferIn) . '</td>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Total Transfer OUTs</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . ucfirst($transferOut). '</td>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Ward Round</b></td style="font-size: 10px;"><td colspan="" style="font-size: 10px;">' . $major_round . '</td>
            </tr>
            <tr>
                <td style="width:24%;text-align:right; font-size: 10px;"><b>Refugees</b></td style="font-size: 10px;"><td colspan="5" style="font-size: 10px;">' . ucfirst($Refugees) . '</td>
            </tr>
            <tr>
            <td style="width:24%;text-align:right; font-size: 10px;"><b>Nurse Notes</b></td style="font-size: 10px;"><td colspan="5" style="font-size: 10px;">' . $taarifa . '</td>
        </tr>
        </table>
        </div><br/>';

    }
}

include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name_Print)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');

$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output();