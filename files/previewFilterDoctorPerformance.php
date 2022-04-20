<?php

include("./includes/connection.php");
session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work']) && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
        
    } else if (isset($_SESSION['userinfo']['Mtuha_Reports']) && $_SESSION['userinfo']['Mtuha_Reports'] == 'yes') {
        
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $E_Name = '';
}

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];

$Date_From = mysqli_real_escape_string($conn,$_GET['Date_From']);
$Date_To = mysqli_real_escape_string($conn,$_GET['Date_To']);
$Sponsor = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
$type_of_doctor_consultation = mysqli_real_escape_string($conn,$_GET['type_of_doctor_consultation']);
$process_status = '';

$consultation_type="";
if($type_of_doctor_consultation!="All"){
    $consultation_type="AND ch.consultation_type='$type_of_doctor_consultation'";
}
if ($is_perf_by_signe_off == '1') {
    $process_status = "  AND ppl.Process_Status = 'signedoff'";
}

$filter = "  AND ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND  Saved='yes' $process_status";

$Guarantor_Name = "All";

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND pr.Sponsor_ID=$Sponsor";
    $filter2="   AND pr.Sponsor_ID='$Sponsor'";
    $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));

    $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
}


$htm = "<table width ='100%' class='nobordertable'>
		    <tr><td style='text-align:center'>
			<img src='./branchBanner/branchBanner.png' width='100%'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span style='font-size: x-small;'><b>DOCTOR'S  PERFORMANCE REPORT</b></span></td></tr>
                    <tr><td style='text-align: center;'><span style='font-size: x-small;'><b>OUTPATIENT</b></span></td></tr>
                    <tr><td style='text-align: center;'><span style='font-size: x-small;'><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($Date_From)) . "</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>" . date('j F, Y H:i:s', strtotime($Date_To)) . "</b></td></tr>
                    <tr><td style='text-align: center;'><span style='font-size: x-small;'><b>SPONSOR</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';
$htm .= "<thead>
    <tr><td colspan='3' ></td><td colspan='4' style='text-align:center'><span style='font-size: x-small;'>NUMBER OF PATIENT SENT</span></td></tr>
                        <tr>
                          <td widtd='3%' style='text-align:left'><span style='font-size: x-small;'>SN</span></td>
                          <td style='text-align:left;' width='30%'><span style='font-size: x-small;'>DOCTOR'S NAME</span></td>
                          <td style='text-align:center;width:18%'><span style='font-size: x-small;'>TOTAL PATIENTS</span></td>
                          <td style='text-align:center'><span style='font-size: x-small;'>LAB</span></td>
                          <td style='text-align:center'><span style='font-size: x-small;'>RAD</span></td>
                          <td style='text-align:center'><span style='font-size: x-small;'>PHAR</span></td>
                          <td style='text-align:center'><span style='font-size: x-small;'>PROC</span></td>
                          <td style='text-align:center'><span style='font-size: x-small;'>SURG</span></td>
                        </tr>
                      </thead>";

//run the query to select all data from the database according to the branch id
$select_doctor_query = "SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' AND Account_Status='active' ORDER BY Employee_Name ASC";


$select_doctor_result = mysqli_query($conn,$select_doctor_query);

    $LaboratoryGrandTotal = 0;
    $PatientsGrandTotal = 0;
    $RadiologyGrandTotal = 0;
    $PharmacyGrandTotal = 0;
    $ProcedurGrandTotal = 0;
    $SurgeryGrandTotal = 0;

$empSN = 0;
while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
    $employeeID = $select_doctor_row['Employee_ID'];
    $employeeName = $select_doctor_row['Employee_Name'];

    $result_patient_no = mysqli_query($conn,"SELECT c.consultation_ID FROM tbl_consultation_history ch "
            . "JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID "
            . "JOIN tbl_employee e ON ch.employee_ID=e.employee_ID 
                      JOIN tbl_patient_payment_item_list ppl ON ppl.Patient_Payment_Item_List_ID=c.Patient_Payment_Item_List_ID  "
            . "JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID WHERE ch.employee_ID='$employeeID' $consultation_type  $filter ") or die(mysqli_error($conn));

