<?php

session_start();
include("./includes/connection.php");
$Control = 'yes';

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    header("Location: ./index.php?InvalidPrivilege=yes");
}

$is_send_to_cashier = false;
$filter = " AND Employee_ID = '$Employee_ID'";
$table = "tbl_reception_items_list_cache";
$filterId = "Registration_ID = '$Registration_ID'";
if (isset($_GET['src']) && $_GET['src'] == 'send_to_cash') {
    $is_send_to_cashier = true;
    $filter = '';
    $table = "tbl_patient_payment_item_list_cache";
    $gt = mysqli_query($conn,"SELECT Patient_Payment_Cache_ID FROM tbl_patient_payments_cache WHERE Registration_ID = '$Registration_ID' AND Transaction_status = 'submitted' ORDER BY Patient_Payment_Cache_ID DESC LIMIT 1") or die(mysqli_error($conn));

    $rs2 = $data = mysqli_fetch_array($gt);
    $filterId = "Patient_Payment_Cache_ID='" . $rs2['Patient_Payment_Cache_ID'] . "'";
}

if (isset($_GET['P_Type'])) {

    //verify items
    $select = mysqli_query($conn,"select Price, Quantity from $table where $filterId $filter") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            if ($row['Price'] < 1) {
                $Control = 'no';
            }

            if ($row['Quantity'] < 1) {
                $Control = 'no';
            }
        }
    }
} else {
    $select = mysqli_query($conn,"select Patient_Payment_Cache_ID from tbl_patient_payments_cache
								where Registration_ID = '$Registration_ID' and
								Employee_ID = '$Employee_ID' and
								Transaction_status = 'pending' order by Patient_Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($data = mysqli_fetch_array($select)) {
            $Patient_Payment_Cache_ID = $data['Patient_Payment_Cache_ID'];
        }
    } else {
        $Patient_Payment_Cache_ID = 0;
    }

    //verify items
    $verify = mysqli_query($conn,"select Price, Quantity from tbl_patient_payment_item_list_cache where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($verify);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($verify)) {
            if ($row['Price'] < 1) {
                $Control = 'no';
            }

            if ($row['Quantity'] < 1) {
                $Control = 'no';
            }
        }
    }
}
echo $Control;
?>