<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/issuenotemanual.php");

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_GET['IssueManual_ID'])){
        $IssueManual_ID = $_GET['IssueManual_ID'];
    }else{
        $IssueManual_ID = 0;
    }

    $Exists = "no";
    if ($Item_ID > 0 && $IssueManual_ID > 0) {
        if (Check_Issue_Note_Manual_Item($Item_ID, $IssueManual_ID)) {
            $Exists = "yes";
        }
    }

    echo $Exists;
?>