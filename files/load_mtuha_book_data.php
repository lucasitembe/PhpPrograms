<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../includes/connection.php";

$report_type = $_GET['report_type'];
// die($end. " 0:00:00");
// WHERE Disease_Consultation_Date_And_Time BETWEEN '$start' AND '$end'
$query = "SELECT mg.id,mg.disease_name,mg.disease_name,mg.below_month,mg.btn_month_yr,mg.btn_yr_five_yrs,mg.btn_five_sixty_yrs,mg.above_sixty_yrs,mg.gender_type FROM tbl_mtuha_groups mg LEFT JOIN tbl_disease_group_mapping gm ON gm.disease_group_id=mg.id LEFT JOIN tbl_disease d ON gm.disease_id=d.disease_ID LEFT JOIN tbl_disease_consultation dc ON dc.disease_ID=d.disease_ID GROUP BY mg.id ORDER BY mg.id ASC";

$select = mysqli_query($conn, $query);


// $query="SELECT mg.id,mg.disease_name,mg.disease_name, mg.below_month,mg.btn_month_yr,mg.btn_yr_five_yrs,mg.btn_five_sixty_yrs,mg.above_sixty_yrs,mg.gender_type,

// COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) <(1 / 12) AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS maleblwMonth,

// COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) <(1 / 12) AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS femaleblwMonth,

// COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) BETWEEN(1 / 12) AND 1  AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS malebtnMonthYr,

// COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) BETWEEN(1 / 12) AND 1 AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS femalebtnMonthYr,

// COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) BETWEEN 1 AND 5 AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS malebtnYrFive,

// COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) BETWEEN 1 AND 5 AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS femalebtnYrFive,

// COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) BETWEEN 5 AND 60 AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS malebtnFiveSixty,

// COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) BETWEEN 5 AND 60 AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS femalebtnFiveSixty,

// COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) > 60 AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS maleAbvSixty,

// COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,
// c.Consultation_Date_And_Time) > 60 AND c.Consultation_Date_And_Time BETWEEN '2018-07-1' AND '2018-07-31',1,NULL)) AS femaleAbvSixty,

// COUNT(pr.Registration_ID) AS totalPatients
// FROM
// tbl_mtuha_groups mg
// LEFT JOIN tbl_disease_group_mapping gm ON
// gm.disease_group_id = mg.id
// LEFT JOIN tbl_disease d ON
// gm.disease_id = d.disease_ID
// LEFT JOIN tbl_disease_consultation dc ON
// dc.disease_ID = d.disease_ID LEFT JOIN tbl_consultation c ON c.consultation_ID=dc.consultation_ID LEFT JOIN tbl_patient_registration pr ON c.Registration_ID=pr.Registration_ID

// GROUP BY
// mg.id
// ORDER BY
// mg.id ASC";
// die("-------------".$query);
if (mysqli_num_rows($select) > 0) {
    $i = 1;
    $html = "";
    while ($row = mysqli_fetch_assoc($select)) {
        $tdata = renderDiseaseRow($conn, $row);
        $diseaseName = $row['disease_name'];
        $diseaseId = $row['id'];
        if ($diseaseId > 23 and $diseaseId < 28) {
            $malaria = $row;
            if ($diseaseId == 24) {
                $html .= "
                <tr>
                    <td colspan='1' rowspan='4'>$i</td>
                    <td colspan='1' rowspan='4'>Malaria</td>
                    <td>$diseaseName</td>
                    $tdata
                 </tr>";
                $i++;
            } else {

                $html .= "
            <tr>
                <td>$diseaseName</td>
                $tdata
             </tr>";
            }
        } else if ($diseaseId == 4) {
            $html .= "
            <tr>
                <td></td>
                <td colspan='2'>$diseaseName</td>
                 $tdata
             </tr>
             
             <tr>
             <td class='bg-grey'></td>
             <td class='bg-grey text-bold' colspan='2'>Diagnoses Za OPD</td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
             <td class='bg-grey'></td>
         </tr>";
            $i++;
        } else {
            $html .= "
            <tr>
                <td>$i</td>
                <td colspan='2'>$diseaseName</td>
                 $tdata
             </tr>";
            $i++;
        }
    }
    echo $html;
} else {
    echo "<tr>
    <td colspan='21'>
        <center>
            <h6 class='text-warning'>No Disease Added</h6>
        </center>
    </td>
</tr>";
}































