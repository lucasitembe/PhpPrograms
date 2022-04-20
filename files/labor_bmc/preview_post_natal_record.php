<?php

include("./includes/connection.php");

include("./includes/check_print_session.php");

include("./handlers/labor.php");

include("./handlers/patient.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$preview_post_natal_record = get_post_natal_record_($conn, $patient_id);

$htm = "<table width ='100%' class='nobordertable'>
		    <tr>
                <td style='text-align:center'>
                <img src='../branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
		    <tr><td style='text-align: center;'><b>PREVIOUS POST NATAL RECORDS</b></td></tr>
            <tr><td style='text-align: center;'><b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';

for ($i = 0; $i < count($preview_post_natal_record); $i++) {
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td colspan='8' style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>DATE OF DISCHARGE</b></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>DURATION IN HOSPITAL </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['hospital_duration'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>DATE OF NEXT APPOINTMENT</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['next_appointment'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>PEURPERIUM</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['peurperium'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>UTERUS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['uterus'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>LOCHIA</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['lochia'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>ABDOMINAL SCARS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['abdominal_scars'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>EPISIOTOMY</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['episiotomy'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BREASTS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['breasts'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td colspan='8' style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>POST NATAL EXAMINATIONS BY MIDWIFE AFTER 6 WEEKS</b></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>GENERAL CONDITION </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['general_condition'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>ANAEMIA</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['anaemia'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BREASTS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['breasts2'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>CERVIX</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['cervix'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>VAGINA</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['vagina'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>EPISIOTOMY</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['episiotomy2'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>STRESS INCONTINENCE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['stress_incontinence'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>ANUS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['anus'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>TENDERNESS IN THE GROIN OF CALF MUSCLES</b></td>
                <td colspan = '3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['tenderness'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>REMARKS</b></td>
                <td colspan = '3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['remarks'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td colspan='8' style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>CLINICAL OBSERVATIONS</b></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>TEMPERATURE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['temperature'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>PULSE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['pulse'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BP</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['bp'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>HOW IS HER BABY?</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['baby_condition'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>GENERAL REMARKS ON THE MOTHER</b></td>
                <td colspan = '3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['mother_remarks'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>MIDWIFE'S NAME</b></td>
                <td colspan = '3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_post_natal_record[$i]['midwife_name2'] . "</td>
            </tr>";
}

$htm .= ' </table></center>';

include("../MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
