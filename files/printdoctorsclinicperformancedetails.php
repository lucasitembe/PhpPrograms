<style>
    .dates{
        color:#002166;
    }
</style>
<?php
@session_start();
include("./includes/connection.php");

$Date_From = ''; //@$_POST['Date_From'];
$Date_To = ''; //@$_POST['Date_To'];
$todayqr = mysqli_fetch_assoc(mysqli_query($conn,"SELECT NOW() AS TODAYDATE"))['TODAYDATE'];
$today = $todayqr; //date('Y-m-d H:m:s');

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$clinic = filter_input(INPUT_GET, 'clinic');
$Employee_ID = filter_input(INPUT_GET, 'Employee_ID');

$filter = " DATE(ch.cons_hist_Date)=DATE(NOW())";
$filterSp = " DATE(ch.cons_hist_Date)=DATE(NOW())";

if (isset($_GET['Sponsor'])) {


    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  ch.cons_hist_Date BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
        $filterSp = "  ch.cons_hist_Date BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    }

    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND pr.Sponsor_ID=$Sponsor";
        $filterSp .="  AND pr.Sponsor_ID=$Sponsor";
    }
//$htm .= $ward;exit;
    if (!empty($clinic) && $clinic != 'All') {
        $filter .= " AND pl.Clinic_ID IN (SELECT Clinic_ID FROM tbl_clinic_employee ce WHERE ce.Clinic_ID='$clinic' AND ce.Employee_ID =" . $Employee_ID . ")";
    }
}

$range = '';

if ($Date_From != '' && $Date_To != '') {
    $range .=" FROM <span style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($Date_From)) . "</span> TO <span style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($Date_To)) . "</span>";
}
if ($Sponsor != 'All') {
    $get_sponsor_name = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));
    $SponsorName = mysqli_fetch_assoc($get_sponsor_name)['Guarantor_Name'];

    if ($Date_From != '' && $Date_To != '') {
        $range .="<br/>Sponsor:<span style='color:#002166;'>" . $SponsorName . "</span>";
    } else {
        $range .="Sponsor:<span style='color:#002166;'>" . $SponsorName . "</span>";
    }
}
if ($clinic != 'All') {
    $get_clinic_name = mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$clinic'") or die(mysqli_error($conn));
    $Clinic_Name = mysqli_fetch_assoc($get_clinic_name)['Clinic_Name'];

    if ($Sponsor != 'All') {
        $range .=" | Clinic Name:<span style='color:#002166;'>" . $Clinic_Name . "</span>";
    } else {
        if ($Date_From != '' && $Date_To != '') {
            $range .="<br/>Clinic Name:<span style='color:#002166;'>" . $Clinic_Name . "</span>";
        } else {
            $range .="Clinic Name:<span style='color:#002166;'>" . $Clinic_Name . "</span>";
        }
    }
}
$range .="";


