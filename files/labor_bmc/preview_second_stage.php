<?php

include("./includes/connection.php");

include("./includes/check_print_session.php");

include("./handlers/labor.php");

include("./handlers/patient.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$preview_second_stage = get_second_stage_($conn, $patient_id);

$htm = "<table width ='100%' class='nobordertable'>
		    <tr>
                <td style='text-align:center'>
                <img src='../branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
		    <tr><td style='text-align: center;'><b>PREVIOUS SECOND STATE OF LABOUR</b></td></tr>
            <tr><td style='text-align: center;'><b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';

for ($i = 0; $i < count($preview_second_stage); $i++) {
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>DATE AND TIME OF BIRTH </b></td>
                <td colspan='7' style='text-align: center; font-size: 10px;  color: #ffffff;'>" . $preview_second_stage[$i]['date_time'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>TIME BEGAN </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_second_stage[$i]['time_began'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>DURATION</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_second_stage[$i]['duration'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>MODE OF DELIVERY</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_second_stage[$i]['mode_of_delivery'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>DRUGS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_second_stage[$i]['drugs'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>REMARKS</b></td>
                <td colspan='7' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_second_stage[$i]['remarks'] . "</td>
            </tr>";
}

$htm .= ' </table></center>';

include("../MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
