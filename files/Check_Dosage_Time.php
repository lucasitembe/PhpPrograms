<?php

include("./includes/connection.php");
if (isset($_GET['Item_ID'])) {
    $Item_ID = $_GET['Item_ID'];
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}
//get today date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = strtotime($original_Date);
    $Today = $new_Date;
}
$select = mysqli_query($conn,"SELECT DATE(ilc.Dispense_Date_Time) AS Last_Dispense_Date,ilc.dosage_duration  from tbl_item_list_cache ilc JOIN tbl_payment_cache pc ON  pc.Payment_Cache_ID = ilc.Payment_Cache_ID
         where pc.Registration_ID = '$Registration_ID' AND ilc.Item_ID='$Item_ID' AND Status='dispensed' ORDER BY Dispense_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($row = mysqli_fetch_assoc($select)) {
        $Dispense_Date_Time = $row['Last_Dispense_Date'];
        $dosage_duration = $row['dosage_duration'];
        $Last_Dispense_Date = strtotime($Dispense_Date_Time);
        $timeDiff = $Today - $Last_Dispense_Date;
        $days = floor($timeDiff / (60 * 60 * 24));
        if($days>$dosage_duration){
        echo "endelea";    
        }else{
             $daysleft=$dosage_duration-$days;
       echo  $daysleft. " Day(s) left to finish the Dosage. Do you still want to dispense?";     
        }
       
    }
} else {
    echo "endelea";
}
