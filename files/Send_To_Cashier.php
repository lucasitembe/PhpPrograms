<?php
include("./includes/connection.php");
session_start();
//get posted values
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}
if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = $_GET['Transaction_Type'];
} else {
    $Transaction_Type = '';
}
if (isset($_GET['Sub_Department_Name'])) {
    $Sub_Department_Name = $_GET['Sub_Department_Name'];
} else {
    $Sub_Department_Name = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

if (isset($_GET['Check_In_Type'])) {
    $Check_In_Type = $_GET['Check_In_Type'];
} else {
    $Check_In_Type = "";
}

if (isset($_GET['sub_department_id'])) {
    $sub_department_id = $_GET['sub_department_id'];
} else {
    $sub_department_id = "";
}

if (isset($_GET['selectedItem'])) {
    $selectedItem = $_GET['selectedItem'];
} else {
    $selectedItem = "";
}

$update_count = 0;
foreach ($selectedItem as $selectedItem) {
    $selectedItemid = $selectedItem['id'];
    $dose_qty = $selectedItem['doseqty'];
    $dispensed_qty = $selectedItem['dispensedqty'];

    $update = " UPDATE tbl_item_list_cache SET status = 'approved', dose = '$dose_qty', Edited_Quantity = '$dispensed_qty', 
                Sub_Department_ID = (select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name') where
                (status = 'active' or status = 'partial dispensed') and Transaction_Type = '$Transaction_Type'
                and  Check_In_Type='$Check_In_Type' and Payment_Item_Cache_List_ID = '$selectedItemid'";

    if (mysqli_query($conn, $update)) {
        $update_count++;
    }
}


    $sql_select = " SELECT ilc.Item_ID,ilc.Edited_Quantity,ilc.Quantity,ilc.Check_In_Type,ilc.status,it.Product_Name FROM 
                    tbl_item_list_cache ilc,tbl_items it WHERE ilc.Item_ID=it.Item_ID AND Payment_Cache_ID = '$Payment_Cache_ID' and
                    ilc.Transaction_Type = '$Transaction_Type' AND ilc.status!='removed' AND ilc.Check_In_Type='$Check_In_Type'";
    $sql_select_result = mysqli_query($conn, $sql_select) or die(mysqli_error($conn));
    $count = 0;

    if (mysqli_num_rows($sql_select_result) > 0) {
        while ($iterm_id_rows = mysqli_fetch_assoc($sql_select_result)) {
            $Item_ID = $iterm_id_rows['Item_ID'];
            $Quantity = $iterm_id_rows['Edited_Quantity'];
            $Quantity2 = $iterm_id_rows['Quantity'];
            $status = $iterm_id_rows['status'];
            $Product_Name = $iterm_id_rows['Product_Name'];

            if ($Quantity == 0 && $Quantity2 == 0) $count++;
                $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
                $sql_get_balance = mysqli_query($conn, "SELECT Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and
                                                        Sub_Department_ID = '$Sub_Department_ID' AND Item_Balance<'$Quantity'") or 
                                                        die(mysqli_error($conn));

                if (mysqli_num_rows($sql_get_balance) > 0) {
                    $balance_row = mysqli_fetch_assoc($sql_get_balance);
                    $Item_Balance = $balance_row['Item_Balance'];
                    $count++;
                }
                }
            } else {
            }
    if ($update_count > 0) {
        echo "Successfull";
    } else {
        echo "fail";
    }


?>