function renderDiseaseRow($conn, $disease)
{
    $bg_color = "bg-grey";
    $html = "";

    $row=dataQuerying($conn,$disease);
// die($row);
// print_r($row);
    $totalMale=$row['maleblwMonth']+$row['malebtnMonthYr']+$row['malebtnYrFive']+$row       ['malebtnFiveSixty']+$row['maleAbvSixty'];
    
    $totalFemale=$row['femaleblwMonth']+$row['femalebtnMonthYr']+$row['femalebtnYrFive']+$row['femalebtnFiveSixty']+$row['femaleAbvSixty'];
    if ($disease['id'] ==1) {
        $html = "
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>
    <td><input type='text' class='form-control text-center'  readonly value='0'></td>";
    } else {
    if ($disease['below_month'] == 0) {
        $html .= "
        <td class='$bg_color'></td>
        <td class='$bg_color'></td>
        <td class='$bg_color'></td>
            ";
    } else {
        $html .= renderTableData($row['maleblwMonth'],$row['femaleblwMonth'], $disease['gender_type']);
    }


    if ($disease['btn_month_yr'] == 0) {
        $html .= "
            <td class='$bg_color'></td>
            <td class='$bg_color'></td>
            <td class='$bg_color'></td>
        ";
    } else {
        $html .= renderTableData($row['malebtnMonthYr'],$row['femalebtnMonthYr'], $disease['gender_type']);
    }


    if ($disease['btn_yr_five_yrs'] == 0) {
        $html .= "
        <td class='$bg_color'></td>
        <td class='$bg_color'></td>
        <td class='$bg_color'></td>
    ";
    } else {
        $html .= renderTableData($row['malebtnYrFive'],$row['femalebtnYrFive'], $disease['gender_type']);
    }


    if ($disease['btn_five_sixty_yrs'] == 0) {
        $html .= "
            <td class='$bg_color'></td>
            <td class='$bg_color'></td>
            <td class='$bg_color'></td>
        ";
    } else {
        $html .= renderTableData($row['malebtnFiveSixty'],$row['femalebtnFiveSixty'], $disease['gender_type']);
    }
    if ($disease['above_sixty_yrs'] == 0) {
        $html .= "
            <td class='$bg_color'></td>
            <td class='$bg_color'></td>
            <td class='$bg_color'></td>
        ";
    } else {
        $html .= renderTableData($row['maleAbvSixty'],$row['femaleAbvSixty'], $disease['gender_type']);
    }

        $html .= renderTableData($totalMale,$totalFemale, $disease['gender_type']);
    }

    return $html;
}

function dataQuerying($conn, $disease)
{
    $diseaseId = $disease['id'];
    $date = $_GET['date'];

    $start = $date . " 0:00:00";
    $end = date("Y-m-t", strtotime($date)) . " 23:59:59";
    // $filter = getFilter($age_limit);
    if ($diseaseId < 5 ) {
        $check_in_filter = "";
        if ($diseaseId == 2) {
            $check_in_filter = "ci.Type_Of_Check_In='Afresh' AND ";
        } else if ($diseaseId == 3) {
            $check_in_filter =  "ci.Type_Of_Check_In='Continuous'  AND ";
        }
        $query="SELECT
        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) < (1/12),1,NULL)) AS maleblwMonth,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) < (1/12),1,NULL)) AS femaleblwMonth,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN (1/12) AND 1,1,NULL)) AS malebtnMonthYr,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN (1/12) AND 1,1,NULL)) AS femalebtnMonthYr,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time)BETWEEN 1 AND 5,1,NULL)) AS malebtnYrFive,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN 1 AND 5,1,NULL)) AS femalebtnYrFive,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN 5 AND 60,1,NULL)) AS malebtnFiveSixty,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN 5 AND 60,1,NULL)) AS femalebtnFiveSixty,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) > 60,1,NULL)) AS maleAbvSixty,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) > 60,1,NULL)) AS femaleAbvSixty,
        COUNT(pr.Registration_ID) AS totalPatients
        FROM tbl_check_in ci JOIN tbl_patient_registration pr ON ci.Registration_ID=pr.Registration_ID WHERE $check_in_filter ci.Check_In_Date_And_Time BETWEEN '$start' AND '$end'
";

    } else if ($diseaseId == 99) {
        $query="SELECT
        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date) < (1/12),1,NULL)) AS maleblwMonth,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date) < (1/12),1,NULL)) AS femaleblwMonth,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date) BETWEEN (1/12) AND 1,1,NULL)) AS malebtnMonthYr,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date) BETWEEN (1/12) AND 1,1,NULL)) AS femalebtnMonthYr,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date)BETWEEN 1 AND 5,1,NULL)) AS malebtnYrFive,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date) BETWEEN 1 AND 5,1,NULL)) AS femalebtnYrFive,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date) BETWEEN 5 AND 60,1,NULL)) AS malebtnFiveSixty,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date) BETWEEN 5 AND 60,1,NULL)) AS femalebtnFiveSixty,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date) > 60,1,NULL)) AS maleAbvSixty,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,rp.trans_date) > 60,1,NULL)) AS femaleAbvSixty,
        COUNT(pr.Registration_ID) AS totalPatients
        FROM tbl_referral_patient rp JOIN tbl_patient_registration pr ON rp.Registration_ID=pr.Registration_ID  WHERE rp.trans_date BETWEEN '$start' AND '$end'
