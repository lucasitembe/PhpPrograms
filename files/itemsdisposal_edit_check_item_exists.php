<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>

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
        $Items_Disposal_Item_List = $_SESSION['Disposal_Edit']['Items'];
        foreach($Items_Disposal_Item_List as $Disposal_Item) {
            if ($Disposal_Item['Item_ID'] == $Item_ID) {
                $Exists = "yes";
                break;
            }
        }

    }

    echo $Exists;
?>