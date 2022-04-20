<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/returninward.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['Item_Remark'])){
        $Item_Remark = $_GET['Item_Remark'];
    }else{
        $Item_Remark = 0;
    }

    if ($Item_ID > 0) {
        $Updated = false;$r=0;
        $Return_Inward_Item_List = $_SESSION['Return_Inward']['Items'];
        foreach($Return_Inward_Item_List as $Return_Inward_Item) {
            if ($Return_Inward_Item['Item_ID'] == $Item_ID) {
                $Return_Inward_Item_List[$r]['Item_Remark'] = $Item_Remark;
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