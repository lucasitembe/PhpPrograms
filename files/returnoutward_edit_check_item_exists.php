<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/returnoutward.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Outward_ID'])){
        $Outward_ID = $_GET['Outward_ID'];
    }else{
        $Outward_ID = 0;
    }

    $Exists = "no";
    if ($Item_ID > 0 && $Outward_ID > 0) {
        $Return_Outward_Item_List = $_SESSION['Return_Outward']['Items'];
        foreach($Return_Outward_Item_List as $Return_Outward_Item) {
            if ($Return_Outward_Item['Item_ID'] == $Item_ID) {
                $Exists = "yes";
                break;
            }
        }
    }

    echo $Exists;
?>