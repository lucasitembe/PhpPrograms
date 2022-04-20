<?php 
    include("./includes/connection.php");

    if(isset($_POST['outOfStockRequest'])){
        $itemListCacheId = (isset($_POST['itemListCacheId'])) ? $_POST['itemListCacheId'] : 0;
        
        $sqlQuery = "UPDATE tbl_item_list_cache SET Status = 'Out Of Stock' WHERE Payment_Item_Cache_List_ID = '$itemListCacheId'";
        if(mysqli_query($conn,$sqlQuery)){
            echo "Updated Successfull";
        }else{
            die(mysqli_error($conn));
        }
    }

    if(isset($_POST['medicationSubstitute'])){
        $itemId = (isset($_POST['itemId'])) ? $_POST['itemId'] : 0;
        $OlditemId = (isset($_POST['OlditemId'])) ? $_POST['OlditemId'] : 0;
        $EmployeeId = (isset($_POST['EmployeeId'])) ? $_POST['EmployeeId'] : 0;
        $ItemListCacheId = (isset($_POST['ItemListCacheId'])) ? $_POST['ItemListCacheId'] : 0;

        $sqlQuery = mysqli_query($conn,"UPDATE tbl_item_list_cache SET item_substituted = '$OlditemId',Item_ID = '$itemId',substituted_by ='$EmployeeId' WHERE Payment_Item_Cache_List_ID = '$ItemListCacheId'") or die(mysqli_errno($conn));
        if(!$sqlQuery){
            die(mysqli_errno($conn));
        }else{
            echo "Item Substituted Successfully";
        }
    }

    if(isset($_GET['check_if_item_is_substituted'])){
        $Payment_Item_List_Cache = (isset($_GET['Payment_Item_List_Cache'])) ? $_GET['Payment_Item_List_Cache'] : 0;
        $Item_Id = (isset($_GET['Item_Id'])) ? $_GET['Item_Id'] : 0;
        $feedack = "";

        $sqlQuery = mysqli_query($conn,"SELECT item_substituted FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Payment_Item_List_Cache' AND Item_ID = '$Item_Id' AND item_substituted > 0 AND substituted_by > 0 LIMIT 1");
        if(mysqli_num_rows($sqlQuery) > 0){
            $feedack = 1;
        }else{
            $feedack = 2;
        }
        echo $feedack;
    }

    if(isset($_POST['return_medication'])){
        $Item_Id = (isset($_POST['Item_Id'])) ? $_POST['Item_Id'] : 0;
        $ItemListCacheId = (isset($_POST['ItemListCacheId'])) ? $_POST['ItemListCacheId'] : 0;
        $feedack = "";
        $sqlQueryGetSubItem = mysqli_fetch_assoc(mysqli_query($conn,"SELECT item_substituted FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$ItemListCacheId' LIMIT 1"))['item_substituted'];
        $sqlQueryUpdateItems = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Item_ID = '$sqlQueryGetSubItem',substituted_by= '0',item_substituted='0' WHERE Payment_Item_Cache_List_ID = '$ItemListCacheId'");
        if($sqlQueryUpdateItems){
            $feedack = "Medication Successfull Return";
        }else{
            $feedback = "Something wrong ... ";
        }
        echo $feedack;
    }

    if(isset($_GET['check_dose_multipole'])){
        $Payment_Cache_ID = (isset($_GET['Payment_Cache_ID'])) ? $_GET['Payment_Cache_ID'] : 0;
        $Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : 0;
        $Items_Ids_String = (isset($_GET['Items_Ids_String'])) ? $_GET['Items_Ids_String'] : 0;
        $output_check = "";

        $Today_Date = mysqli_query($conn,"SELECT now() as today");
        while ($row = mysqli_fetch_array($Today_Date)) {
            $original_Date = $row['today'];
            $new_Date = strtotime($original_Date);
            $Today = $new_Date;
        }

        $items_id_array = explode(',',$Items_Ids_String);
        $items_array_size = sizeof($items_id_array);

        for($i = 0; $i < $items_array_size; $i++){
            $new_item_id = $items_id_array[$i];

            $get_item_from_item_list_cache = mysqli_query($conn,"SELECT ti.Product_Name,tilc.Dispense_Date_Time,tpc.Payment_Cache_ID,tpc.Registration_ID,tilc.dosage_duration FROM tbl_payment_cache AS tpc,tbl_item_list_cache AS tilc,tbl_items AS ti WHERE tpc.Registration_ID = '$Registration_ID'
            AND tpc.Payment_Cache_ID = tilc.Payment_Cache_ID AND tilc.Item_ID = '$new_item_id' AND ti.Item_ID = '$new_item_id' AND tilc.Status = 'dispensed' ORDER BY tpc.Payment_Cache_ID DESC LIMIT 1");
        
            while($data = mysqli_fetch_assoc($get_item_from_item_list_cache)){
                $Dispense_Date_Time = $data['Dispense_Date_Time'];
                $dosage_duration = $data['dosage_duration'];
                $Product_Name = $data['Product_Name'];

                $Last_Dispense_Date = strtotime($Dispense_Date_Time);
                $timeDiff = $Today - $Last_Dispense_Date;
                $days = floor($timeDiff / (60 * 60 * 24));
        
                $daysleft=$dosage_duration-$days;

                if($daysleft > 0){
                    $output_check .= $Product_Name." : ".$daysleft." Day(s) left to finish the Dosage. \r\n";
                }
            }   
        }
        echo $output_check;
    }

    # checks if the item is available in tbl_item_balance and has balance if not found insert then update
    if(isset($_GET['change_department'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
        $Item_ID = $_GET['Item_ID'];

        $check_if_item_exist = mysqli_query($conn,"SELECT Item_ID FROM tbl_items_balance WHERE Sub_Department_ID = '$Sub_Department_ID' AND Item_ID = '$Item_ID' LIMIT 1") or die(mysqli_errno($conn));
        if(mysqli_num_rows($check_if_item_exist) == 0){
            $add_item_ = mysqli_query($conn,"INSERT INTO tbl_items_balance (Item_ID,Item_Balance,Item_Temporary_Balance,Sub_Department_ID,Sub_Department_Type,Reorder_Level,Reorder_Level_Status) 
                                             VALUES ($Item_ID,0,0,$Sub_Department_ID,'Pharmacy',0,'normal')");
            if(!$add_item_){ die(mysqli_error($conn)." :: Not added"); }
        }
        
        $update_department = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Sub_Department_ID = '$Sub_Department_ID' WHERE Item_ID = '$Item_ID' AND Status IN ('active','approved','paid') AND Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");

        echo (!$update_department) ? "Update Successfully" : mysqli_errno($conn)." :: Not Updates";
    }
?>