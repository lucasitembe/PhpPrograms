<?php

@session_start();
include("./includes/connection.php");

$Date_From = ''; //@$_POST['Date_From'];
$Date_To = ''; //@$_POST['Date_To'];
$todayqr = mysqli_fetch_assoc(mysqli_query($conn,"SELECT NOW() AS TODAYDATE"))['TODAYDATE'];
$today = $todayqr; //date('Y-m-d H:m:s');

if (!isset($_GET['Date_From'])) {
    $Date_From = $today;
} else {
    $Date_From = $_GET['Date_From'];
}
if (!isset($_GET['Date_To'])) {
    $Date_To = $today;
} else {
    $Date_To = $_GET['Date_To'];
}if (!isset($_GET['Employee_ID'])) {
    $Employee_ID = 0;
} else {
    $Employee_ID = $_GET['Employee_ID'];
}if (!isset($_GET['Sponsor_ID'])) {
    $Sponsor = "All";
} else {
    $Sponsor = $_GET['Sponsor_ID'];
}


$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];

$employeeID = $employee_ID = $Employee_ID; //exit;
$EmployeeName = strtoupper(mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employeeID'"))['Employee_Name']);

$process_status = '';

if ($is_perf_by_signe_off == '1') {
    $process_status = "  AND ppl.Process_Status = 'signedoff'";
}

$filter = "  ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employee_ID' AND Saved='yes' $process_status ";

$Guarantor_Name = "All";
if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND pr.Sponsor_ID=$Sponsor";
    $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));

    $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
}

$sql = "SELECT COUNT(c.Registration_ID) AS total_patient FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE $filter ";

$consultationPat = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$totalAllPat = "<span class='dates'>" . mysqli_fetch_assoc($consultationPat)['total_patient'] . '</span>';


$htm = "<table width ='100%'>
		    <tr><td style='text-align:center'>
			<img src='./branchBanner/branchBanner.png' width='100%'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>$EmployeeName PERFORMANCE REPORT</b></span></td></tr>
                    <tr><td style='text-align: center;'><span><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($Date_From)) . "</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>" . date('j F, Y H:i:s', strtotime($Date_To)) . "</b></td></tr>
                    <tr><td style='text-align: center;'><span><b>SPONSOR</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $Guarantor_Name . "</b></td></tr>
                        <tr><td style='text-align: center;'><span><b>TOTAL PATIENT </b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $totalAllPat . "</b></td></tr>
        </table><br/>";

$avoidDoctorNameDuplicate = 0;
$Employee_Name_Cur = '';

