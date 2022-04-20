<?php

include './includes/connection.php';

$filter = '';
$empType = " Employee_Job_Code LIKE '%Sonographer%' || Employee_Job_Code LIKE '%Radiologist%'";
$rad_Type = 'All';
$fromDate = DATE('Y-m-d H:m:s');
$toDate = DATE('Y-m-d H:m:s');

if (isset($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $rad_emp_ID = $_GET['rad_emp_ID'];
    $rad_Type = $_GET['rad_Type'];
    $filter = "";
    if ($rad_emp_ID != 'All') {
        if ($rad_Type == 'Sonographer') {
            $filter .=" AND rp.Sonographer_ID='$rad_emp_ID'";
        }if ($rad_Type == 'Radiologist') {
            $filter .=" AND rp.Radiologist_ID='$rad_emp_ID'";
        }

        $empType = " Employee_ID='$rad_emp_ID'";
    }

    if ($rad_Type != 'All') {
        $empType = "Employee_Job_Code LIKE '%$rad_Type%' AND Employee_ID='$rad_emp_ID'";
        //$filter .=" AND e.Employee_Job_Code LIKE '%$rad_Type%'";
        $filterEmp = " AND e.Employee_Job_Code LIKE '%$rad_Type%'";
    }
}

$htm = "<table width ='100%'>
		    <tr><td style='text-align:center'>
			<img src='./branchBanner/branchBanner.png' width='100%'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>RADIOLOGY PERFORMANCE REPORT</b></span></td></tr>
                    <tr><td style='text-align: center;'><span><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($fromDate)) . "</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>" . date('j F, Y H:i:s', strtotime($toDate)) . "</b></td></tr>
                     <tr><td style='text-align: center;'><span><b>Radiology Type</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $rad_Type . "</b></td></tr>
        </table><br/>";

