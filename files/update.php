<?php

@session_start();
include("./includes/connection.php");

//select surgery

$check = mysqli_query($conn,"SELECT `Procedure_Names`,Post_Operative_Date_Time,Employee_ID,consultation_ID  FROM `tbl_post_operative`") or die(mysqli_error($conn));
$i=1;
while ($row1 = mysqli_fetch_array($check)) {
    $Procedure_Names = $row1['Procedure_Names'];
    $Post_Operative_Date_Time = $row1['Post_Operative_Date_Time'];
    $Employee_ID = $row1['Employee_ID'];
    $consultation_ID = $row1['consultation_ID'];
    
    $select = mysqli_query($conn,"SELECT `Payment_Item_Cache_List_ID`,`Item_ID`,consultation_ID,ServedDateTime,ServedBy FROM `tbl_item_list_cache` il inner join tbl_payment_cache pc ON pc.`Payment_Cache_ID`=il.`Payment_Cache_ID` WHERE consultation_ID='$consultation_ID' AND Item_ID = '$Procedure_Names'  AND Check_In_Type='Surgery' AND Status='served' AND ServedBy IS NULL ") or die(mysqli_error($conn));
    $nums = mysqli_num_rows($select);
    if ($nums > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
            $ServedDateTime = $row['ServedDateTime'];
            $ServedBy = $row['ServedBy'];
           //S echo $i.' '.$Payment_Item_Cache_List_ID.' '.$Employee_ID.' '.$Procedure_Names.' '.$consultation_ID.'<br/>';
           $i++;
            mysqli_query($conn,"UPDATE tbl_item_list_cache SET ServedDateTime='$Post_Operative_Date_Time',ServedBy='$Employee_ID' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
        }

       // echo 'Success';
    }
}

echo $i.'<br/>';

//  $select = mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID,Item_ID,Post_Operative_Date_Time,po.Employee_ID FROM tbl_item_list_cache ilc JOIN tbl_post_operative po ON po.Procedure_Names = ilc.Item_ID where ilc.Payment_Cache_ID IN (SELECT pc.Payment_Cache_ID FROM tbl_payment_cache pc JOIN tbl_item_list_cache il ON il.Payment_Cache_ID=pc.Payment_Cache_ID JOIN tbl_post_operative pop ON pop.consultation_ID = pc.consultation_ID AND Check_In_Type='Surgery' AND Status='served' group by pc.Payment_Cache_ID) ") or die(mysqli_error($conn));
//    $nums = mysqli_num_rows($select);
//    if($nums > 0){
//        while ($row = mysqli_fetch_array($select)) {
//            $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
//            $Post_Operative_Date_Time = $row['Post_Operative_Date_Time'];
//            $Employee_ID = $row['Employee_ID'];
//            $Item_ID = $row['Item_ID'];
//            
//            mysqli_query($conn,"UPDATE tbl_item_list_cache SET ServedDateTime=NULL,ServedBy=NULL WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn)); 
//        }
//        
//        echo 'Success';
//    }