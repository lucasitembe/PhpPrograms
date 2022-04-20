<?php

include("./includes/connection.php");

include("./get_audiology_data.php");

if (isset($_GET['registration_id'])) {
    $registration_id = $_GET['registration_id'];
}

if (isset($_GET['paymant_item_cache_list_id'])) {
    $paymant_item_cache_list_id = $_GET['paymant_item_cache_list_id'];
}

if (isset($_GET['registration_id']) && $_GET['registration_id'] != 0) {
    $select_patien_details = mysqli_query($conn, "SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
              FROM
                  tbl_patient_registration pr,
                  tbl_sponsor sp
              WHERE
                  pr.Registration_ID = '" . $registration_id . "' AND
                  sp.Sponsor_ID = pr.Sponsor_ID
                  ") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $registration_id = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
}

$age = date_diff(date_create($DOB), date_create('today'))->y;

$preview_audiogram = get_audiogram_data($conn, $registration_id, $paymant_item_cache_list_id);

$htm = "<table width ='100%' class='nobordertable'>
		    <tr>
                <td style='text-align:center'>
                </td>
            </tr>
		    <tr><td style='text-align: center;'><b>PREVIEW AUDIOGRAM RECORDS</b></td></tr>
            <tr><td style='text-align: center;'><b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';

for ($i = 0; $i < count($preview_audiogram); $i++) {
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td colspan='8' style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>AUDIOLOGY/HEARING EVALUATION</b></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>DATE </b></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['date'] . "</td>
                <td style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>AUDIOMETRIST</b></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['employee_name'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>EQUIPMENT</b></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['equipment'] . "</td>
                <td style='text-align: center; font-size: 10px;background-color:#bdb5ac;padding: 4px;'><b>RESULT/RECOMMENDATION</b></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['recommendation'] . "</td>
            </tr>";
    $htm .= "<tr>
                <td colspan='8' style='text-align: center; font-size: 10px;padding: 4px;'>
                    <div id='audiogram_chart' style='width: 98%; height: 500px;'></div>
                </td>
            </tr>";
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td colspan='8' style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>OTOSCOPY</b></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td colspan='2' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>RIGHT OTOSCOPY </b></td>
                <td colspan='6' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['right_otoscopy'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td colspan='2' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>LEFT OTOSCOPY </b></td>
                <td colspan='6' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['left_otoscopy'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center; background-color:#037db0;'>
                <td colspan='8' style='text-align: center; font-size: 10px; color: #ffffff; padding: 4px;'><b>TYMPANOMETRY</b></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td colspan='2' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b></b></td>
                <td colspan='3' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>R</b></td>
                <td colspan='3' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>L</b></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td colspan='2' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>JERGER TYPE</b></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['jerger_type_right'] . "</td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['jerger_type_left'] . "</td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td colspan='2' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>ADMITTANCE</b></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['admittance_right'] . "<span> ml</span></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['admittance_left'] . "<span> ml</span></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td colspan='2' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>PRESSURE</b></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['pressure_right'] . "<span> daPa</span></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['pressure_left'] . "<span> daPa</span></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td colspan='2' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>WIDTH</b></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['width_right'] . "<span> daPa</span></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['width_left'] . "<span> daPa</span></td>
            </tr>";
    $htm .= "<tr style='text-align: center;'>
                <td colspan='2' style='text-align: center; font-size: 10px; background-color:#bdb5ac;padding: 4px;'><b>VOLUME</b></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['volume_right'] . "<span> cm<sup>3</sup></span></td>
                <td colspan='3' style='text-align: center; font-size: 10px;padding: 4px;'>" . $preview_audiogram[$i]['volume_left'] . "<span> cm<sup>3</sup></span></td>
            </tr>";
}

$htm .= ' </table></center>';


echo $htm;
include("./MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4', 0, '', 15, 15, 20, 40, 15, 35, 'P');
$mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
