<?php
include("./includes/connection.php");

$Date_From = ''; //@$_POST['Date_From'];
$Date_To = ''; //@$_POST['Date_To'];

if (!isset($_GET['Date_From'])) {
    $Date_From = date('Y-m-d H:m');
} else {
    $Date_From = $_GET['Date_From'];
}
if (!isset($_GET['Sponsor_ID'])) {
    $Sponsor = '';
    ;
} else {
    $Sponsor = $_GET['Sponsor_ID'];
}

if (!isset($_GET['Date_To'])) {
    $Date_To = date('Y-m-d H:m');
    ;
} else {
    $Date_To = $_GET['Date_To'];
}

$filter="  wr.Ward_Round_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' ";

if (!empty($Sponsor) && $Sponsor != 'All') {
     $filter .="  AND pr.Sponsor_ID=$Sponsor";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
     $filter2 ="  AND pc.Sponsor_ID=$Sponsor";
     $Guarantor_Name = "All";
}
else {
    $filter2 = "";
    $Guarantor_Name = "All";
}
?>
<br/><br/>


        <?php
        $htm = "<table width ='100%' class='nobordertable'>
        		    <tr><td style='text-align:center'>
        			<img src='./branchBanner/branchBanner.png' width='100%'>
        		    </td></tr>
        		    <tr><td style='text-align: center;'><span><b>DOCTOR'S ROUND REPORT SUMMARY</b></span></td></tr>
                            <tr><td style='text-align: center;'><span><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($Date_From)) . "</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>" . date('j F, Y H:i:s', strtotime($Date_To)) . "</b></td></tr>
                            <tr><td style='text-align: center;'><span><b>SPONSOR</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $Guarantor_Name . "</b></td></tr>
                </table><br/>";
        $htm .= '<center><table width =100% border="1" class="display" id="doctorsperformancetbl">';
        $htm .= "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
          <th>LAB SENT</th>
          <th>RAD SENT</th>
          <th>PHARM SENT</th>
          <th>PROC SENT</th>
          <th>TOTAL PATIENTS</th>
			    <th style='text-align: left;' width=18%>TOTAL ITEMS ORDERED</th>
		     </tr></thead>";
        //run the query to select all data from the database according to the branch id
        $select_doctor_query = "SELECT DISTINCT(emp.Employee_ID),emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp INNER JOIN tbl_ward_round wr ON wr.Employee_ID=emp.Employee_ID  WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";


        $select_doctor_result = mysqli_query($conn,$select_doctor_query) or die(mysqli_error($conn));

        $empSN = 0;
        while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
            $employeeID = $select_doctor_row['Employee_ID'];
            $employeeName = $select_doctor_row['Employee_Name'];
            //$employeeNumber=$select_doctor_row['Employee_Number'];


              $lab_total_count = 0;
              $rad_total_count = 0;
              $pham_total_count = 0;
              $proc_total_count = 0;
              $select_checking_type = mysqli_query($conn,"SELECT
              (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND pc.Employee_ID=$employeeID AND  (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Laboratory,

              (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND pc.Employee_ID=$employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Radiology,

              (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND pc.Employee_ID=$employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Pharmacy,

              (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND pc.Employee_ID=$employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Procedur
              ") or die(mysqli_error($conn));
              while($count_spefic = mysqli_fetch_assoc($select_checking_type)){
                $lab_total = $count_spefic['Laboratory'];
                $rad_total = $count_spefic['Radiology'];
                $pham_total = $count_spefic['Pharmacy'];
                $proc_total = $count_spefic['Procedur'];

                $lab_total_count = $lab_total_count + $lab_total;
                $rad_total_count = $rad_total_count + $rad_total;
                $pham_total_count = $pham_total_count + $pham_total;
                $proc_total_count = $proc_total_count + $proc_total;
              }

              $total_patient = $lab_total_count + $rad_total_count + $pham_total_count + $proc_total_count;

              $result_patient_no = mysqli_query($conn,"SELECT COUNT(rg.Registration_ID) AS numberOfPatients FROM tbl_patient_registration as rg, tbl_payment_cache as pc WHERE pc.Registration_ID = rg.Registration_ID $filter2 AND pc.Employee_ID = $employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'") or die(mysqli_error($conn));


                $patient_no_number = mysqli_fetch_assoc($result_patient_no)['numberOfPatients'];

                $empSN ++;
                $htm .= "<tr><td>" . ($empSN) . "</td>";
                $htm .= "<td style='text-align:left'>" . $employeeName . "</b></td>";
                $htm .= "<td>$lab_total_count </td>";
                $htm .= "<td>$rad_total_count </td>";
                $htm .= "<td>$pham_total_count</td>";
                $htm .= "<td>$proc_total_count</td>";
                $htm .= "<td>$patient_no_number</td>";
                $htm .= "<td style='text-align:center'>" . number_format($total_patient) . "</b></td></tr>";

        }
          $htm .= "</table>";
        include("MPDF/mpdf.php");

        $mpdf = new mPDF('s', 'A4-L');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
        // LOAD a stylesheet
        $stylesheet = file_get_contents('patient_file.css');
        $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
        $mpdf->WriteHTML($htm, 2);
        $mpdf->Output();
        ?>