$selectAllRadEmployee = "SELECT Employee_ID, Employee_Name,Employee_Job_Code FROM tbl_employee WHERE $empType GROUP BY Employee_ID";
$selectAllRadEmployee_qry = mysqli_query($conn,$selectAllRadEmployee) or die(mysqli_error($conn));
$dataRange = returnBetweenDates($fromDate, $toDate);
while ($emp = mysqli_fetch_array($selectAllRadEmployee_qry)) {
    $empname = $emp['Employee_Name'];
    $employee_job_code = $emp['Employee_Job_Code'];
    $empid = $emp['Employee_ID'];

    $jobCode = explode('/', $employee_job_code);
    $filterRad = '';

    // print_r($jobCode);

    if (count($jobCode) > 0) {
        if ($rad_Type == 'Sonographer') {
            if (in_array('Sonographer', $jobCode)) {
                $filterRad = " AND rp.Sonographer_ID='$empid'";
            }
        } elseif ($rad_Type == 'Radiologist') {
            if (in_array('Radiologist', $jobCode)) {
                $filterRad = " AND rp.Radiologist_ID='$empid'";
            }
        } else {
            $filterRad = " AND (rp.Radiologist_ID='$empid' OR rp.Sonographer_ID='$empid')";
        }
    } elseif ($rad_Type != 'All') {
        if ($rad_Type == 'Sonographer') {
            $filterRad = " AND rp.Sonographer_ID='$empid'";
        } elseif ($rad_Type == 'Radiologist') {
            $filterRad = " AND rp.Radiologist_ID='$empid'";
        }
    } else {
        if ($employee_job_code == 'Sonographer') {
            $filterRad = " AND rp.Sonographer_ID='$empid'";
        } elseif ($employee_job_code == 'Radiologist') {
            $filterRad = " AND rp.Radiologist_ID='$empid'";
        }
    }

    $check_has_patient = "SELECT ilc.Consultant_ID FROM tbl_item_list_cache ilc JOIN tbl_radiology_patient_tests rp ON ilc.Payment_Item_Cache_List_ID=rp.Patient_Payment_Item_List_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID =pp.Registration_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_employee e ON e.Employee_ID=ilc.Consultant_ID WHERE  rp.Date_Time BETWEEN '$fromDate' AND '$toDate' $filterRad $filter";

    // $htm .= ($check_has_patient) . '<br/>';
    $check_has_patient_result = mysqli_query($conn,$check_has_patient) or die(mysqli_error($conn));
    $pat_no = mysqli_num_rows($check_has_patient_result);
    if ($pat_no > 0) {
        $htm .= "<div style='margin:10px 0px 10px 0px;width:100%;text-align:left;font-family: times;font-size: large;font-weight: bold;background-color:#ccc;padding:4px'>" . $empname . "/<span>&nbsp; $employee_job_code </span></div><br/>";


        $totalPPP = 0;
        foreach ($dataRange as $value) {
            $thisDate = date('d, M y', strtotime($value)) . '';

            $notIn = array('-1');

            $selectPatients = "SELECT pr.Registration_ID,Patient_Name,Gender,Date_Of_Birth,Guarantor_Name,Employee_Name AS Consultant,ilc.Consultant_ID FROM tbl_item_list_cache ilc JOIN tbl_radiology_patient_tests rp ON ilc.Payment_Item_Cache_List_ID=rp.Patient_Payment_Item_List_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID =pp.Registration_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_employee e ON e.Employee_ID=ilc.Consultant_ID WHERE   DATE(rp.Date_Time)='$value'  $filterRad $filter AND ilc.Payment_Item_Cache_List_ID NOT IN (" . implode(',', $notIn) . ")";

            $select_data_patient_result = mysqli_query($conn,$selectPatients) or die(mysqli_error($conn));
            $noOfPatient = mysqli_num_rows($select_data_patient_result);

            if (mysqli_num_rows($select_data_patient_result) > 0) {
                // $htm .= $selectPatients.'<br/>';
                $htm .= "<div style='margin:10px 0px 10px 0px;width:98%;text-align:left;font-family: times;font-size: large;font-weight:bold'>" . $thisDate . "</div>";
                $htm .= '<center><table width ="100%" border="0" id="patientspecimenCollected" class="display" >';
                $htm .= "<thead>
                            <tr><td colspan='6' style='width:100%;border-bottom:1px solid #000'></td></tr>
	                    <tr>
                                <th  width='4%'>SN</th>
                                <th style='text-align: left;' width='20%'><b>PATIENT NAME</th>
                                <th style='text-align: left;' >REG #</th>
                                <th style='text-align: left;' >SPONSOR</th>
                                <th style='text-align: left;' width='30%'>INVESTIGATION</th>
                                <th style='text-align: left;' width='20%'>TRANS DATE</th>
		            </tr>
                            <tr><td colspan='6' style='width:100%;border-bottom:1px solid #000'></td></tr>
                         </thead>";

                $count = 1;
                while ($row = mysqli_fetch_array($select_data_patient_result)) {
                    $registration_ID = $row['Registration_ID'];
                    $patientName = $row['Patient_Name'];
                    $Registration_ID = $row['Registration_ID'];
                    $Consultant = $row['Consultant'];
                    $Guarantor_Name = $row['Guarantor_Name'];
                    $Gender = $row['Gender'];
                    $dob = $row['Date_Of_Birth'];
                    $Consultant_ID = $row['Consultant_ID'];

                    //these codes are here to determine the age of the patient
                    $date1 = new DateTime(date('Y-m-d'));
                    $date2 = new DateTime($dob);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";

                    //get its patient items

                    $select_items = "SELECT Product_Name,Payment_Item_Cache_List_ID,Date_Time FROM tbl_item_list_cache ilc JOIN tbl_radiology_patient_tests rp ON ilc.Payment_Item_Cache_List_ID=rp.Patient_Payment_Item_List_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID WHERE  DATE(rp.Date_Time)='$value' $filterRad AND pp.Registration_ID='$Registration_ID' $filter  AND ilc.Payment_Item_Cache_List_ID NOT IN (" . implode(',', $notIn) . ")";

                    $selected_items = mysqli_query($conn,$select_items) or die(mysqli_error($conn));

                    $products = '';
                    $itemdates = '';
                    $numberOfItem = mysqli_num_rows($selected_items);
                    $track = 1;
                    while ($rowdata = mysqli_fetch_array($selected_items)) {
                        $Product_Name = $rowdata['Product_Name'];
                        $ppil = $rowdata['Payment_Item_Cache_List_ID'];
                        $Date_Time = $rowdata['Date_Time'];
                        $notIn[] = $ppil;

                        if ($numberOfItem == 1) {
                            $products = $Product_Name;
                            $itemdates = $Date_Time;
                        } else {
                            if ($track < $numberOfItem) {
                                $products .=$Product_Name . ',  ';
                                $itemdates .= $Date_Time . ',  ';
                            } else {
                                $products .=$Product_Name . '.';
                                $itemdates .= $Date_Time . '.';
                            }
                        }

                        $track++;
                    }

                    if (!empty($products)) {

                        $htm .= "<tr><td>" . $count++ . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $patientName . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $row['Registration_ID'] . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $Guarantor_Name . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $products . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $itemdates . "</td>";

                        $htm .= " </tr>";
                        $htm .="<tr><td colspan='6' style='width:100%;border-bottom:1px solid #000'></td></tr>";

                        $totalPPP ++;
                    }
                }
                $htm .= "</table></center><br/><br/>";
            }
        }

        $htm .= "<div style='margin:0px 0px 10px 0px;width:100%;text-align:right;font-family: times;font-size: small;font-weight: bold;border:2px solid #ccc'>TOTAL PATIENT: " . number_format($totalPPP) . "<span style='float:right'></span></div><br/>";
    }
}

//function between
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
$mpdf = new mPDF('', 'A4');

$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($htm, 2);

$mpdf->Output('mpdf.pdf', 'I');
exit;
