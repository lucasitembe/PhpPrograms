<?php

include("./includes/connection.php");

include("./includes/check_print_session.php");

include("./handlers/labor.php");

include("./handlers/patient.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$preview_third_stage = get_third_stage_($conn, $patient_id);

$htm = "<table width ='100%' class='nobordertable'>
		    <tr>
                <td style='text-align:center'>
                <img src='../branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
		    <tr><td style='text-align: center;'><b>PREVIOUS THIRD STATE OF LABOUR</b></td></tr>
            <tr><td style='text-align: center;'><b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';

for ($i = 0; $i < count($preview_third_stage); $i++) {
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>DATE AND TIME </b></td>
                <td colspan='7' style='text-align: center; font-size: 10px;  color: #ffffff;'>" . $preview_third_stage[$i]['date_time'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>METHOD OF DELIVERY </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['placenta_method_of_delivery'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>DURATION</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['duration'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>PLACENTA WEIGHT</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['placenta_weight'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>STAGE OF PLACENTA</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['stage_of_placenta'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>COLOUR</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['colour'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>CORD</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['cord'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>MEMBRANES</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['membranes'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>DISPOSAL</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['disposal'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>STATE OF CERVIX</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['state_of_cervix'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>TEAR</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['tear'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>REPAIRED WITH SUTURES</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['repaired_with_sutures'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>TOTAL BLOOD LOSS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['total_blood_loss'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>TEMPERATURE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['temperature'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>PULSE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['pulse'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>RESP</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['resp'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BLOOD PRESSURE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['bp'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>LOCHIA</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['lochia'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>STATE OF UTERUS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['state_of_uterus'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>REMARKS</b></td>
                <td colspan = '3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_third_stage[$i]['remarks'] . "</td>
            </tr>";
}

$htm .= ' </table></center>';

include("../MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
