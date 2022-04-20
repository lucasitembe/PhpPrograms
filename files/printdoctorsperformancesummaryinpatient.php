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
    $Sponsor = '';
} else {
    $Sponsor = $_GET['Sponsor_ID'];
}

$Guarantor_Name = "All";

$employeeID = $employee_ID = $Employee_ID; //exit;
$EmployeeName = strtoupper(mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employeeID'"))['Employee_Name']);

$filter = "  wr.Ward_Round_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND wr.Employee_ID='$employeeID' AND wr.Process_Status='served' ";

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND pr.Sponsor_ID=$Sponsor";
    $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));

    $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
}

$sql = "SELECT COUNT(wr.Registration_ID) AS total_patient  FROM tbl_ward_round wr  WHERE $filter ORDER BY wr.consultation_ID ";


$consultationPat = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$totalAllPat =  mysqli_fetch_assoc($consultationPat)['total_patient'] ;

$htm = "<table width ='100%' class='nobordertable'>
		    <tr><td style='text-align:center'>
			<img src='./branchBanner/branchBanner.png' width='100%'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>$EmployeeName PERFORMANCE ROUND REPORT</b></span></td></tr>
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


    $consultationDateRange = mysqli_query($conn,"SELECT wr.Registration_ID,pr.Patient_Name,pr.Phone_Number,wr.employee_ID,e.Employee_Name,wr.consultation_ID FROM tbl_ward_round wr JOIN tbl_employee e ON wr.Employee_ID=e.Employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=wr.Registration_ID  WHERE $filter   AND  DATE(wr.Ward_Round_Date_And_Time)='$value' ORDER BY wr.consultation_ID") or die(mysqli_error($conn));


//retrieve consultations for the employee		
    $empSN = 1;

    if (mysqli_num_rows($consultationDateRange) > 0) {
        $noOfPatient = mysqli_num_rows($consultationDateRange);
        $totalPPP +=$noOfPatient;

        $htm .= "<div style='margin:10px 0px 10px 0px;width:100%;text-align:left;font-family: times;font-size: large;font-weight: bold;background-color:#ccc;padding:4px'>$thisDate</div>";

        $htm .= '<center><table style="width:100%" border="1" class="display" id="doctorsperformancetbl">';
        $htm .= "<thead><tr>
			        <td widtd=3% style='text-align:left'><b>SN</b></td>
			  	<td style='text-align:left;width:35%' ><b>PATIENT NAME</b></td>
			        <td style='text-align:center'><b>LAB</b></td> 
				<td style='text-align:center'><b>RADIOLOGY</b></td>
				<td style='text-align:center'><b>PHARMACY</b></td>
                                <td style='text-align:center'><b>PROCEDURE</b></td>
                                <td style='text-align:center;'><b>FINAL DIAGNOSIS</b></td>
                                <td style='text-align:center'><b>TIME</b></td>
			  </tr></thead>";

        //$sql = "SELECT  pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE $filter  AND  DATE(ch.cons_hist_Date)='$value'";

        $consultations = mysqli_query($conn,"SELECT wr.Registration_ID,pr.Patient_Name,pr.Phone_Number,wr.employee_ID,e.Employee_Name,wr.consultation_ID,wr.Ward_Round_Date_And_Time FROM tbl_ward_round wr JOIN tbl_employee e ON wr.Employee_ID=e.Employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=wr.Registration_ID  WHERE $filter   AND  DATE(wr.Ward_Round_Date_And_Time)='$value' ORDER BY wr.consultation_ID DESC") or die(mysqli_error($conn));

//$htm .= "SELECT pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employee_ID'";
        $empSN = 1;

        if (mysqli_num_rows($consultations) > 0) {

            while ($row = mysqli_fetch_array($consultations)) {

                $Registration_ID = $row['Registration_ID'];
                $patient_name = $row['Patient_Name'];


                $employeeName = $row['Employee_Name'];

                $Employee_Name_Cur = $row['Employee_Name'];

                $Ward_Round_Date_And_Time = $row['Ward_Round_Date_And_Time'];

                $consultation_ID = $row['consultation_ID'];



                $finalDiagnosis = "<span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >No</span>";
                // $proviDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >No</span>";

                $checkIfHasFinalDiagnosis = mysqli_query($conn,"
		   SELECT wr.Round_ID FROM tbl_ward_round_disease wrd INNER JOIN tbl_ward_round wr ON wr.Round_ID=wrd.Round_ID WHERE wr.consultation_ID='$consultation_ID' AND wr.Registration_ID='$Registration_ID' AND  wr.employee_ID='$employeeID' AND wrd.diagnosis_type='diagnosis'   AND  DATE(wr.Ward_Round_Date_And_Time)='$value' 
		") or die(mysql_error);

                if (mysqli_num_rows($checkIfHasFinalDiagnosis) > 0) {
                    $finalDiagnosis = "<span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >Yes</span>";
                }

                $select_checking_type = mysqli_query($conn,"SELECT 
     (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.consultation_ID='$consultation_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'  AND  DATE(pc.Payment_Date_And_Time)='$value'  ) as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.consultation_ID='$consultation_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'  AND  DATE(pc.Payment_Date_And_Time)='$value'  ) as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.consultation_ID='$consultation_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'  AND  DATE(pc.Payment_Date_And_Time)='$value'  ) as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.consultation_ID='$consultation_ID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'  AND  DATE(pc.Payment_Date_And_Time)='$value'  ) as Procedur
	 ") or die(mysqli_error($conn));

                $rowChkType = mysqli_fetch_assoc($select_checking_type);
                $Laboratory = $rowChkType['Laboratory'];
                $Radiology = $rowChkType['Radiology'];
                $Pharmacy = $rowChkType['Pharmacy'];
                $Procedur = $rowChkType['Procedur'];
                //if( $avoidDoctorNameDuplicate==1){$employeeName='';}
                $htm .= "<tr><td>" . ($empSN++) . "</td>";
                //$htm .= "<td style='text-align:left'>".$employeeName."</td>";
                $htm .= "<td style='text-align:left'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . $patient_name . "</span></td>";
                $htm .= "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Laboratory > 0 ? 'Yes' : 'No') . "</span></td>";

                $htm .= "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Radiology > 0 ? 'Yes' : 'No') . "</span></td>";

                $htm .= "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Pharmacy > 0 ? 'Yes' : 'No') . "</span></td>";

                $htm .= "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Procedur > 0 ? 'Yes' : 'No') . "</span></td>";

                $htm .= "<td style='text-align:center'>" . ($finalDiagnosis) . "</td>";
                $htm .= "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Ward_Round_Date_And_Time) . "</span></td></tr>";
            }
            $htm .= '</table></center><br/>';
        }
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

$mpdf = new mPDF('s', 'A4-L');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);
$mpdf->Output();