<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/returnoutward.php");
    include_once("./functions/items.php");

    if(isset($_GET['Outward_ID'])){
        $Outward_ID = $_GET['Outward_ID'];
    }else{
        $Outward_ID = 0;
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

    if ($Outward_ID > 0 && $Item_ID > 0 ) {
        $Return_Outward_Item_List = $_SESSION['Return_Outward']['Items'];
        $Item = Get_Item($Item_ID);
        $Item_Balance = Get_Item_Balance($Item_ID, $Current_Store_ID);
        $Outward_Item_ID = count($Return_Outward_Item_List) - 1;

        $Return_Outward_Item = array(
            "Outward_Item_ID" => $Outward_Item_ID + 1,
            "Product_Name" => $Item['Product_Name'],
            "Store_Balance" => $Item_Balance['Item_Balance'],
            "Item_ID" => $Item_ID,
            "Quantity_Returned" => 0,
            "Outward_ID" => $Outward_ID,
            "Item_Remark" => ''
        );
        $Return_Outward_Item_List = array_merge($Return_Outward_Item_List, array($Return_Outward_Item));
        $_SESSION['Return_Outward']['Items'] = $Return_Outward_Item_List;

        include_once("./returnoutward_edit_show_items.php");
    }

?>