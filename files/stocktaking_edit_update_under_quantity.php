<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/stocktaking.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Under_Quantity'])){
        $Under_Quantity = $_GET['Under_Quantity'];
    }else{
        $Under_Quantity = 0;
    }

    if ($Item_ID > 0 && $Under_Quantity >= 0) {
        $Updated = false;$r=0;
        $Stock_Taking_Item_List = $_SESSION['Stock_Taking']['Items'];
        foreach($Stock_Taking_Item_List as $Stock_Taking_Item) {
            if ($Stock_Taking_Item['Item_ID'] == $Item_ID) {
                $Stock_Taking_Item_List[$r]['Under_Quantity'] = $Under_Quantity;
                if ($Under_Quantity > 0) {
                    $Stock_Taking_Item_List[$r]['Over_Quantity'] = 0;
                }
                $Updated = true;
                break;
            }
            $r++;
        }

        $_SESSION['Stock_Taking']['Items'] = $Stock_Taking_Item_List;

        if ($Updated) {
            echo "yes";
        } else {
            echo "no";
        }
    }
?>