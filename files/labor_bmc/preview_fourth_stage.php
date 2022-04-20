<?php

include("./includes/connection.php");

include("./includes/check_print_session.php");

include("./handlers/labor.php");

include("./handlers/patient.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$preview_fourth_stage = get_fourth_stage_($conn, $patient_id);

$htm = "<table width ='100%' class='nobordertable'>
		    <tr>
                <td style='text-align:center'>
                <img src='../branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
		    <tr><td style='text-align: center;'><b>PREVIOUS FOURTH STATE OF LABOUR</b></td></tr>
            <tr><td style='text-align: center;'><b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';

for ($i = 0; $i < count($preview_fourth_stage); $i++) {
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>TEMPERATURE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_fourth_stage[$i]['temperature'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>PRESSURE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_fourth_stage[$i]['pr'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BLOOD PRESSURE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_fourth_stage[$i]['bp'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>FUNDAL HEIGHT</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_fourth_stage[$i]['fundal_height'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>STATE OF CERVIX</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_fourth_stage[$i]['state_of_cervix'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>STATE OF PERINIUM</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_fourth_stage[$i]['state_of_perinium'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BLOOD LOSS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_fourth_stage[$i]['blood_loss'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>DOCTOR'S RECOMMENDATIONS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_fourth_stage[$i]['recommendations'] . "</td>
            </tr>";
}

$htm .= ' </table></center>';

include("../MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
