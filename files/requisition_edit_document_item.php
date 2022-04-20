<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/requisition.php");
    include_once("./functions/items.php");

    if(isset($_GET['Requisition_ID'])){
        $Requisition_ID = $_GET['Requisition_ID'];
    }else{
        $Requisition_ID = 0;
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

    if ($Requisition_ID > 0 && $Item_ID > 0 ) {
        $Requisition_Item_List = $_SESSION['Requisition']['Items'];
        $Item = Get_Item($Item_ID);
        $Item_Balance = Get_Item_Balance($Item_ID, $Current_Store_ID);
        $Requisition_Item_ID = count($Requisition_Item_List) - 1;

        $Requisition = Get_Requisition($Requisition_ID);
        $Store_Issue_Item_Balance = Get_Item_Balance($Item_ID, $Requisition['Store_Issue']);

        $Requisition_Item = array(
            "Requisition_Item_ID" => $Requisition_Item_ID + 1,
            "Product_Name" => $Item['Product_Name'],
            "Store_Need_Balance" => $Item_Balance['Item_Balance'],
            "Store_Issue_Balance" => $Store_Issue_Item_Balance['Item_Balance'],
            "Item_ID" => $Item_ID,
            "Quantity_Required" => 0,
            "Requisition_ID" => $Requisition_ID,
            "Item_Remark" => ''
        );
        $Requisition_Item_List = array_merge($Requisition_Item_List, array($Requisition_Item));
        $_SESSION['Requisition']['Items'] = $Requisition_Item_List;

        include_once("./requisition_edit_show_items.php");
    }

?>