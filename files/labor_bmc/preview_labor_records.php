<?php

include("./includes/connection.php");

include("./includes/check_print_session.php");

include("./handlers/labor.php");

include("./handlers/patient.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$preview_labor_array = get_labor_record_($conn, $patient_id);

$htm = "<table width ='100%' class='nobordertable'>
		    <tr>
                <td style='text-align:center'>
                <img src='../branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
		    <tr><td style='text-align: center;'><b>PREVIOUS LABOUR RECORDS</b></td></tr>
            <tr><td style='text-align: center;'><b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';

for ($i = 0; $i < count($preview_labor_array); $i++) {
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>ADMISSION DATE </b></td>
                <td colspan='7' style='text-align: center; font-size: 10px;  color: #ffffff;'>" . $preview_labor_array[$i]['date_time'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>STATE OF ADMISSION </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['state_of_admission'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>ADMITTED BY</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['admitted_by'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>EXAMINAER</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['examinaer'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>INFORMED BY</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['informed_by'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>TEMPERATURE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['temperature'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>PULSE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['pulse'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>RESPIRATION</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['resp'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BLOOD PRESSURE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['bp'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>COLOUR</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['colour'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>SPECIFIC GRAVITY</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['specific_gravity'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>PH</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['ph'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>ALBUMIN</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['albumin'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>SUGAR</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['sugar'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BLOOD</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['blood'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>LEUCOCYTES</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['leucocytes'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>KETONES</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['ketones'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>CLINICAL APPEARANCE </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['clinical_appearance'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>VARICOSE VEINS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['varicose_veins'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>OEDEMA </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['oedema'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>MENTAL STATUS</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['mental_status'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>INSPECTION </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['inspection'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>EXPECTED MODE OF DELIVERY</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['expected_mode_of_delivery'] . "</td>
                <td colspan='2' style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>REMARKS </b></td>
                <td colspan='2' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_labor_array[$i]['remarks'] . "</td>
            </tr>";
}

$htm .= ' </table></center>';

include("../MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
