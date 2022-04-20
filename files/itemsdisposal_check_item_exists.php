<?php
    include_once("./functions/itemsdisposal.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Disposal_ID'])){
        $Disposal_ID = $_GET['Disposal_ID'];
    }else{
        $Disposal_ID = 0;
    }

    $Exists = "no";
    if ($Item_ID > 0 && $Disposal_ID > 0) {
        if (Check_Items_Disposal_Item($Item_ID, $Disposal_ID)) {
            $Exists = "yes";
        }
    }

    echo $Exists;
?>