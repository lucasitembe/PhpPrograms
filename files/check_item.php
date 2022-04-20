<?php
include("./includes/connection.php");
if(isset($_GET['Payment_Item_Cache_List_ID'])){
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
}else{
    $Payment_Item_Cache_List_ID = 0;
}
if(isset($_GET['Registration_ID'])){
    $Registration_ID = $_GET['Registration_ID'];
}
else{
    $Registration_ID = 0;
}
if(isset($_GET['Payment_Cache_ID'])){
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $payment_cache_ID = $Payment_Cache_ID;
}else{
    $Payment_Cache_ID = 0;
}
$select_qr = mysqli_query($conn,"SELECT * FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' ");
$row=mysqli_fetch_assoc($select_qr);
//$Item_ID=$row['Item_ID'];
//$Payment_Cache_ID=$row['Payment_Cache_ID'];


//run the query to check if this item has been paid
// $check_item=mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_payment_cache pc,tbl_item_list_cache il
                          // WHERE pr.Registration_ID=pc.Registration_ID
                           // AND pc.Payment_Cache_ID=il.Payment_Cache_ID
                           // AND il.Payment_Cache_ID='$payment_cache_ID'
                           // AND il.Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'
                          // AND il.Item_ID='$Item_ID'
                           // AND pc.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
						   
	// echo "SELECT * FROM tbl_patient_registration pr,tbl_payment_cache pc,tbl_item_list_cache il
                          // WHERE pr.Registration_ID=pc.Registration_ID
                           // AND pc.Payment_Cache_ID=il.Payment_Cache_ID
                           // AND il.Payment_Cache_ID='$payment_cache_ID'
                           // AND il.Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'
                          // AND il.Item_ID='$Item_ID'
                           // AND pc.Registration_ID='$Registration_ID'";	exit;				  
//$check_item_row=mysqli_fetch_assoc($check_item);
$Status=$row['Status'];
//echo $Status;exit;
if(strtolower($Status) =='active' || strtolower($Status) =='notsaved'){
    echo "unprocessed";
}else{
    echo "processed";
}






?>