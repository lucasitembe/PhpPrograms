<?php
 include("./includes/connection.php");

 $sponsor_id = $_POST['Sponsor_ID'];
 $item_id = $_POST['Item_ID'];
 $Registration_ID = $_POST['Registration_ID'];

 #check if item exist
$check_query = mysqli_query($conn,"SELECT duration FROM tbl_items_alert_control WHERE item_id='$item_id' AND sponsor_id='$sponsor_id'");
$item_code_control_data = mysqli_fetch_assoc($check_query);
$duration = $item_code_control_data['duration'];

$pased_days="";$code=""; $min_days="";$past_date="";

if(mysqli_num_rows($check_query)>0){
    $query_result = mysqli_query($conn,"SELECT Service_Date_And_Time FROM tbl_item_list_cache,tbl_payment_cache WHERE  tbl_item_list_cache.Payment_Cache_ID=tbl_payment_cache.Payment_Cache_ID AND Registration_ID='$Registration_ID' AND Item_ID='$item_id' AND Status IN('active','paid','approved','dispensed','pending','served','Sample Collected')");
    if(mysqli_num_rows($query_result)>0){
        while($row = mysqli_fetch_assoc($query_result)){
            $past_date=$row['Service_Date_And_Time'];//get database last visit date
        }

        //get the current date
        $Today_Date = mysqli_fetch_assoc(mysqli_query($conn,"select now() as today"))['today'];
            
        $date1 = new DateTime(date('Y-m-d', strtotime($Today_Date)));
        $date2 = new DateTime(date('Y-m-d', strtotime($past_date)));
        $diff = $date1->diff($date2);
        $date_diff = $diff->days;
        $diff_days= $date_diff;
        
        if($diff_days<=$duration&&$duration!=0){
            $pased_days=$diff_days;$code="200"; $min_days=$duration; 
        }
    }
}

echo '{"code":'.'"'.$code.'"'.', "min_days":'.'"'.$min_days.'"'.',"pased_days":'.'"'.$pased_days.'"'.',"past_date":'.'"'.$past_date.'"'.'}';
