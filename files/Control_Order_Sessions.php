<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['New_Order'])){
        unset($_SESSION['General_Order_ID']);
        header("Location: ./storeordering.php?status=new&NPO=True&StoreOrder=StoreOrderThisPage");
    }elseif(isset($_GET['Pending_Order']) && isset($_GET['Order_ID'])){
        $_SESSION['General_Order_ID'] = $_GET['Order_ID'];
        header("Location: ./storeordering.php?Status=PendingStoreOrder&StoreOrder=StoreOrderThisPage");
    }else{
        echo 'Something was wrong!!';
    }
?>