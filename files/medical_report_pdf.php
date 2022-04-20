<?php

include("./includes/connection.php");
session_start();

$filter = '';
$query = '';
$data = array();
$v_column = array();
$h_column = array();
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
$lowerage = $_GET['lowerage'];
$higherage = $_GET['higherage'];
$clinics = $_GET['clinics'];
$y_axis = $_GET['y_axis'];
$visittype = $_GET['visittype'];
$wards = $_GET['wards'];
$ipdstatus = $_GET['ipdstatus'];
$report = $_GET['report'];
@$agetype=$_GET['agetype'];
$District=$_GET['District'];
$region=$_GET['region'];
$html = '';
$clinicfilter = '';
$visitfilter = '';
$ipdstatusfilter = '';
$Address='';
if($District != ''){
    $Address .= " AND  pr.District='$District' ";
}
if(($lowerage != '') && ($higherage != '' )){
    $lowerage = $lowerage ;
    $higherage = $higherage ;
   // $filter .= " AND datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth)) between '$lowerage' and '$higherage'";
   
    $filter .=" AND pr.Region='$region' $Address AND TIMESTAMPDIFF($agetype, pr.Date_Of_Birth,CURDATE()) BETWEEN '".$lowerage."' AND '".$higherage."'";
}


if (!empty($y_axis)) {

    if ($y_axis == 'Medication') {
        if ($clinics != '') {
            $clinicfilter .= " AND itl.Clinic_ID = '$clinics'";
        }

        $query = "SELECT 
                    i.Product_Name,
                    pr.Gender,
                    (datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth))/365.25) as age,
                    pc.Payment_Date_And_Time
                FROM
                    tbl_item_list_cache AS itl,
                    tbl_items i,
                    tbl_payment_cache pc,
                    tbl_patient_registration pr
                WHERE
                    itl.Item_ID = i.Item_ID AND 
                    itl.Payment_Cache_ID = pc.Payment_Cache_ID AND
                    pc.Registration_ID = pr.Registration_ID AND 
                    itl.Check_In_Type = 'Pharmacy' and 
                    date(pc.Payment_Date_And_Time) between '$start_date' and '$end_date' $filter $clinicfilter
                    order by i.Product_Name";
    } else if ($y_axis == 'Clinic') {
        if ($clinics != '') {
            $clinicfilter .= " AND ptl.Clinic_ID = '$clinics'";
        }

        if ($visittype == "new") {
            $visitfilter .= " AND ck.Type_Of_Check_In = 'Afresh' and ck.Referral_Status = 'no'";
        } else if ($visittype == "return") {
            $visitfilter .= " AND ck.Type_Of_Check_In = 'Continuous' and ck.Referral_Status = 'no'";
        } else if ($visittype == "referral") {
            $visitfilter .= " AND ck.Referral_Status = 'yes'";
        } else {
            $visitfilter .= "AND ck.Type_Of_Check_In not in ('PATIENT FROM OUTSIDE','') ";
        }
        $query = "SELECT 
                    c.Clinic_Name as Product_Name,
                    c.Clinic_ID,
                    pr.Gender,
                    (datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth))/365.25) as age
                FROM
                    tbl_patient_payment_item_list ptl,
                    tbl_patient_payments pp,
                    tbl_patient_registration pr,
                    tbl_clinic c,
                     tbl_check_in ck
                WHERE
                    ck.Check_In_ID = pp.Check_In_ID and 
                    ptl.Patient_Payment_ID = pp.Patient_Payment_ID and 
                    pp.Registration_ID = pr.Registration_ID and 
                    c.Clinic_ID = ptl.Clinic_ID and ptl.Check_In_Type = 'Doctor Room' and
                    DATE(pp.Payment_Date_And_Time) between '$start_date' AND '$end_date' $filter $visitfilter $clinicfilter
                    group by ptl.Patient_Payment_ID order by c.Clinic_Name";
                    // die($query);
    } else if ($y_axis == 'Diagnosis') {
        if ($clinics != '') {
            $clinicfilter .= " AND ptl.Clinic_ID = '$clinics'";
        }
        if ($visittype == "new") {
            $visitfilter .= " AND ck.Type_Of_Check_In = 'Afresh' and ck.Referral_Status = 'no'";
        } else if ($visittype == "return") {
            $visitfilter .= " AND ck.Type_Of_Check_In = 'Continuous' and ck.Referral_Status = 'no'";
        } else if ($visittype == "referral") {
            $visitfilter .= " AND ck.Referral_Status = 'yes'";
        }

        $query = "SELECT 
                    d.disease_name as Product_Name,
                    pr.Gender,
                    (datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth))/365.25) as age
                FROM
                    tbl_disease_consultation dc,
                    tbl_consultation cs,
                    tbl_disease d,
                    tbl_patient_payment_item_list ptl,
                    tbl_patient_payments pp,
                    tbl_patient_registration pr,
                    tbl_clinic c,
                     tbl_check_in ck
                WHERE
                    cs.Patient_Payment_Item_List_ID = ptl.Patient_Payment_Item_List_ID and
                    dc.consultation_ID = cs.consultation_ID and
                    d.disease_ID = dc.disease_ID and
                    ck.Check_In_ID = pp.Check_In_ID and 
                    ptl.Patient_Payment_ID = pp.Patient_Payment_ID and 
                    pp.Registration_ID = pr.Registration_ID and 
                    c.Clinic_ID = ptl.Clinic_ID and ptl.Check_In_Type = 'Doctor Room' and
                    DATE(pp.Payment_Date_And_Time) between '$start_date' AND '$end_date' $filter $visitfilter $clinicfilter
                    group by ptl.Patient_Payment_ID order by d.disease_name";
    } else if ($y_axis == 'Ward') {
        if ($wards != '') {
            $wardfilter .= " AND ad.Hospital_Ward_ID= '$wards'";
        }

        if ($ipdstatus == 'admitted') {
            $ipdstatusfilter .= " AND date(ad.Admission_Date_Time) BETWEEN '$start_date' AND '$end_date'";
        }
        if ($ipdstatus == 'normal') {
            $ipdstatusfilter .= " AND date(ad.Discharge_Date_Time) BETWEEN '$start_date' AND '$end_date' and ad.Discharge_Reason_ID = (select Discharge_Reason_ID from tbl_discharge_reason where Discharge_Reason = 'Normal') ";
        }
        if ($ipdstatus == 'Absconded') {
            $ipdstatusfilter .= " AND date(ad.Discharge_Date_Time) BETWEEN '$start_date' AND '$end_date' and ad.Discharge_Reason_ID = (select Discharge_Reason_ID from tbl_discharge_reason where Discharge_Reason = 'Absconded') ";
        }
        if ($ipdstatus == 'Refferal') {
            $ipdstatusfilter .= " AND date(ad.Discharge_Date_Time) BETWEEN '$start_date' AND '$end_date' and ad.Discharge_Reason_ID = (select Discharge_Reason_ID from tbl_discharge_reason where Discharge_Reason = 'Refferal') ";
        }
        if ($ipdstatus == 'Death') {
            $ipdstatusfilter .= " AND date(ad.Discharge_Date_Time) BETWEEN '$start_date' AND '$end_date' and ad.Discharge_Reason_ID = (select Discharge_Reason_ID from tbl_discharge_reason where Discharge_Reason = 'Death') ";
        }

        $query = "SELECT 
                        hw.Hospital_Ward_Name as Product_Name,
                        pr.Gender,
                        (datediff(CURRENT_TIMESTAMP(),(pr.Date_Of_Birth))/365.25) as age
                    FROM
                        tbl_admission ad,
                        tbl_hospital_ward hw,
                        tbl_patient_registration pr
                    where 
                        pr.Registration_ID = ad.Registration_ID and
                        ad.Hospital_Ward_ID = hw.Hospital_Ward_ID $ipdstatusfilter $filter $wardfilter"
                . "order by hw.Hospital_Ward_Name";
    }
}

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
if (mysqli_num_rows($result) > 0) {
    while ($rows = mysqli_fetch_assoc($result)) {
        $female = 0;
        $male = 0;
        $name = $rows['Product_Name'];
        $Gender = $rows['Gender'];
        if (strtolower($Gender) == "female") {
            $female++;
        } else if (strtolower($Gender) == "male") {
            $male++;
        }
        $total = $female + $male;

        if (array_key_exists((string) $name, $data)) {
            $data[(string) $name][0] += $female;
            $data[(string) $name][1] += $male;
            $data[(string) $name][2] += $total;
        } else {
            $data[(string) $name] = array($female, $male, $total);
        }
    }
}


