<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])){
    $start_date=$_POST['start_date'];
}else{
    $start_date=""; 
}
if(isset($_POST['end_date'])){
    $end_date=$_POST['end_date'];
}else{
    $end_date=""; 
}
$date_from = strtotime($start_date); // Convert date to a UNIX timestamp  
$date_to = strtotime($end_date); // Convert date to a UNIX timestamp

$serial_count=1;
for ($i=$date_from; $i<=$date_to; $i+=86400) {
$Current_Date = date("Y-m-d", $i);
$sql_select_number_of_test_result=mysqli_query($conn,"SELECT test_result_ID,Payment_Cache_ID FROM tbl_test_results tr,tbl_item_list_cache ilc WHERE tr.payment_item_ID=ilc.Payment_Item_Cache_List_ID AND DATE(tr.TimeSubmitted)='$Current_Date'") or die(mysqli_error($conn));
$count_number_of_test= mysqli_num_rows($sql_select_number_of_test_result);
 
$sql_select_number_of_patient_result=mysqli_query($conn,"SELECT pc.Registration_ID FROM tbl_test_results tr,tbl_item_list_cache ilc,tbl_payment_cache pc WHERE tr.payment_item_ID=ilc.Payment_Item_Cache_List_ID AND ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND DATE(tr.TimeSubmitted)='$Current_Date' GROUP BY pc.Registration_ID") or die(mysqli_error($conn));
$count_number_of_patient= mysqli_num_rows($sql_select_number_of_patient_result);
 
 echo "<tr>
            <td>$serial_count.</td>
            <td>$count_number_of_patient</td>
            <td>$count_number_of_test</td>
            <td>$Current_Date</td>
      </tr>";
 $serial_count++;
}   
?>