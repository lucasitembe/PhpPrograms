<?php

include("./includes/connection.php");

include("./includes/check_print_session.php");

include("./handlers/labor.php");

include("./handlers/patient.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$preview_baby_record = get_baby_record_($conn, $patient_id);

$htm = "<table width ='100%' class='nobordertable'>
		    <tr>
                <td style='text-align:center'>
                <img src='../branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
		    <tr><td style='text-align: center;'><b>PREVIOUS BABY RECORDS</b></td></tr>
            <tr><td style='text-align: center;'><b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';

for ($i = 0; $i < count($preview_baby_record); $i++) {
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>STATE OF BIRTH </b></td>
                <td colspan='7' style='text-align: center; font-size: 10px;  color: #ffffff;'>" . $preview_baby_record[$i]['state_of_birth'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>SEX </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['sex'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>APGAR</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['apgar'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BIRTH WEIGHT</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['birth_weight'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>LENGTH</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['length'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>HEAD CIRCUMFERENCE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['head_circumference'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>ABNORMALITIES</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['abnormalities'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>DRUGS GIVEN</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['drugs'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>PAEDITRICIAN PRESENT</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['paediatrician'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>TRANSFERRED TO</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['transferred_to'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>REASON</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['reason'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>TRANSFERRED BY</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['transferred_by'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>TEMPERATURE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_baby_record[$i]['temperature'] . "</td>
            </tr>";
}

$htm .= ' </table></center>';

include("../MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
