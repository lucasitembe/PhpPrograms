<?php

include './includes/connection.php';

$filter = '';
$rad_Type = 'All';
$fromDate = DATE('Y-m-d H:m:s');
$toDate = DATE('Y-m-d H:m:s');

// if (isset($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $Item_ID = $_GET['Item_ID'];
    $employee_id = $_GET['employee_id'];
    $filter .= " AND ilc.ServedDateTime BETWEEN '$fromDate' AND '$toDate'";

// }

if($employee_id != 'All'){
    $empType =" AND po.Employee_ID = '$employee_id'";
}else{
    $empType = '';
}

$selectAllRadEmployee_qry = mysqli_query($conn, "SELECT po.Employee_ID, em.Employee_Name, em.Employee_Job_Code FROM tbl_employee em, tbl_post_operative_notes po WHERE em.Employee_ID = po.Employee_ID $empType GROUP BY po.Employee_ID") or die(mysqli_error($conn));
$emp_num = 1;
while ($emp = mysqli_fetch_array($selectAllRadEmployee_qry)) {
    $empname = $emp['Employee_Name'];
    $employee_job_code = $emp['Employee_Job_Code'];
    $empid = $emp['Employee_ID'];

    $jobCode = explode('/', $employee_job_code);
    $filterRad = '';

    // print_r($jobCode);

   // echo ($check_has_patient) . '<br/>';
    $check_has_patient_result = mysqli_query($conn,"SELECT ilc.Payment_Item_Cache_List_ID, ilc.Patient_Payment_ID, ilc.ServedDateTime FROM tbl_item_list_cache ilc, tbl_payment_cache pp WHERE pp.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Status='served' AND ilc.ServedBy = '$empid' AND ilc.Payment_Item_Cache_List_ID IN(SELECT Payment_Item_Cache_List_ID FROM tbl_post_operative_notes WHERE Post_Operative_Status = 'Saved') $filter ORDER BY ilc.ServedDateTime ASC") or die(mysqli_error($conn));
    $pat_no = mysqli_num_rows($check_has_patient_result);
    if ($pat_no > 0) {
        echo "<div class='daterange'>".$emp_num.". " . $empname . "/<span> $employee_job_code </span><span style='float:right'>PATIENT NO. $pat_no </span></div>";
        echo '<center><table width =100% border=0 id="patientspecimenCollected" class="display">';
        echo "<thead>
        <tr>
                <th  width='3%'>SN</th>
                <th style='text-align: left;' width='20%'><b>PATIENT NAME</th>
                <th style='text-align: left;'>REG #</th>
                <th style='text-align: left;'>SPONSOR</th>
                <th style='text-align: left;' width='20%'>SURGERY</th>
                <th style='text-align: left;' width='10%'>SURGERY DATE</th>
                <th style='text-align: left;' width='20%'>REPORT</th>
    </tr>
         </thead>";

        $totalPPP = 0;
        $count = 1;
        while($details = mysqli_fetch_array($check_has_patient_result)){
            $datee = $details['ServedDateTime'];
            $thisDate = date('d, M Y', strtotime($datee)) . '';
            $Payment_Item_Cache_List_ID = $details['Payment_Item_Cache_List_ID'];
            $Patient_Payment_ID = $details['Patient_Payment_ID'];

            $notIn = array('-1');


            $select_data_patient_result = mysqli_query($conn,"SELECT pp.Registration_ID, i.Product_Name, pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, sp.Guarantor_Name FROM tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_payment_cache pp, tbl_sponsor sp, tbl_items i WHERE  pp.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Item_ID=i.Item_ID AND pr.Registration_ID = pp.Registration_ID AND sp.Sponsor_ID = pp.Sponsor_ID AND ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
            if (mysqli_num_rows($select_data_patient_result) > 0) {

                while ($row = mysqli_fetch_array($select_data_patient_result)) {
                    $Registration_ID = $row['Registration_ID'];
                    $Patient_Name = $row['Patient_Name'];
                    $Registration_ID = $row['Registration_ID'];
                    $Guarantor_Name = $row['Guarantor_Name'];
                    $Gender = $row['Gender'];
                    $dob = $row['Date_Of_Birth'];
                    $Product_Name = $row['Product_Name'];   
  

                        echo "<tr><td>" . $count . "</td>";
                        echo "<td style='text-align:left; '>" . $Patient_Name . "</td>";
                        echo "<td style='text-align:left; '>" . $Registration_ID . "</td>";
                        echo "<td style='text-align:left; '>" . $Guarantor_Name . "</td>";
                        echo "<td style='text-align:left; '>" . $Product_Name . "</td>";
                        echo "<td style='text-align:left; '>" . $thisDate . "</td>";
                        echo "<td><a href='previewpostoperativereport.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$Patient_Payment_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&PreviewPostOperativeReport=PreviewPostOperativeReportThisPage' class='art-button-green' target='_blank'>POST OPERATIVE REPORT</a></td>"; 
                        echo " </tr>";

                $count++;
                }
            }
        }
    $emp_num++;
    echo "</table></center><br/><br/>";
    }
}