";
    }
    
    
    else if ($diseaseId > 100) {
        $sponsor_filter = "";
        if ($diseaseId == 100) {
            $sponsor_filter =  "s.payment_method='credit'  AND ";
        } else if ($diseaseId == 101) {
            $sponsor_filter = "s.payment_method='cash' AND s.Exemption='no' AND";
        }
        elseif ($diseaseId==102) {
            $sponsor_filter = "s.payment_method='cash' AND s.Exemption='yes' AND";
        }

        $query="SELECT
        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) < (1/12),1,NULL)) AS maleblwMonth,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) < (1/12),1,NULL)) AS femaleblwMonth,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN (1/12) AND 1,1,NULL)) AS malebtnMonthYr,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN (1/12) AND 1,1,NULL)) AS femalebtnMonthYr,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time)BETWEEN 1 AND 5,1,NULL)) AS malebtnYrFive,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN 1 AND 5,1,NULL)) AS femalebtnYrFive,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN 5 AND 60,1,NULL)) AS malebtnFiveSixty,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) BETWEEN 5 AND 60,1,NULL)) AS femalebtnFiveSixty,

        COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) > 60,1,NULL)) AS maleAbvSixty,

        COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,ci.Check_In_Date_And_Time) > 60,1,NULL)) AS femaleAbvSixty,
        COUNT(pr.Registration_ID) AS totalPatients
        FROM tbl_check_in ci JOIN tbl_patient_registration pr ON ci.Registration_ID=pr.Registration_ID  LEFT JOIN tbl_sponsor s ON s.Sponsor_ID=pr.Sponsor_ID WHERE $sponsor_filter ci.Check_In_Date_And_Time BETWEEN '$start' AND '$end'
";
// die($query);
    }
    
    else {

        $query = "SELECT 
            COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time) < (1/12),1,NULL)) AS maleblwMonth,

            COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time) < (1/12),1,NULL)) AS femaleblwMonth,

            COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time) BETWEEN (1/12) AND 1,1,NULL)) AS malebtnMonthYr,

            COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time) BETWEEN (1/12) AND 1,1,NULL)) AS femalebtnMonthYr,

            COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time)BETWEEN 1 AND 5,1,NULL)) AS malebtnYrFive,

            COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time) BETWEEN 1 AND 5,1,NULL)) AS femalebtnYrFive,

            COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time) BETWEEN 5 AND 60,1,NULL)) AS malebtnFiveSixty,

            COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time) BETWEEN 5 AND 60,1,NULL)) AS femalebtnFiveSixty,

            COUNT(IF(pr.gender = 'Male' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time) > 60,1,NULL)) AS maleAbvSixty,

            COUNT(IF(pr.gender = 'Female' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,c.Consultation_Date_And_Time) > 60,1,NULL)) AS femaleAbvSixty,
            COUNT(pr.Registration_ID) AS totalPatients

            FROM tbl_mtuha_groups mg LEFT JOIN tbl_disease_group_mapping dgm ON mg.id=dgm.disease_group_id LEFT JOIN tbl_disease d ON dgm.disease_id=d.disease_ID LEFT JOIN tbl_disease_consultation dc ON dc.disease_ID=dgm.disease_id LEFT JOIN tbl_consultation c ON c.consultation_ID=dc.consultation_ID LEFT JOIN tbl_patient_registration pr ON c.Registration_ID=pr.Registration_ID  WHERE mg.id=$diseaseId AND c.Consultation_Date_And_Time BETWEEN '$start' AND '$end'";
    }
    // echo $query."<br><br><br>";
    // die($query);

    $select = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($select);
    return  $row;
}

function renderTableData($male,$female,$gender)
{
    $bg_color = "bg-grey";
    $total=$male+$female;
    if ($gender == "both") {
        return   "
        <td ><input type='text' class='form-control text-center' readonly value='$male'></td>
        <td ><input type='text' class='form-control text-center' readonly value='$female'></td>
        <td ><input type='text' class='form-control text-center' readonly value='$total'></td>
    ";
    } else if ($gender == "female") {
        return  "
            <td class='$bg_color'></td>
            <td ><input type='text' class='form-control text-center' readonly value='$female'></td>
            <td ><input type='text' class='form-control text-center' readonly value='$total'></td>
        ";
    } else {
        return  "
            <td ><input type='text' class='form-control text-center' readonly value='$male'></td>
            <td class='$bg_color'></td>
            <td ><input type='text' class='form-control text-center' readonly value='$total'></td>
        ";
    }
}