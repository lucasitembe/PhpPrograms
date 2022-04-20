<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>

<?php
    include_once("./functions/requisition.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Quantity_Required'])){
        $Quantity_Required = $_GET['Quantity_Required'];
    }else{
        $Quantity_Required = 0;
    }

    if ($Item_ID > 0 && $Quantity_Required >= 0) {
        $Updated = false;$r=0;
        $Requisition_Item_List = $_SESSION['Requisition']['Items'];
        foreach($Requisition_Item_List as $Requisition_Item) {
            if ($Requisition_Item['Item_ID'] == $Item_ID) {
                $Requisition_Item_List[$r]['Quantity_Required'] = $Quantity_Required;
                $Updated = true;
                break;
            }
            $r++;
        }

        $_SESSION['Requisition']['Items'] = $Requisition_Item_List;

        if ($Updated) {
            echo "yes";
        } else {
            echo "no";
        }
    }
?>