<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/requisition.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Requisition_ID'])){
        $Requisition_ID = $_GET['Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }

    $Exists = "no";
    if ($Item_ID > 0 && $Requisition_ID > 0) {
        $Requisition_Item_List = $_SESSION['Requisition']['Items'];
        foreach($Requisition_Item_List as $Requisition_Item) {
            if ($Requisition_Item['Item_ID'] == $Item_ID) {
                $Exists = "yes";
                break;
            }
        }
    }

    echo $Exists;
?>