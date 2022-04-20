<?php

include("./includes/connection.php");

include("./includes/check_print_session.php");

include("./handlers/labor.php");

include("./handlers/patient.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$preview_first_stage = get_first_stage_($conn, $patient_id);

$htm = "<table width ='100%' class='nobordertable'>
		    <tr>
                <td style='text-align:center'>
                <img src='../branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
		    <tr><td style='text-align: center;'><b>PREVIOUS FIRST STATE OF LABOUR</b></td></tr>
            <tr><td style='text-align: center;'><b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';

for ($i = 0; $i < count($preview_first_stage); $i++) {
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>ADMISSION DATE </b></td>
                <td colspan='7' style='text-align: center; font-size: 10px;  color: #ffffff;'>" . $preview_first_stage[$i]['onset_labor'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>ADMITTED AT </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['admitted_at'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>MEMBRANE LIQUOR</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['membrane_liquor'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>RAPTURE DATE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['rapture_date'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>RAPTURE DUTATION</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['rapture_duration'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>ARM</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['arm'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>NO OF EXAMINATIONS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['no_of_examinations'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>ABNORMALITIES</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['abnormalities'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>INDUCTION OF LABOUR</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['induction_of_labor'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>INDUCTION OF LABOUR REASON</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['induction_of_labor_reason'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>DUTATION OF FIRST STAGE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['first_stage_duration'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>DRUGS GIVEN</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['drugs_given'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>REMARKS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_first_stage[$i]['remarks'] . "</td>
            </tr>";
}

$htm .= ' </table></center>';

include("../MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
