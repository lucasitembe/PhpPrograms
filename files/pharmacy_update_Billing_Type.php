<?php

@session_start();
include("./includes/connection.php");
require_once './includes/ehms.function.inc.php';

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if (isset($$_GET['Guarantor_Name'])) {
    $Guarantor_Name = $_GET['Guarantor_Name'];
} else {
    $Guarantor_Name = '';
}

if (isset($$_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}


$select_Transaction_Items = mysqli_query($conn,
        "select Billing_Type
				    from tbl_pharmacy_items_list_cache alc
				    where alc.Employee_ID = '$Employee_ID' and
				    Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));

$no = mysqli_num_rows($select_Transaction_Items);

$sql_select_auto_item_update_api_result=mysqli_query($conn,"SELECT auto_item_update_api FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_auto_item_update_api_result)>0){
  $auto_item_update_api=mysqli_fetch_assoc($sql_select_auto_item_update_api_result)['auto_item_update_api'];  
}else{
  $auto_item_update_api=0;  
}
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_Transaction_Items)) {
        $Billing_Type = $row['Billing_Type'];
    }
    echo '<option selected="selected">' . $Billing_Type . '</option>';
} else {
    $can_login_to_high_privileges_department = $_SESSION['userinfo']['can_login_to_high_privileges_department'];
    if (strtolower($Guarantor_Name) == 'cash' || strtolower(getPaymentMethod($Sponsor_ID)) == 'cash'||($can_login_to_high_privileges_department=="yes"&&$auto_item_update_api==1)) {
        echo '<option selected="selected">Outpatient Cash</option>';
    } else {
        echo '<option>Outpatient Cash</option>';
        echo '<option selected="selected">Outpatient Credit</option>';
    }
}
?>