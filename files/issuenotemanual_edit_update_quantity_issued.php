<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>

<?php
    include_once("./functions/issuenotemanual.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Quantity_Issued'])){
        $Quantity_Issued = $_GET['Quantity_Issued'];
    }else{
        $Quantity_Issued = 0;
    }

    if ($Item_ID > 0 && $Quantity_Issued >= 0) {
        $Updated = false;$r=0;
        $Issue_Note_Manual_Item_List = $_SESSION['Issue_Note_Manual']['Items'];
        foreach($Issue_Note_Manual_Item_List as $Issue_Note_Manual_Item) {
            if ($Issue_Note_Manual_Item['Item_ID'] == $Item_ID) {
                $Issue_Note_Manual_Item_List[$r]['Quantity_Issued'] = $Quantity_Issued;
                $Updated = true;
                break;
            }
            $r++;
        }

        $_SESSION['Issue_Note_Manual']['Items'] = $Issue_Note_Manual_Item_List;

        if ($Updated) {
            echo "yes";
        } else {
            echo "no";
        }
    }
?>