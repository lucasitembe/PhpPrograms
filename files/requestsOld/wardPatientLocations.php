<?php
include("../includes/connection.php");

$filterOptions = filter_input(INPUT_GET, 'filterOptions');
$start_date_op = filter_input(INPUT_GET, 'start_date_op');
$end_date_op = filter_input(INPUT_GET, 'end_date_op');
$consultType = filter_input(INPUT_GET, 'consultType');
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
        $filter .=" AND ilc.Check_In_Type='$consultType'";
    }

    if (!empty($Ward_id)) {
        $filter .=" AND ad.Hospital_Ward_ID='$Ward_id'";
    }

    $sql = "SELECT s.Sub_Department_ID,Sub_Department_Name FROM tbl_sub_department s 
             JOIN tbl_item_list_cache ilc ON s.Sub_Department_ID=ilc.Sub_Department_ID 
             JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID
             JOIN tbl_check_in_details ch ON ch.consultation_ID = pc.consultation_id 
             JOIN tbl_admission ad ON ad.Admision_ID = ch.Admission_ID
             WHERE Round_ID IS NOT NULL AND  pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit') $filter GROUP BY s.Sub_Department_ID
             ";
    $result = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result) > 0) {
        $options = '<option value="">Select location</option>
                    <option >All</option>';
        while ($row = mysql_fetch_array($result)) {
            $options.="<option value='" . $row['Sub_Department_ID'] . "'>" . strtoupper($row['Sub_Department_Name']) . "</option>";
        }

        echo $options;
    } else {
        echo '<option value="">There is no orders at the moment.</option>';
    }
} else {
    echo '<h4 align="text-align:center">Invalid data sent.Try again.</h4>';
}
