<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/returninward.php");
    include_once("./functions/items.php");

    if(isset($_GET['Inward_ID'])){
        $Inward_ID = $_GET['Inward_ID'];
    }else{
        $Inward_ID = 0;
    }

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }

    if ($Inward_ID > 0 && $Item_ID > 0 ) {
        $Return_Inward_Item_List = $_SESSION['Return_Inward']['Items'];
        $Item = Get_Item($Item_ID);
        $Item_Balance = Get_Item_Balance($Item_ID, $Current_Store_ID);
        $Inward_Item_ID = count($Return_Inward_Item_List) - 1;

        $Return_Inward_Item = array(
            "Inward_Item_ID" => $Inward_Item_ID + 1,
            "Product_Name" => $Item['Product_Name'],
            "Store_Balance" => $Item_Balance['Item_Balance'],
            "Item_ID" => $Item_ID,
            "Quantity_Returned" => 0,
            "Inward_ID" => $Inward_ID,
            "Item_Remark" => ''
        );
        $Return_Inward_Item_List = array_merge($Return_Inward_Item_List, array($Return_Inward_Item));
        $_SESSION['Return_Inward']['Items'] = $Return_Inward_Item_List;

        include_once("./returninward_edit_show_items.php");
    }

?>