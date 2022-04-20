
<?php include ("includes/connection.php");
$employee_id = $_SESSION['employee_id'];
$start_data = $_GET['start_date'];
$end_date = $_GET['end_date'];
$today = Date("Y-m-d");
$sql = "SELECT DISTINCT pc.Payment_Cache_ID,pc.Registration_ID,ilc.Item_ID,ilc.Approved_By,ilc.Approval_Date_Time FROM tbl_payment_cache pc JOIN `tbl_item_list_cache` ilc ON pc.Payment_Cache_ID = ilc.Payment_Cache_ID WHERE ilc.Category='indirect cash' AND DATE(ilc.Approval_Date_Time) BETWEEN '$start_date' AND '$end_date' GROUP BY  ilc.Payment_Cache_ID  DESC LIMIT 40";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$i = 1;
$data = '<table style="width:100%; background:#fff">';
while ($row = mysqli_fetch_assoc($result)) {
    $registration_id = $row['Registration_ID'];
    $approve_date_and_time = $row['Approval_Date_Time'];
    $patient_name = getPAtientName($row['Registration_ID']);
    $approver_name = getApprovalName($row['Approved_By']);
    $item_name = getItems($row['Item_ID'], $row['Payment_Cache_ID']);
    $j = 0;
    $data.= '<tr><td style="text-align:center;width:5%;">' . $i++ . '</td><td style="width:15.2%">' . $patient_name . '</td><td style="text-align:Center;width:7.2%;">' . $registration_id . '</td><td style="text-align:center;width:15.2%;">' . $approve_date_and_time . '</td><td style="width:44.3%">';
    $j = 1;
    foreach ($item_name as $item) {
        $data.= '<span><b>' . $j++ . '.  </b></span><span style="border-bottom:1px solid grey;padding-left:3px;">' . $item . '</span><br/>';
    }
    $data.= '</td><td>' . $approver_name . '</td></tr>';
}
$data.= '</table>';
function getPAtientName($registration_id) {
    $sql = "SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID='$registration_id'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
    }
    return $Patient_Name;
}
function getApprovalName($Approved_By) {
    $sql = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Approved_By'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
    }
    return $Employee_Name;
}
function getItems($item_id, $payment_cache_id) {
    $sql = "SELECT Item_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID='$payment_cache_id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $dat = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $item_name = getItemName($row['Item_ID']);
        array_push($dat, $item_name);
    }
    return $dat;
}
function getItemName($item_id) {
    $sql = "SELECT Product_Name FROM tbl_items WHERE Item_ID='$item_id'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
    }
    return $Product_Name;
}
echo $data;
