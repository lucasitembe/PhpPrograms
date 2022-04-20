<?php
include("../includes/connection.php");

$filterOptions = filter_input(INPUT_GET, 'filterOptions');
$start_date_op = filter_input(INPUT_GET, 'start_date_op');
$end_date_op = filter_input(INPUT_GET, 'end_date_op');
$consultType = filter_input(INPUT_GET, 'consultType');
$patientLocation = filter_input(INPUT_GET, 'patientLocation');
$groupBy = filter_input(INPUT_GET, 'groupBy');
$Ward_id = filter_input(INPUT_GET, 'Ward_id');

$filter = '';
$optionfilter = '';
$otherFilter = '';
if (!empty($filterOptions)) {
    if ($filterOptions == 'today') {
        $optionfilter = 'TODAY';
        $filter = " AND DATE(Transaction_Date_And_Time)=DATE(NOW()) AND ilc.Status='active'";
    } elseif ($filterOptions == 'yesterday') {
        $optionfilter = 'YESTERDAY';
        $filter = " AND DATE(Transaction_Date_And_Time) = CURDATE()-INTERVAL 1 DAY AND ilc.Status='active' ";
    } elseif ($filterOptions == 'fromyesterday') {
        $optionfilter = 'FROM YESTERDAY';
        $filter = " AND DATE(Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())  AND ilc.Status='active'";
    } elseif ($filterOptions == 'daterange') {
        $optionfilter = "FROM " . $start_date_op . " TO " . $end_date_op . "";
        $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $start_date_op . "' AND '" . $end_date_op . "' AND ilc.Status='active'";
    }

    if (!empty($consultType) && $consultType != "All") {
        $chktype = array($consultType);
        //$otherFilter = " AND ilc.Check_In_Type='$consultType'";
    }

    if (!empty($patientLocation) && $patientLocation != "All") {
        $filter .=" AND ilc.Sub_Department_ID='$patientLocation'";
        $otherFilter = " AND ilc.Sub_Department_ID='$patientLocation'";
    }

    if (!empty($Ward_id)) {
        $filter .=" AND ad.Hospital_Ward_ID='$Ward_id'";
        $otherFilter .=" AND ad.Hospital_Ward_ID='$Ward_id'";
    }

    $group_by = '';

    if (!empty($groupBy)) {
        //$filter .=" AND ilc.Sub_Department_ID='$patientLocation'";
        $group_by = " GROUP BY $groupBy";
    }

    //die($group_by);

    if ($consultType == "All") {
        $chktype = array('Pharmacy', 'Laboratory', 'Radiology', 'Procedure');
    }

    $htm = "<table style=' border: 0 !important;width:100%' class='nobordertable'>  <tr><td colspan='9'><img src='branchBanner/branchBanner1.png' width='100%' /></td></tr>
                    <tr><td colspan='9' style='text-align: center;'><b>PATIENT ORDERED ITEMS</b></td></tr>
                    <tr><td colspan='9' style='text-align: center;'><b>" . $optionfilter . "</b></td></tr>
	            </table>";
    $dateRages = array();
    $sqlDates = "SELECT DATE(Transaction_Date_And_Time) AS dateRange FROM tbl_item_list_cache ilc 
             JOIN tbl_sub_department s ON s.Sub_Department_ID=ilc.Sub_Department_ID 
             JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID
             JOIN tbl_patient_registration pr ON pr.Registration_ID = pc.Registration_ID
             JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
             JOIN tbl_check_in_details ch ON ch.consultation_ID = pc.consultation_id 
             JOIN tbl_admission ad ON ad.Admision_ID = ch.Admission_ID
             JOIN tbl_hospital_ward hw ON hw.Hospital_Ward_ID = ad.Hospital_Ward_ID
             WHERE  Round_ID IS NOT NULL AND  pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit') $filter  GROUP BY DATE(Transaction_Date_And_Time)
             ";
    $getDate = mysqli_query($conn,$sqlDates) or die(mysqli_error($conn));
    while ($rowDate = mysqli_fetch_array($getDate)) {
        $dateRages[] = $rowDate['dateRange'];
    }
    foreach ($dateRages as $value) {

        $thisDate = date('F jS Y l', strtotime($value));

        $htm .= "<div style='text-transform: uppercase;margin:10px 0px 10px 0px;width:100%;text-align:left;font-family: times;font-size: small;font-weight: bold;background-color:#ccc;padding:4px'>" . $thisDate . "<span style='float:right'> </span></div>";

        foreach ($chktype as $chktypeValue) {

            $sql = "SELECT s.Sub_Department_ID,Sub_Department_Name,hw.Hospital_Ward_Name,Patient_Name,Guarantor_Name,pr.Registration_ID,pr.Region,pr.District,Date_Of_Birth,Registration_Date_And_Time,pr.Gender FROM tbl_item_list_cache ilc 
             JOIN tbl_sub_department s ON s.Sub_Department_ID=ilc.Sub_Department_ID 
             JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID
             JOIN tbl_patient_registration pr ON pr.Registration_ID = pc.Registration_ID
             JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
             JOIN tbl_check_in_details ch ON ch.consultation_ID = pc.consultation_id 
             JOIN tbl_admission ad ON ad.Admision_ID = ch.Admission_ID
             JOIN tbl_hospital_ward hw ON hw.Hospital_Ward_ID = ad.Hospital_Ward_ID
             WHERE Round_ID IS NOT NULL AND pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit') AND  ilc.Status='active' AND  DATE(Transaction_Date_And_Time) = '$value' AND ilc.Check_In_Type='$chktypeValue' $otherFilter  $group_by
             ";

            

            $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            $count = 1;
            $ppil = array(-1);

            if (mysqli_num_rows($result) > 0) {

                $htm .= "<div style='text-transform: uppercase;margin:10px 0px 10px 0px;width:100%;text-align:left;font-family: times;font-size: small;font-weight: bold;padding:4px;border:1px solid #ccc'>" . $chktypeValue . "<span style='float:right'> </span></div>";


                $htm .= '<table >';
                $htm .= "        <tr>
			    <td style='text-align: left;width:1%'><b>SN</b></td>
			    <td style='text-align: left;width:25%'><b>&nbsp;&nbsp;&nbsp;&nbsp;NAME</b></td>
			    <td style='text-align: center;width:10%' ><b>REG #</b></td>
			    <td style='text-align: left;width:6%' ><b>GENDER</b></td>
			    <td style='text-align: left;width:10%' ><b>AGE</b></td>
                            <td style='text-align: left;width:7%' ><b>DEPARTMENT</b></td>
                            <td style='text-align: left;width:7%' ><b>WARD</b></td>
                            <td style='text-align: left;width:51%' ><b>ITEMS ORDERED</b></td>
		     </tr>
           ";

                while ($row = mysqli_fetch_array($result)) {
                    $patientName = $row['Patient_Name'];
                    $Registration_ID = $row['Registration_ID'];
                    $Region = $row['Region'];
                    $District = $row['District'];
                    $Gender = $row['Gender'];
                    $dob = $row['Date_Of_Birth'];
                    $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
                    $Sub_Department_Name = $row['Sub_Department_Name'];
                    $Sub_Department_ID = $row['Sub_Department_ID'];
                    $Hospital_Ward_Name = $row['Hospital_Ward_Name'];

                    //these codes are here to determine the age of the patient
                    $date1 = new DateTime(date('Y-m-d'));
                    $date2 = new DateTime($dob);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";

                    $filterByDepartment = " AND ilc.Sub_Department_ID='$Sub_Department_ID'";
                    if (!empty($groupBy)) {
                        $Sub_Department_Name = '';
                        $filterByDepartment = "";
                    }

                    $select_items = "SELECT Product_Name,Payment_Item_Cache_List_ID,Transaction_Date_And_Time FROM tbl_item_list_cache ilc 
                     JOIN tbl_items i ON i.Item_ID=ilc.Item_ID 
                     JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID 
                     JOIN tbl_check_in_details ch ON ch.consultation_ID = pc.consultation_id 
                     JOIN tbl_admission ad ON ad.Admision_ID = ch.Admission_ID
                     JOIN tbl_hospital_ward hw ON hw.Hospital_Ward_ID = ad.Hospital_Ward_ID
                     WHERE Round_ID IS NOT NULL AND pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit') AND  ilc.Status='active' AND  DATE(Transaction_Date_And_Time) = '$value' AND ilc.Check_In_Type='$chktypeValue'  AND pc.Registration_ID='$Registration_ID' AND Payment_Item_Cache_List_ID NOT IN (" . implode(',', $ppil) . ") $otherFilter $filterByDepartment";

                    //die($select_items);
                    $selected_items = mysqli_query($conn,$select_items) or die(mysqli_error($conn));
                     
                    $products = '<table style="width:100%" width="100%" border="0"  class="nobordertable">';
                    $numberOfItem = mysqli_num_rows($selected_items);
                    $track = 1;
                    
                    $hasdata=false;
                    while ($rowdata = mysqli_fetch_array($selected_items)) {
                        $Product_Name = $rowdata['Product_Name'];
                        $dateordered=$rowdata['Transaction_Date_And_Time'];
                        $ppil[] = $rowdata['Payment_Item_Cache_List_ID'];

                        if ($numberOfItem == 1) {
                            $products .= '<tr><td>'.$Product_Name. '&nbsp; ( '.$dateordered.' ) </td></tr>';
                             $hasdata=true;
                        } else {
                             $hasdata=true;
                            if ($track < $numberOfItem) {
                                 $products .="<tr><td  style='width:100%;'>".$Product_Name. "&nbsp; ( ".$dateordered." ) ,</td></tr>";
                            
                            } else {
                                $products .="<tr><td  style='width:100%;'>".$Product_Name. "&nbsp; ( ".$dateordered." ) .</td></tr>";
                            
                            }
                        }

                        $track++;
                    }
                    
                     $products .= '</table>';

                    //End of Items

                    if ($hasdata) {
                        $htm .= "<tr><td style='text-align:left;'>" . $count++ . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $patientName . "</td>";
                        $htm .= "<td style='text-align:center; '>" . $Registration_ID . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $Gender . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $age . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $Sub_Department_Name . "</td>";
                         $htm .= "<td style='text-align:left; '>" . $Hospital_Ward_Name . "</td>";
                        $htm .= "<td style='text-align:left; '>" . $products . "</td>";
                        $htm .= "</tr>";
                    }
                }
            }

            $htm .= "</table>";
        }
    }



//     else {
//        $htm .= "<tr><td colspan='9' style='text-align:center; '>There is no orders available at the moment.</td></tr>";
//    }
}



//
include("MPDF/mpdf.php");

$mpdf = new mPDF('', 'A4-L');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
