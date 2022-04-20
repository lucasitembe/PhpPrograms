<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/returninward.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Inward_ID'])){
        $Inward_ID = $_GET['Inward_ID'];
    }else{
        $Inward_ID = 0;
    }

    if ($Item_ID > 0) {
        $Removed = false; $r = 0;
        $Return_Inward_Item_List = $_SESSION['Return_Inward']['Items'];
        foreach($Return_Inward_Item_List as $Return_Inward_Item) {
            if ($Return_Inward_Item['Item_ID'] == $Item_ID) {
                unset($Return_Inward_Item_List[$r]);
                $Removed = true;
                break;
            }
            $r++;
        }

        $_SESSION['Return_Inward']['Items'] = $Return_Inward_Item_List;

        if ($Removed) {
            include_once("./returninward_edit_show_items.php");
        }
    }
?>