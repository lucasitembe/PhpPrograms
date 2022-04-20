<?php
error_reporting(!E_NOTICE);
@session_start();
include("./includes/connection.php");
$data = '';
 if (isset($_GET['section']) &&  $_GET['section'] != 'Pharmacy') {
     $Sub_Department_ID =  $_SESSION['Sub_Department_ID'];
 }else{
     if(isset($_SESSION['Pharmacy_ID'])){
         $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
     }
 }
 
// $_POST['getItemByConsType']=true;
// $_POST['consultationType']='Laboratory';
 //getItemByConsType=&consultationType=Laboratory

if (isset($_GET['consultationType']) && isset($_GET['search_word']) && !empty($_GET['search_word'])) {
    $search_items = '';
    $search_word = '';

    if (isset($_GET['search_word']) && !empty($_GET['search_word'])) {
        $search_word = "  AND Product_Name LIKE '%" . $_GET['search_word'] . "%'";
    }



    if (isset($_GET['type']) && $_GET['type'] == 'left') {
        $search_items = mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Consultation_Type !='" . $_GET['consultationType'] . "' $search_word LIMIT 100") or die(mysqli_error($conn));
        if (mysqli_num_rows($search_items) > 0) {
            while ($row = mysqli_fetch_array($search_items)) {
                $data.= '<tr><td width="0.5%"><input type="checkbox" name="item_' . $row['Item_ID'] . '" class="itemsNotInCategory" id="' . $row['Item_ID'] . '"></td><td width="100%">' . $row['Product_Name'] . '</td></tr>';
            }
        }
    } else {
        $search_items = mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Consultation_Type='" . $_GET['consultationType'] . "' $search_word LIMIT 100") or die(mysqli_error($conn));
        if (mysqli_num_rows($search_items) > 0) {
            while ($row = mysqli_fetch_array($search_items)) {
                // $data.= '<tr><td width="0.5%"><input type="checkbox" name="item_'.$row['Item_ID'].'" class="itemsInCategory" id="'.$row['Item_ID'].'"></td><td width="100%">'.$row['Product_Name'].'</td></tr>';

                $data.= '<tr><td width="0.5%"><input type="checkbox" name="item_' . $row['Item_ID'] . '" class="itemsInCategory" id="' . $row['Item_ID'] . '"></td><td width="100%">' . $row['Product_Name'] . '</td></tr>';
            }
        }
    }




    echo $data;
} elseif (isset($_POST['getItemByConsType'])) {
    $dataNot = '';
    $data = '';
    $consultationType = $_POST['consultationType'];

    if ($_POST['getItemByConsType'] == 'moveItemToRigth') {
        $itemsToMove = explode("<$$>", $_POST['items']);

        if (count($itemsToMove) > 1) {
            foreach ($itemsToMove as $id) {
                mysqli_query($conn,"UPDATE tbl_items SET Consultation_Type ='$consultationType' WHERE Item_ID='$id'") or die(mysqli_error($conn));
            }
        } else {
            mysqli_query($conn,"UPDATE tbl_items SET Consultation_Type ='$consultationType' WHERE Item_ID='" . $_POST['items'] . "'") or die(mysqli_error($conn));
        }
    } else if ($_POST['getItemByConsType'] == 'moveItemToLeft') {
        $itemsToMove = explode("<$$>", $_POST['items']);

        if (count($itemsToMove) > 1) {
            foreach ($itemsToMove as $id) {
                mysqli_query($conn,"UPDATE tbl_items SET Consultation_Type ='' WHERE Item_ID='$id'") or die(mysqli_error($conn));
            }
        } else {
            mysqli_query($conn,"UPDATE tbl_items SET Consultation_Type ='' WHERE Item_ID='" . $_POST['items'] . "'") or die(mysqli_error($conn));
        }
    }



    $sql = "SELECT Item_ID,Product_Name FROM tbl_items WHERE Consultation_Type ='$consultationType'";
    $search_items_for_this_category = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $no_of_item_for_this_category = mysqli_num_rows($search_items_for_this_category);
    if ($no_of_item_for_this_category > 0) {

        while ($row = mysqli_fetch_array($search_items_for_this_category)) {
            $data.= '<tr><td width="0.5%"><input type="checkbox" name="item_' . $row['Item_ID'] . '" class="itemsInCategory" id="' . $row['Item_ID'] . '"></td><td width="100%">' . $row['Product_Name'] . '</td></tr>';
        }

        //$dataToEncode['ITems_In_Category']=$data;
    }


    $sqlNot = "SELECT Item_ID,Product_Name FROM tbl_items WHERE Consultation_Type <>'$consultationType'";
    $search_items_Not_In_category = mysqli_query($conn,$sqlNot) or die(mysqli_error($conn));
    $no_of_item_not_in_category = mysqli_num_rows($search_items_Not_In_category);
    if ($no_of_item_not_in_category > 0) {
        while ($row = mysqli_fetch_array($search_items_Not_In_category)) {
            $dataNot.= '<tr><td width="0.5%"><input type="checkbox" name="item_' . $row['Item_ID'] . '" class="itemsNotInCategory" id="' . $row['Item_ID'] . '"></td><td width="100%">' . $row['Product_Name'] . '</td></tr>';
        }
    }

    $dataToEncode = array(
        'ITems_In_Category' => $data,
        'ITems_Not_In_Category' => $dataNot,
        'No_Of_Item_In_Category' => "Total Items: <b>" . $no_of_item_for_this_category . "</b>",
        'No_Of_Item_Not_In_Category' => "Total Items: <b>" . $no_of_item_not_in_category . "</b>",
    );

//  echo '<pre>';
//   print_r(json_encode(array_map('utf8_encode', $dataToEncode)));
//  echo '</pre>';
//  
    //echo '<br/>'. json_last_error_msg();
    echo json_encode(array_map('utf8_encode', $dataToEncode));
} elseif (isset($_GET['adthisItem'])) {
    $id = $_GET['adthisItem'];

    $select_items = mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Consultation_Type='" . $_GET['section'] . "' AND Status='Available' AND Item_ID='$id'");
    $data = '';
    $Price = '';
    $i = 1;
    if (mysqli_num_rows($select_items) > 0) {

        $row = mysqli_fetch_assoc($select_items);

        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
        $Select_Price = "select Items_Price as price from tbl_item_price ip
                                    where ip.Item_ID = '" . $row['Item_ID'] . "' AND ip.Sponsor_ID = '$Sponsor_ID'";
        $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

        if (mysqli_num_rows($itemSpecResult) > 0) {
            $rowpr = mysqli_fetch_assoc($itemSpecResult);
            $Price = number_format($rowpr['price']);
            if ($Price == 0) {
                $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                                    where ig.Item_ID = '$Item_ID'";
                $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                if (mysqli_num_rows($itemGenResult) > 0) {
                    $row = mysqli_fetch_assoc($itemGenResult);
                    $Price = number_format($row['price']);
                } else {
                    $Price = 0;
                }
            }
            //echo $Select_Price;
        } else {
            $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                               where ig.Item_ID = '" . $row['Item_ID'] . "'";
            $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

            if (mysqli_num_rows($itemGenResult) > 0) {
                $rowpr = mysqli_fetch_assoc($itemGenResult);
                $Price = number_format($rowpr['price']);
            } else {
                $Price = 0;
            }
        }

        $data = '<tr class="rowDelete" id="itm_id_' . $row['Item_ID'] . '"><td style="width:35%" ><input type="hidden" name="item_ID[]" value="' . $row['Item_ID'] . '"><input type="text" name="Product_Name[]" value="' . $row['Product_Name'] . '"></td><td style="width:15%"><input type="text" name="Selling_Price_Cash[]" value="' . $Price . '"></td></tr>';
    }

    echo $data;
} elseif (isset($_POST['addMoreItems'])) {

    $item_ID = $_POST['item_ID'];
    $Product_Name = $_POST['Product_Name'];
    $Selling_Price_Cash = $_POST['Selling_Price_Cash'];
    $data = '';
    $Check_In_Type = $_POST['section'];
    $payment_cache_ID = $_SESSION['Payment_Cache_ID'];
    $Patient_Direction = "others";
    $Consultant = $_SESSION['userinfo']['Employee_Name'];
    $Consultant_ID = $_SESSION['userinfo']['Employee_ID'];
    $Status = 'active';
    if ($_POST['section'] == 'Pharmacy') {
        $Status = 'active';
    }

    $Transaction_Date_And_Time = '(SELECT NOW())';
    $Process_Status = 'inactive';
    $Discount = 0;
    $Service_Date_And_Time = '(SELECT NOW())';
    $Transaction_Type = $_SESSION['Transaction_Type'];




    foreach ($Product_Name as $key => $value) {
        $data .= $item_ID[$key] . ' ' . $Product_Name[$key] . ' ' . $Selling_Price_Cash[$key] . '<br/>';
        $insert_query = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,
			Payment_Cache_ID, Transaction_Date_And_Time, Process_Status,Sub_Department_ID,Transaction_Type,Service_Date_And_Time)
			VALUES ('$Check_In_Type', '" . $item_ID[$key] . "', '$Discount', '" . str_replace(',', '', $Selling_Price_Cash[$key]) . "', 1, '$Patient_Direction', '$Consultant', '$Consultant_ID',
			'$Status','$payment_cache_ID', NOW(),
			'$Process_Status','$Sub_Department_ID','$Transaction_Type',NOW())";

        mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
    }

    echo 'saved';
} elseif (isset($_GET['loadData'])) {
    $Status = '';
    if ($_GET['section'] == 'Pharmacy') {
        $Status = 'active';
    } else {
        $Status = 'active';
    }
    $sql = "SELECT itm.Item_ID,itm.Product_Name FROM tbl_items as itm WHERE itm.Consultation_Type='" . $_GET['section'] . "' AND itm.Status='Available' AND itm.Can_Be_Sold='yes' AND itm.Item_ID NOT IN (SELECT Item_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID='" . $_GET['Payment_Cache_ID'] . "' AND Status='$Status' and Check_In_Type ='" . $_GET['section'] . "' ) LIMIT 100";

    $select_items = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $data = '';
    while ($row = mysqli_fetch_array($select_items)) {
        $data.= '<tr><td width="0.5%"><input type="checkbox" name="item_' . $row['Item_ID'] . '" class="chosenTests" id="' . $row['Item_ID'] . '"></td><td width="100%">' . $row['Product_Name'] . '</td></tr>';
    }
    
    echo $data;
}if ((isset($_GET['section'])) && isset($_GET['search_word']) && !empty($_GET['search_word'])) {
    $search_items = '';
    $search_word = '';
    // $_GET['Payment_Cache_ID']=$_SESSION['Payment_Cache_ID'];

    $Status = '';
    if ($_GET['section'] == 'Pharmacy') {
        $Status = 'approved';
    } else {
        $Status = 'active';
    }

    if (isset($_GET['search_word']) && !empty($_GET['search_word'])) {
        $search_word = "  AND Product_Name LIKE '%" . $_GET['search_word'] . "%'";
    }

    $sql = "SELECT itm.Item_ID,itm.Product_Name FROM tbl_items as itm WHERE itm.Consultation_Type='" . $_GET['section'] . "' AND itm.Status='Available' AND itm.Item_ID NOT IN (SELECT Item_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID='" . $_GET['Payment_Cache_ID'] . "' AND Status='$Status' and Check_In_Type ='" . $_GET['section'] . "' ) $search_word LIMIT 100";
    //       echo $sql;
//       exit;
    $select_items = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $data = '';
    while ($row = mysqli_fetch_array($select_items)) {
        $data.= '<tr><td width="0.5%"><input type="checkbox" name="item_' . $row['Item_ID'] . '" class="chosenTests" id="' . $row['Item_ID'] . '"></td><td width="100%">' . $row['Product_Name'] . '</td></tr>';
    }

    echo $data;
}
 