$EmployeeName = strtoupper(mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name']);

$htm = "<table width ='100%'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>$EmployeeName CLINIC PERFORMANCE REPORT</b></span></td></tr>
                    <tr><td style='text-align: center;'><b>$range</b></td></tr>
		    </table><br/>";

if ($clinic == 'All' || $clinic == '') {
    $query = "SELECT cl.Clinic_ID,Clinic_Name FROM tbl_Clinic cl INNER JOIN tbl_clinic_employee ce ON ce.Clinic_ID=cl.Clinic_ID JOIN tbl_patient_payment_item_list pl ON ce.Clinic_ID=pl.Clinic_ID WHERE ce.Employee_ID =" . $Employee_ID . "";
    //die($query);
    $result = mysqli_query($conn,$query) or die(mysqli_error($conn));

    $sql = "SELECT cl.Clinic_ID,Clinic_Name FROM tbl_consultation_history ch 
                                       LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID 
                                       JOIN tbl_employee e ON ch.employee_ID=e.employee_ID
                                       JOIN tbl_patient_payment_item_list pl ON c.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID
                                       JOIN tbl_Clinic cl ON cl.Clinic_ID=pl.Clinic_ID
                                       JOIN tbl_clinic_employee ce ON ce.Clinic_ID=cl.Clinic_ID
                                       JOIN tbl_patient_registration pr ON pr.Registration_ID = c.Registration_ID
                                       WHERE  $filterSp AND ch.employee_ID='$Employee_ID' group by cl.Clinic_ID
                                       ";
    $resultcl = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($resultcl)) {
        $Clinic_ID = $row['Clinic_ID'];
        $Clinic_Name = $row['Clinic_Name'];

        $htm .= "<h1 style='width:100%;border-top:0px;padding-bottom:5px;text-align:left'>" . ucfirst(strtolower($Clinic_Name)) . "</h1>";
        $htm .= "<table class='patientList' width='100%'> 
                <thead>
                   <tr class='headerrow'>
                       <th style='text-align:left;width:5%'>S/n</th>
                       <th style='text-align:left'>PATIENT NAME</th>
                       <th>SPONSOR</th>
                       <th>AGE</th>
                       <th>GENDER</th>
                       <th>PHONE</th>
                       <th>MEMBER #</th>
                       <th>DISTRICT</th>
                       <th>CONS. DATE</th>
                   </tr>
                    <tr><td colspan='9'><hr style='width:100%'/></td></tr>
                </thead>";

        $sql = "SELECT NOW() as today, pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Phone_Number,pr.Registration_ID,pr.District,sp.Guarantor_Name,ch.cons_hist_Date
                        FROM tbl_consultation_history ch 
                        LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID 
                        JOIN tbl_employee e ON ch.employee_ID=e.employee_ID
                        JOIN tbl_patient_payment_item_list pl ON c.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID
                        JOIN tbl_patient_registration pr ON pr.Registration_ID = c.Registration_ID
                        JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                        WHERE $filterSp AND pl.Clinic_ID='$Clinic_ID' AND ch.employee_ID='$Employee_ID'
                        ";
        //$htm .=($sql).'<br/>';
        $result_patient = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        $sn = 1;
        while ($row_patient = mysqli_fetch_array($result_patient)) {
            $row_patient['Patient_Name'] . '<br/>';
            $age = floor((strtotime(date('Y-m-d')) - strtotime($row_patient['Date_Of_Birth'])) / 31556926) . " Years";
            // if($age == 0){
            $Today = $row_patient['today'];
            $date1 = new DateTime($Today);
            $date2 = new DateTime($row_patient['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

            $htm .= "<tr>"
            . "<td>" . $sn++ . "</td>"
            . "<td>" . $row_patient['Patient_Name'] . "</td>"
            . "<td>" . $row_patient['Guarantor_Name'] . "</td>"
            . "<td>" . $age . "</td>"
            . "<td>" . $row_patient['Gender'] . "</td>"
            . "<td>" . $row_patient['Phone_Number'] . "</td>"
            . "<td>" . $row_patient['Registration_ID'] . "</td>"
            . "<td>" . $row_patient['District'] . "</td>"
            . "<td>" . $row_patient['cons_hist_Date'] . "</td>"
            . "</tr>";
            $htm .= "<tr><td colspan='9'><hr style='width:100%'/></td></tr>";
        }
        $htm .= "</table><br/>";
    }
} else {
    $get_clinic_name = mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$clinic'") or die(mysqli_error($conn));

    $Clinic_Name = mysqli_fetch_assoc($get_clinic_name)['Clinic_Name'];

    $htm .= "<h1 style='margin:10px 0 0 0;width:100%;border-bottom:1px solid #A3A3A3;padding-bottom:5px;text-align:left'>" . ucfirst(strtolower($Clinic_Name)) . "</h1>";
    $htm .= "<table class='patientList' width='100%'> 
                <thead>
                   <tr class='headerrow'>
                       <th style='text-align:left;width:5%'>S/n</th>
                       <th style='text-align:left'>PATIENT NAME</th>
                       <th>SPONSOR</th>
                       <th>AGE</th>
                       <th>GENDER</th>
                       <th>PHONE</th>
                       <th>MEMBER #</th>
                       <th>DISTRICT</th>
                       <th>CONS. DATE</th>
                   </tr>
                   <tr><td colspan='9'><hr style='width:100%'/></td></tr>
                </thead>";

    $sql = "SELECT NOW() as today, pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Phone_Number,pr.Registration_ID,pr.District,sp.Guarantor_Name,ch.cons_hist_Date
                        FROM tbl_consultation_history ch 
                        LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID 
                        JOIN tbl_employee e ON ch.employee_ID=e.employee_ID
                        JOIN tbl_patient_payment_item_list pl ON c.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID
                        JOIN tbl_patient_registration pr ON pr.Registration_ID = c.Registration_ID
                        JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                        WHERE $filterSp AND pl.Clinic_ID='$clinic' AND ch.employee_ID='$Employee_ID'
                        ";
    //$htm .=($sql).'<br/>';
    $result_patient = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $sn = 1;
    while ($row_patient = mysqli_fetch_array($result_patient)) {
        $row_patient['Patient_Name'] . '<br/>';
        $age = floor((strtotime(date('Y-m-d')) - strtotime($row_patient['Date_Of_Birth'])) / 31556926) . " Years";
        // if($age == 0){
        $Today = $row_patient['today'];
        $date1 = new DateTime($Today);
        $date2 = new DateTime($row_patient['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        $htm .= "<tr>"
        . "<td>" . $sn++ . "</td>"
        . "<td>" . $row_patient['Patient_Name'] . "</td>"
        . "<td>" . $row_patient['Guarantor_Name'] . "</td>"
        . "<td>" . $age . "</td>"
        . "<td>" . $row_patient['Gender'] . "</td>"
        . "<td>" . $row_patient['Phone_Number'] . "</td>"
        . "<td>" . $row_patient['Registration_ID'] . "</td>"
        . "<td>" . $row_patient['District'] . "</td>"
        . "<td>" . $row_patient['cons_hist_Date'] . "</td>"
        . "</tr>";
        $htm .= "<tr><td colspan='9'><hr style='width:100%'/></td></tr>";
    }
    $htm .= "</table>";
}

include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);

//$mpdf->SetDisplayMode('fullpage');
//$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($htm, 2);

$mpdf->Output('mpdf.pdf', 'I');
