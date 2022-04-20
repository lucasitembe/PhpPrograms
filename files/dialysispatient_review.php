<?php 

session_start();
include("./includes/connection.php");
$data = '';
if ($_GET['Registration_ID'] != '') {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

if ($_GET['AtendaceDate'] != '') {
    $AtendaceDate = $_GET['AtendaceDate'];
    $filterByDate = "AND Attendance_Date='$AtendaceDate'";
} else {
    $AtendaceDate = '';
}

$getPatientDetails = "SELECT * FROM `tbl_patient_registration`,"
        . "tbl_sponsor WHERE  tbl_sponsor.Sponsor_ID = tbl_patient_registration.Sponsor_ID AND"
        . " tbl_patient_registration.`Registration_ID`='$Registration_ID'";

$result = mysqli_query($conn,$getPatientDetails) or die(mysqli_error($conn));

$doctorName = '';
//echo mysqli_num_rows($result);exit;
if (mysqli_num_rows($result) == 0) {

} else {

    $row = mysqli_fetch_array($result);

    $patientName = $row['Patient_Name'];
    $Registration_ID = $row['Registration_ID'];
    $Gender = $row['Gender'];
    $age = $row['age'];
    $Region = $row['Region'];
    $District = $row['District'];
    $Guarantor_Name = $row['Guarantor_Name'];
    
    $data = "<table width ='100%' height = '30px'  border='0'   class='nobordertable'>
            <tr>
                <td>
                <img src='./branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td style='text-align: center;'><b>PATIENT DIALYSIS REPORT</b></td>
            </tr></table><br/>";

    $data .= '<table width="100%"  border="0"   class="nobordertable">
                <tr>
                    <td style="text-align: right;" width="10%"><b>Name:</b></td>
                    <td width="30%">' . $patientName . '</td>
                    <td style="text-align: right;" width="20%"><b>Gender:</b></td>
                    <td>' . $Gender . '</td>
                    <td style="text-align: right;"><b>Reg #:</b></td>
                    <td  width="15%">' . $Registration_ID . '</td>
                <tr>
                    <td style="text-align: right;"><b>Sponsor:</b></td>
                    <td>' . $Guarantor_Name . '</td>
                    <td style="text-align: right;"><b>Age:</b></td>
                    <td>' . $age . ' years</td>
                    <td style="text-align: right;"><b>Date:</b></td>
                    <td colspan="3">' . date('d, M Y') . '</td>
                </tr>
                <tr>
                    <td style="text-align: right;"><b>Attendace Date:</b></td>
                    <td>' . $AtendaceDate . '</td>
                </tr>
            </table><br/>';
            
    
         $qr = "SELECT `dialysis_details_ID`, `Patient_reg`, `Attendance_Date`,"
                 . " `bpPrevious_Post_sit`, `bpPrevious_Post_stand`, `Weight_Previous_Post_sit`,"
                 . " `Weight_Previous_Post_stand`, `Weight_Gain`, `Time_On`, `bpPre_sit`, "
                 . "`bpPre_stand`, `Weight_Pre_sit`, `Weight_Pre_stand`, `Weight_removal`,"
                 . " `Time_Off`, `bpPost_sit`, `bpPost_stand`, `Weight_Post_sit`,"
                 . " `Weight_Post_stand`, `Area`, `Station`, `Machine`, `Hosp` "
                 . "FROM `tbl_dialysis_details` WHERE `Patient_reg`= '$Registration_ID' $filterByDate";
         
        $select_qr = mysqli_query($conn,$qr) or die(mysqli_error($conn));

        if (mysqli_num_rows($select_qr) > 0) {
            $data .= " <div width='100%' id='departments_comments'>
                        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                           <b>Vitals</b>
                        </div>";

            $data .= ' <table  border="1" width="100%" style="margin-left:2px">
                    <tr>
                        <td style="width:3%;"><b>SN</b></td>
                        <td><b>Vitals</b></b></td>
                        <td colspan="4"><b>Previous Post</b></td>
                        <td colspan="4"><b>Pre</b></td>
                        <td colspan="4"><b>Post</b></td>
                    </tr>';

            // die();

            $sn = 1;
            while ($patient = mysqli_fetch_assoc($select_qr)) {
                $bpPrevious_Post_sit = $patient['bpPrevious_Post_sit'];
                $bpPrevious_Post_stand = $patient['bpPrevious_Post_stand'];
                $bpPre_sit= $patient['bpPre_sit'];
                $bpPre_stand = $patient['bpPre_stand'];
                $bpPost_sit = $patient['bpPost_sit'];
                $bpPost_stand= $patient['bpPost_stand'];
                
                $Weight_Previous_Post_sit = $patient['Weight_Previous_Post_sit'];
                $Weight_Previous_Post_stand = $patient['Weight_Previous_Post_stand'];
                $Weight_Pre_sit= $patient['Weight_Pre_sit'];
                $Weight_Pre_stand = $patient['Weight_Pre_stand'];
                $Weight_Post_sit = $patient['Weight_Post_sit'];
                $Weight_Post_stand= $patient['Weight_Post_stand'];
                
                $Weight_Gain = $patient['Weight_Gain'];
                $Weight_removal = $patient['Weight_removal'];
                $Station = $patient['Station'];
                $Area = $patient['Area'];
                $Machine = $patient['Machine'];
                
                $Time_On = $patient['Time_On'];
                $Time_Off = $patient['Time_Off'];
                $Hosp = $patient['Hosp'];

                $data .= '<tr>'
                        . '<td>'.$sn.'</td>'
                        . '<td> B/P </td><td>Sit</td><td>'.$bpPrevious_Post_sit.'</td><td>Stand</td><td>'.$bpPrevious_Post_stand.'</td>'
                        . '<td>Sit</td><td>'.$bpPre_sit.'</td><td>Stand</td><td>'.$bpPre_stand.'</td>'
                        . '<td>Sit</td><td>'.$bpPost_sit.'</td><td>Stand</td><td>'.$bpPost_stand.'</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td></td>'
                        . '<td>Weight</td><td></td><td>'.$Weight_Previous_Post_sit.'</td><td></td><td>'.$Weight_Previous_Post_stand.'</td>'
                        . '<td></td><td>'.$Weight_Pre_sit.'</td><td></td><td>'.$Weight_Pre_stand.'</td>'
                        . '<td></td><td>'.$Weight_Post_sit.'</td><td></td><td>'.$Weight_Post_stand.'</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td></td>'
                        . '<td>EDW</td><td>Weight-Gain</td><td>'.$Weight_Gain.'</td><td></td><td></td>'
                        . '<td>Weight-removal</td><td>'.$Weight_removal.'</td>'
                        . '<td> Station#</td><td>'.$Station.'</td><td>Area#</td><td>'.$Area.'</td>'
                        . '<td> Machine#</td><td>'.$Machine.'</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td></td>'
                        . '<td>Time</td><td>Time-On</td><td>'.$Time_On.'</td><td></td><td></td>'
                        . '<td>Time-Off</td><td>'.$Time_Off.'</td><td></td><td></td>'
                        . '<td></td><td></td><td></td><td></td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td></td>'
                        . '<td>Hosp/ER/OP Procedure since last treatment?</td><td colspan="12">'.$Hosp.'</td>'
                        . '</tr>';
                $sn++;
                        
            }
            $data .= '</table></div>';
        }
        
        
     $qr = "SELECT tbl_employee.Employee_Name,tbl_dialysis_details.Notes,tbl_dialysis_details.Attendance_Date
        FROM `tbl_dialysis_details`,tbl_employee WHERE tbl_dialysis_details.Doctor_ID = tbl_employee.Employee_ID
        AND tbl_dialysis_details.Patient_reg = '$Registration_ID' $filterByDate";
        $select_qr = mysqli_query($conn,$qr) or die(mysqli_error($conn));

        if (mysqli_num_rows($select_qr) > 0) {
            $data .= " <div width='100%' id='departments_comments'>
                        <div style='padding:5px; width:99.3%;font-size:larger;  background:#ccc;text-align:left  '>
                           <b>Doctor`s Notes</b>
                        </div>";

            $data .= ' <table  border="1" width="100%" style="margin-left:2px">
                    <tr>
                        <td style="width:3%;"><b>SN</b></td>
                        <td><b>Docotor Name</b></b></td>
                        <td><b>Notes</b></td>
                        <td><b>Served Date</b></td>
                    </tr>';

            // die();

            $sn = 1;
            while ($patient = mysqli_fetch_assoc($select_qr)) {
                $doctor_name = $patient['Employee_Name'];
                $Doctor_Comment = $patient['Notes'];
                $Date = $patient['Attendance_Date'];
                // if (strtolower($patient['Status'] != 'served')) {
                $data .= '<tr>';
                $data .= '<td>' . $sn . '</td>';
                $data .= '<td>' . $doctor_name . '</td>';
                $data .= '<td>' . $Doctor_Comment . '</td>';
                $data .= '<td>' . $Date . '</td>';
                $data .= '</tr>';
                $sn++;
                // }
            }
            $data .= '</table></div>';
        }
}

include("./MPDF/mpdf.php");


$mpdf = new mPDF('s', 'Letter');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
?>
