<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>

<?php
    include_once("./functions/returninward.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Quantity_Returned'])){
        $Quantity_Returned = $_GET['Quantity_Returned'];
    }else{
        $Quantity_Returned = 0;
    }

    if ($Item_ID > 0 && $Quantity_Returned >= 0) {
        $Updated = false;$r=0;
        $Return_Inward_Item_List = $_SESSION['Return_Inward']['Items'];
        foreach($Return_Inward_Item_List as $Return_Inward_Item) {
            if ($Return_Inward_Item['Item_ID'] == $Item_ID) {
                $Return_Inward_Item_List[$r]['Quantity_Returned'] = $Quantity_Returned;
                $Updated = true;
                break;
            }
            $r++;
        }

        $_SESSION['Return_Inward']['Items'] = $Return_Inward_Item_List;

        if ($Updated) {
            echo "yes";
        } else {
            echo "no";
        }
    }
?>