$htm = '<table align="center" width="100%">
                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr><td style="text-align:center"><b>'.$report.' '.strtoupper($y_axis).' REPORT</b></td></tr>
                <tr><td style="text-align:center"><b>START DATE : ' . date("d-m-Y h:i:s", strtotime($start_date)) . '</b></td></tr>
                <tr><td style="text-align:center"><b>END DATE : ' . date("d-m-Y h:i:s", strtotime($end_date)) . '</b></td></tr>
            </table>';



$htm .= '<table width=100% border=1 style="border-collapse: collapse;">
				<thead>
			    <tr>
            <td style="width:50px">
               SN 
            </td>
            <td style="width:30%">
               ITEM NAME 
            </td>
            <td>
               MALE 
            </td>
            <td>
               FEMALE 
            </td>
            <td>
               TOTAL 
            </td>
        </tr></thead>';

$index = 1;
foreach ($data as $key => $values) {
    array_push($v_column, $values[2]);
    array_push($h_column, "Row " . $index);
    $htm .= '<tr><td>' . $index . '</td><td>' . $key . '</td><td>' . $values[1] . '</td><td>' . $values[0] . '</td><td>' . $values[2] . '</td></tr>';
    $index++;
}

$htm .= "</table>";
include("./MPDF/mpdf.php");
$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
$mpdf = new mPDF('c', 'A4', '', '', 15, 15, 20, 23, 15, 20, 'P');
$mpdf->SetFooter('Printed By ' . ucwords(strtolower($Employee_Name)) . '  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet, 1);    // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($htm, 2);

$mpdf->Output('mpdf.pdf', 'I');
exit;
