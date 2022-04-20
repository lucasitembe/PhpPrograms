<?php
    include_once("./functions/itemsdisposal.php");

    if(isset($_GET['Disposal_Item_ID'])){
        $Disposal_Item_ID = $_GET['Disposal_Item_ID'];
    }else{
        $Disposal_Item_ID = 0;
    }

    if(isset($_GET['Item_Remark'])){
        $Item_Remark = $_GET['Item_Remark'];
    }else{
        $Item_Remark = 0;
    }

    if ($Disposal_Item_ID > 0) {
        $Update_Item_Remark = Update_Item_Remark($Disposal_Item_ID, $Item_Remark);
        $hasError = $Update_Item_Remark["error"];
        if (!$hasError) {
            echo "yes";
        } else {
            echo $Update_Item_Remark["errorMsg"];
        }
    }
?>