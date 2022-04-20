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

if (!empty($filterOptions)) {
    if ($filterOptions == 'today') {
        $filter = " AND DATE(Transaction_Date_And_Time)=DATE(NOW()) AND ilc.Status='active'";
    } elseif ($filterOptions == 'yesterday') {
        $filter = " AND DATE(Transaction_Date_And_Time) = CURDATE()-INTERVAL 1 DAY AND ilc.Status='active' ";
    } elseif ($filterOptions == 'fromyesterday') {
        $filter = " AND DATE(Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())  AND ilc.Status='active'";
    } elseif ($filterOptions == 'daterange') {
        $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $start_date_op . "' AND '" . $end_date_op . "' AND ilc.Status='active'";
    }

    if (!empty($consultType) && $consultType != "All") {
        //$filter .=" AND ilc.Check_In_Type='$consultType'";
        $chktype = array($consultType);
    }

    if (!empty($patientLocation) && $patientLocation != "All") {
        $filter .=" AND ilc.Sub_Department_ID='$patientLocation'";
    }

    if (!empty($Ward_id)) {
        $filter .=" AND ad.Hospital_Ward_ID='$Ward_id'";
    }

    // die($filter);
    $group_by = '';

    if (!empty($groupBy)) {
        //$filter .=" AND ilc.Sub_Department_ID='$patientLocation'";
        $group_by = " GROUP BY $groupBy";
    }

    if ($consultType == "All") {
        $chktype = array('Pharmacy', 'Laboratory', 'Radiology', 'Procedure');
    }
    //$filter .=" AND ilc.Check_In_Type='$consultType'";

    foreach ($chktype as $value) {

        $sql = "SELECT s.Sub_Department_ID,Sub_Department_Name,hw.Hospital_Ward_Name,Patient_Name,Guarantor_Name,pr.Registration_ID,pr.Region,pr.District,Date_Of_Birth,Registration_Date_And_Time,pr.Gender FROM tbl_item_list_cache ilc 
             JOIN tbl_sub_department s ON s.Sub_Department_ID=ilc.Sub_Department_ID 
             JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID
             JOIN tbl_patient_registration pr ON pr.Registration_ID = pc.Registration_ID
             JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
             JOIN tbl_check_in_details ch ON ch.consultation_ID = pc.consultation_id 
             JOIN tbl_admission ad ON ad.Admision_ID = ch.Admission_ID
             JOIN tbl_hospital_ward hw ON hw.Hospital_Ward_ID = ad.Hospital_Ward_ID
             WHERE Round_ID IS NOT NULL AND pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit') AND ilc.Check_In_Type='$value' $filter $group_by
             ";

        // echo $sql;exit;

        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        $count = 1;
        $ppil = array(-1);

        echo "<div style='text-transform: uppercase;margin:10px 0px 10px 0px;width:100%;text-align:left;font-family: times;font-size: small;font-weight: bold;background-color:#037DB0;padding:4px;color:white'>" . $value . "<span style='float:right'> </span></div>";
        echo '<center><table width =100% border=0 id="patientslist" class="display">';
        echo "<thead>
	            <tr>
			    <th style='text-align: left;width:1%'><b>SN</b></th>
			    <th style='text-align: left;width:20%'><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</b></th>
			    <th style='text-align: center;width:10%' ><b>REG #</b></th>
			    <th style='text-align: left;width:10%' ><b>REGION</b></th>
			    <th style='text-align: left;width:10%' ><b>DISTRICT</b></th>
			    <th style='text-align: left;width:6%' ><b>GENDER</b></th>
			    <th style='text-align: left;width:10%' ><b>AGE</b></th>
                            <th style='text-align: left;width:7%' ><b>DEPARTMENT</b></th>
                            <th style='text-align: left;width:7%' ><b>WARD</b></th>
                            <th style='text-align: left;width:26%' ><b>ITEMS ORDERED</b></th>
		     </tr>
           </thead>";

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_array($result)) {
                $patientName = $row['Patient_Name'];
                $Registration_ID = $row['Registration_ID'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Gender = $row['Gender'];
                $dob = $row['Date_Of_Birth'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
                $Sub_Department_Name = $row['Sub_Department_Name'];//
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
                     WHERE Round_ID IS NOT NULL AND  pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit') $filter AND ilc.Check_In_Type='$value' $filterByDepartment  AND  pc.Registration_ID='$Registration_ID' AND Payment_Item_Cache_List_ID NOT IN (" . implode(',', $ppil) . ")";

                //die($select_items);
                $selected_items = mysqli_query($conn,$select_items) or die(mysqli_error($conn));

                $products = '';
                $numberOfItem = mysqli_num_rows($selected_items);
                $track = 1;
                while ($rowdata = mysqli_fetch_array($selected_items)) {
                    $Product_Name = $rowdata['Product_Name'];
                    $dateordered=$rowdata['Transaction_Date_And_Time'];
                    $ppil[] = $rowdata['Payment_Item_Cache_List_ID'];

                    if ($numberOfItem == 1) {
                        $products = $Product_Name. '&nbsp; ( '.$dateordered.' ) ';
                    } else {
                        //if ($track < $numberOfItem) {
                            $products .='<div style="display:block;border-bottom:1px solid #ccc;">'.$Product_Name . '&nbsp; ( '.$dateordered.' ) </div>';
//                        } else {
//                            $products .=$Product_Name . '.';
//                        }
                    }

                    $track++;
                }

                //End of Items

                if (!empty($products)) {
                    echo "<tr><td style='text-align:left;'>" . $count++ . "</td>";
                    echo "<td style='text-align:left; '>" . $patientName . "</td>";
                    echo "<td style='text-align:center; '>" . $Registration_ID . "</td>";
                    echo "<td style='text-align:left; '>" . $Region . "</td>";
                    echo "<td style='text-align:left; '>" . $District . "</td>";
                    echo "<td style='text-align:left; '>" . $Gender . "</td>";
                    echo "<td style='text-align:left; '>" . $age . "</td>";
                    echo "<td style='text-align:left; '>" . $Sub_Department_Name . "</td>";
                    echo "<td style='text-align:left; '>" . $Hospital_Ward_Name . "</td>";
                    echo "<td style='text-align:left; '>" . $products . "</td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='10' style='text-align:center; '>There is no orders available at the moment.</td></tr>";
        }

        echo "</table></center>";
    }
} 