$dataRange = returnBetweenDates($Date_From, $Date_To);
$totalPPP = 0;
foreach ($dataRange as $value) {
    $thisDate = date('d, M y', strtotime($value)) . '';
    $sql = "SELECT  pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE $filter  AND  DATE(ch.cons_hist_Date)='$value'";

    $consultationDateRange = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if (mysqli_num_rows($consultationDateRange) > 0) {
        //$rowPatient = mysqli_fetch_array($consultationDateRange);
        $noOfPatient = mysqli_num_rows($consultationDateRange);
        $totalPPP +=$noOfPatient;
        $htm .= "<div style='margin:10px 0px 10px 0px;width:100%;text-align:left;font-family: times;font-size: large;font-weight: bold;background-color:#ccc;padding:4px'>" . $thisDate . "<span style='float:right'> </span></div>";
    
        $htm .= '<center><table width ="100%" border="0" class="display" >';
        $htm .= "<thead>
                        <tr><td colspan='12' style='width:100%;border-bottom:1px solid #000'></td></tr>
                        <tr class='headerrow'>
                          <th width='3%' style='text-align:left'>SN</th>
                          <th style='text-align:left;' width='27%'>PATIENT NAME</th>
                          <th style='text-align:center'>SPONSOR</th>
                          <th style='text-align:center'>CONSULT</th>
                          <th style='text-align:center'>LAB</th> 
                          <th style='text-align:center'>RAD</th>
                          <th style='text-align:center'>PHAR</th>
                          <th style='text-align:center'>PROC</th>
                          <th style='text-align:center'>F. DIAG</th>
                          <th style='text-align:center'>PHONE</th>
                          <th style='text-align:center'>BILL</th>
                          <th style='text-align:center'>TIME</th>
                        </tr>
                         <tr><td colspan='12' style='width:100%;border-bottom:1px solid #000'></td></tr>
                      </thead>";
//retrieve consultations for the employee		
//$consultations=mysqli_query($conn,"SELECT pr.Patient_Name,pr.Phone_Number,co.Registration_ID,co.employee_ID,co.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID FROM tbl_consultation co JOIN tbl_patient_registration pr ON pr.Registration_ID=co.Registration_ID JOIN tbl_patient_payment_item_list ppl ON co.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE co.employee_ID='$employeeID' AND DATE(co.Consultation_Date_And_Time)='$today'") or die(mysqli_error($conn));
        $consultations = mysqli_query($conn,"SELECT pr.Patient_Name,sp.Guarantor_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ch.cons_hist_Date,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID  JOIN tbl_sponsor sp ON pr.Sponsor_ID=sp.Sponsor_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE $filter  AND  DATE(ch.cons_hist_Date)='$value'") or die(mysqli_error($conn));

//$htm .= "SELECT pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employee_ID'";
        $empSN = 1;

        if (mysqli_num_rows($consultations) > 0) {


            while ($row = mysqli_fetch_array($consultations)) {

                $Registration_ID = $row['Registration_ID'];
                $patient_name = $row['Patient_Name'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $employeeName = $row['Employee_Name'];
                $Employee_Name_Cur = $row['Employee_Name'];
                $Phone_Number = $row['Phone_Number'];
                $consultation_ID = $row['consultation_ID'];
                $consultation_Date = $row['cons_hist_Date'];


                $finalDiagnosis = "No";
                // $proviDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >No</span>";

                $checkIfHasFinalDiagnosis = mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc INNER JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  dc.employee_ID='$employeeID'  AND  DATE(dc.Disease_Consultation_Date_And_Time)='$value' AND dc.diagnosis_type='diagnosis'
		") or die(mysqli_error($conn));

                if (mysqli_num_rows($checkIfHasFinalDiagnosis) > 0) {
                    $finalDiagnosis = "Yes";
                }

                //select checking type
                $select_checking_type = mysqli_query($conn,"SELECT 
     (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID' AND  DATE(pc.Payment_Date_And_Time)='$value' ) as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID' AND  DATE(pc.Payment_Date_And_Time)='$value' ) as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  AND  DATE(pc.Payment_Date_And_Time)='$value' ) as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  AND  DATE(pc.Payment_Date_And_Time)='$value' ) as Procedur
	 ") or die(mysqli_error($conn));

                $rowChkType = mysqli_fetch_assoc($select_checking_type);
                $Laboratory = $rowChkType['Laboratory'];
                $Radiology = $rowChkType['Radiology'];
                $Pharmacy = $rowChkType['Pharmacy'];
                $Procedur = $rowChkType['Procedur'];
                $hrefpatientfile = 'Patientfile_Record_Detail.php?consultation_ID=' . $consultation_ID . '&Registration_ID=' . $Registration_ID . '&Section=ManagementPatient&Employee_ID=' . $employeeID . '&Date_From=' . $_GET['Date_From'] . '&Date_To=' . $_GET['Date_To'] . '&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage&PatientFile=PatientFileThisForm';
                //if( $avoidDoctorNameDuplicate==1){$employeeName='';}
                $htm .= "<tr><td>" . ($empSN++) . "</td>";
                //$htm .= "<td style='text-align:left'>".$employeeName."</td>";
                $htm .= "<td style='text-align:left;'>" . $patient_name . "</td>";
                $htm .= "<td style='text-align:left'>" . $Guarantor_Name . "</td>";
                $htm .= "<td style='text-align:center'>Yes</span></td>";
                $htm .= "<td style='text-align:center'>" . ($Laboratory > 0 ? 'Yes' : 'No') . "</td>";
                $htm .= "<td style='text-align:center'>" . ($Radiology > 0 ? 'Yes' : 'No') . "</td>";
                $htm .= "<td style='text-align:center'>" . ($Pharmacy > 0 ? 'Yes' : 'No') . "</td>";
                $htm .= "<td style='text-align:center'>" . ($Procedur > 0 ? 'Yes' : 'No') . "</td>";
                $htm .= "<td style='text-align:center'>" . ($finalDiagnosis) . "</td>";
                $htm .= "<td style='text-align:center'>" . ($Phone_Number) . "</td>";
                $htm .= "<td style='text-align:center'>" . ($Patient_Payment_ID) . "</td>";
                $htm .= "<td style='text-align:center'>" . ($consultation_Date) . "</td>";
                $htm .= '</tr>'
                        . '<tr><td colspan="12" style="width:100%;border-bottom:1px solid #000"></td></tr>';
                // $avoidDoctorNameDuplicate=1;
            }
        }
        $htm .= '</table></center><br/>';
    }
}

function returnBetweenDates($startDate, $endDate) {
    $startStamp = strtotime($startDate);
    $endStamp = strtotime($endDate);

    if ($endStamp > $startStamp) {
        while ($endStamp >= $startStamp) {

            $dateArr[] = date('Y-m-d', $startStamp);

            $startStamp = strtotime(' +1 day ', $startStamp);
        }
        return $dateArr;
    } else {
        return $startDate;
    }
}

include("MPDF/mpdf.php");
$mpdf = new mPDF('s', 'A4');

$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);

$mpdf->Output('mpdf.pdf', 'I');
