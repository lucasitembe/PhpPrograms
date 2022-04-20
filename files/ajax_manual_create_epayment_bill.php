<?php
session_start();
include("./includes/connection.php");
include_once("./functions/items.php");
$Amount = 0;
$HAS_ERROR = false;
$Invoice_Number="0";
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$selected_Payment_Item_Cache_List_ID=$_POST['selected_Payment_Item_Cache_List_ID'];
$Registration_ID=$_POST['Registration_ID'];
$Payment_Cache_ID=$_POST['Payment_Cache_ID'];
$grand_total_price=$_POST['grand_total_price'];

    $count_processed_item=0;
    foreach($selected_Payment_Item_Cache_List_ID as $Payment_Item_Cache_List_ID){
        ///verify if this transaction already created
        if(isset($_SESSION['Transaction_ID'])){
            $Transaction_ID=$_SESSION['Transaction_ID'];
            $sql_verify_if_transaction_already_created_result=mysqli_query($conn,"SELECT Transaction_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn)); 
        
            if(mysqli_num_rows($sql_verify_if_transaction_already_created_result)>0){
               $count_processed_item++; 
            }
        }
    }

//if($count_processed_item<=0){
Start_Transaction();
require_once './includes/Folio_Number_Generator.php';
 $insert = mysqli_query($conn,"insert into tbl_bank_transaction_cache(
								Registration_ID, Amount_Required, Employee_ID, Transaction_Date_Time, Transaction_Date, Source) 
							values ('$Registration_ID','$grand_total_price','$Employee_ID',(select now()),(select now()),'Revenue Center')") or die(mysqli_error($conn) . 'One');
    if (!$insert) {
        $HAS_ERROR = true;
        die("4");
    }
    $select_result = mysqli_query($conn,"select Transaction_ID from tbl_bank_transaction_cache where 
											Registration_ID = '$Registration_ID' and 
											Employee_ID = '$Employee_ID' order by Transaction_ID desc limit 1") or die(mysqli_error($conn) . 'two');
    $no = mysqli_num_rows($select_result);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_result)) {
            $Transaction_ID = $row['Transaction_ID'];
        }
    } else {
        $Transaction_ID = 0;
    }
    if ($Transaction_ID != 0) {
        $retrieve_rs = mysqli_query($conn,"SELECT hospital_id FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
        $data_hosp_id = mysqli_fetch_assoc($retrieve_rs);
        $hospital_id = $data_hosp_id['hospital_id'];

        $Invoice_Number = str_pad($hospital_id, 2, "0", STR_PAD_LEFT) . str_pad($Transaction_ID, 11, "0", STR_PAD_LEFT);

            //update code
            $update = mysqli_query($conn,"update tbl_bank_transaction_cache set Payment_Code = '$Invoice_Number' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
            if (!$update) {
                $HAS_ERROR = true;
                die("5");
            }

    foreach($selected_Payment_Item_Cache_List_ID as $Payment_Item_Cache_List_ID){
          $result = mysqli_query($conn,"update tbl_item_list_cache set Transaction_ID = '$Transaction_ID', ePayment_Status = 'Served' where
															Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
        if (!$result) {
            $HAS_ERROR = true;
            die("6");
        }
    }
//            $num = mysqli_num_rows($select_items);
//           // die("$num ==>");
//            if ($num > 0) {
//                while ($data = mysqli_fetch_array($select_items)) {
//                    $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
//                    $result = mysqli_query($conn,"update tbl_item_list_cache set Transaction_ID = '$Transaction_ID', ePayment_Status = 'Served' where
//															Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
//                    if (!$result) {
//                        $HAS_ERROR = true;
//                        die("6");
//                    }
//                }
//            }else{
//               $HAS_ERROR = true; 
//               die("7-->");
//            }

            if (!$HAS_ERROR) {         
                Commit_Transaction();
               echo $_SESSION['Transaction_ID'] = $Transaction_ID;
//                echo $Invoice_Number;
//                if(isset($_GET['from_revenue_phamacy'])&&$_GET['from_revenue_phamacy']=="yes"){
//                  $from_revenue_phamacy="&from_revenue_phamacy=yes";  
//                }else{
//                    $from_revenue_phamacy="";
//                }
//                header("Location: ./crdbtransactiondetails.php?Section=Departmental&CRDBTransactionDetails=CRDBTransactionDetailsThisPage&Payment_Cache_ID='$Payment_Cache_ID'&kutokaphamacy='$kutokaphamacy'$from_revenue_phamacy" . $otherlocs);
            } else {
                Rollback_Transaction();
                echo "fail";
            }
    }else{
        echo "fail";
    }
//}else{
//    echo "already_created";
//}