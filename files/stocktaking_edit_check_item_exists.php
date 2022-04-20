<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/stocktaking.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Stock_Taking_ID'])){
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
    }else{
        $Stock_Taking_ID = 0;
    }

    $Exists = "no";
    if ($Item_ID > 0 && $Stock_Taking_ID > 0) {
        $Stock_Taking_Item_List = $_SESSION['Stock_Taking']['Items'];
        foreach($Stock_Taking_Item_List as $Stock_Taking_Item) {
            if ($Stock_Taking_Item['Item_ID'] == $Item_ID) {
                $Exists = "yes";
                break;
            }
        }
    }

    echo $Exists;
?>