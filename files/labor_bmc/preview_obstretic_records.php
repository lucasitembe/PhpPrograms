<?php

include("./includes/connection.php");

include("./includes/check_print_session.php");

include("./handlers/labor.php");

include("./handlers/patient.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$preview_obstretic_array = get_obstretic_record_($conn, $patient_id);

$htm = "<table width ='100%' class='nobordertable'>
		    <tr>
                <td style='text-align:center'>
                <img src='../branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
		    <tr><td style='text-align: center;'><b>PREVIOUS OBSTRETIC RECORDS</b></td></tr>
            <tr><td style='text-align: center;'><b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';

for ($i = 0; $i < count($preview_obstretic_array); $i++) {
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>ADMISSION DATE </b></td>
                <td colspan='7' style='text-align: center; font-size: 10px;  color: #ffffff;'>" . $preview_obstretic_array[$i]['date_of_admission'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>PAEDITRICIAN </b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['paeditrician'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>ANAESTHETIST</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['anaesthetist'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>SURGEON</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['surgeon'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>PHYSICIAN</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['physician'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>LMP DURATION</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['lmp_duration'] . "<span> week(s)</span></td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>EDD DURATION</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['edd_duration'] . "<span> week(s)</span></td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>GA DURATION</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['ga_duration'] . "<span> week(s)</span></td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>PARA</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['para'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>GRAVIDA</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['gravida'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>DATE OF ANC</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['date_of_anc'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>DRUG ALLERGIES</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['drug_allergies'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>DATE OF DISCHARGE</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['date_of_discharge'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>BLOOD GROUP</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['blood_group'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>WEIGHT</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['weight'] . "<span>Kg</span></td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>HEIGHT</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['height'] . "<span>CM</span></td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>FAMILY HISTORY</b></td>
                <td style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['family_history'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td colspan='2' style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>MEDICAL SURGICAL HISTORY </b></td>
                <td colspan='2' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['medical_surgical_history'] . "</td>
                <td colspan='2' style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>REASON FOR ADMISSION</b></td>
                <td colspan='2' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_obstretic_array[$i]['reason_for_admission'] . "</td>
            </tr>";
}

$htm .= ' </table></center>';

include("../MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
