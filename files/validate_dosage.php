<?php 
    #CONNECTION
    include("./includes/connection.php");

    #SELECT NOW DATE AND TIME
    $Today_Date = mysqli_query($conn,"SELECT now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = strtotime($original_Date);
        $Today = $new_Date;
    }

    #GET POSTED PARAMS
    $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    $Item_Id = $_POST['Item_Id'];
    $Registration_Id = $_POST['Registration_Id'];
    $Payment_Cache_Id = $_POST['Payment_Cache_Id'];

    $Dispense_Date_Time = "";

    $get_item = mysqli_query($conn,"SELECT *  FROM tbl_item_list_cache WHERE Item_Id = '$Item_Id' AND Payment_Cache_Id = '$Payment_Cache_Id' LIMIT 1 ") or mysqli_error($conn);
    
    if(mysqli_num_rows($get_item) > 0){
        while($item_data_dose = mysqli_fetch_assoc($get_item)){
            $Dispense_Date_Time = $item_data_dose['Dispense_Date_Time'];
            $dosage_duration  = $item_data_dose ['dosage_duration'];
        }

        $Last_Dispense_Date = strtotime($Dispense_Date_Time);
        $timeDiff = $Today - $Last_Dispense_Date;
        $days = floor($timeDiff / (60 * 60 * 24));

        $daysleft=$dosage_duration-$days;

        if($daysleft <= 0){
            echo 'Yes';
        }else{
            echo  $daysleft. " Day(s) left to finish the Dosage. Do you still want to dispense?";     
        }
    }else{
        echo "Never given continue";
    }  
?>