//    if($type_of_doctor_consultation=="new_consultation"){
//        $result_patient_no=mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation c INNER JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID INNER JOIN tbl_patient_registration pr ON c.Registration_ID=pr.Registration_ID WHERE c.employee_ID='$employeeID' AND c.Consultation_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND DATE(ppl.Transaction_Date_And_Time)=DATE(c.Consultation_Date_And_Time)  $filter2") or die(mysqli_error($conn));
//    }else if($type_of_doctor_consultation=="result_consultation"){
//        $result_patient_no=mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation c INNER JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID INNER JOIN tbl_patient_registration pr ON c.Registration_ID=pr.Registration_ID WHERE c.employee_ID='$employeeID' AND c.Consultation_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND DATE(ppl.Transaction_Date_And_Time)<>DATE(c.Consultation_Date_And_Time) $filter2") or die(mysqli_error($conn));
//    }
    
    $patient_no_number = mysqli_num_rows($result_patient_no);

    $LaboratoryTotal = 0;
    $RadiologyTotal = 0;
    $PharmacyTotal = 0;
    $ProcedurTotal = 0;
    $SurgeryTotal = 0;

    while ($patientdt = mysqli_fetch_array($result_patient_no)) {
        $consultation_ID = $patientdt['consultation_ID'];

        $select_checking_type = mysqli_query($conn,"SELECT 
         (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID') as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID' ) as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  ) as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  ) as Procedur,
         
         (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Surgery' AND ilc.Consultant_ID='$employeeID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  ) as Surger
	 ") or die(mysqli_error($conn));

        $rowChkType = mysqli_fetch_assoc($select_checking_type);
        $Laboratory = $rowChkType['Laboratory'];
        $Radiology = $rowChkType['Radiology'];
        $Pharmacy = $rowChkType['Pharmacy'];
        $Procedur = $rowChkType['Procedur'];
        $Surger = $rowChkType['Surger'];

        //Total
        if ($Laboratory > 0) {
            $LaboratoryTotal += 1;
        }
        if ($Radiology > 0) {
            $RadiologyTotal += 1;
        }
        if ($Pharmacy > 0) {
            $PharmacyTotal += 1;
        }
        if ($Procedur > 0) {
            $ProcedurTotal += 1;
        }
        if ($Surger > 0) {
            $SurgeryTotal += 1;
        }
    }

        $LaboratoryGrandTotal += $LaboratoryTotal;
        $RadiologyGrandTotal += $RadiologyTotal;
        $PharmacyGrandTotal += $PharmacyTotal;
        $ProcedurGrandTotal += $ProcedurTotal;
        $SurgeryGrandTotal += $SurgeryTotal;

    $empSN ++;
    $htm .= "<tr><td><span style='font-size: x-small;'>" . ($empSN) . "</span></td>";
    $htm .= "<td style='text-align:left' ><span style='font-size: x-small;'>" . $employeeName . "</span></td>";
    $htm .= "<td style='text-align:center'><span style='font-size: x-small;'>" . number_format($patient_no_number) . "</span></td>";
    $htm .= "<td style='text-align:center'><span style='font-size: x-small;'>" . number_format($LaboratoryTotal) . "</span></td>";
    $htm .= "<td style='text-align:center'><span style='font-size: x-small;'>" . number_format($RadiologyTotal) . "</span></td>";
    $htm .= "<td style='text-align:center'><span style='font-size: x-small;'>" . number_format($PharmacyTotal) . "</span></td>";
    $htm .= "<td style='text-align:center'><span style='font-size: x-small;'>" . number_format($ProcedurTotal) . "</span></td>";
    $htm .= "<td style='text-align:center'><span style='font-size: x-small;'>" . number_format($SurgeryTotal) . "</span></td></tr>";
}
    $htm .= "<tr><td colspan='2'  style='text-align:center'><b><span style='font-size: x-small;'>GRAND TOTAL</span></b></td>";
    $htm .= "<td style='text-align:center'><b><span style='font-size: x-small;'>" . number_format($PatientsGrandTotal) . "</span></b></td>";
    $htm .= "<td style='text-align:center'><b><span style='font-size: x-small;'>" . number_format($LaboratoryGrandTotal) . "</span></b></td>";
    $htm .= "<td style='text-align:center'><b><span style='font-size: x-small;'>" . number_format($RadiologyGrandTotal) . "</span></b></td>";
    $htm .= "<td style='text-align:center'><b><span style='font-size: x-small;'>" . number_format($PharmacyGrandTotal) . "</span></b></td>";
    $htm .= "<td style='text-align:center'><b><span style='font-size: x-small;'>" . number_format($ProcedurGrandTotal) . "</span></b></td>";
    $htm .= "<td style='text-align:center'><b><span style='font-size: x-small;'>" . number_format($SurgeryGrandTotal) . "</span></b></td></tr>";
 $htm .= ' </table></center>';

include("./MPDF/mpdf.php");
$mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
$mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
?>
