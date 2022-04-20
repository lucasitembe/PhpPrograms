<?php
    session_start();
    include("./includes/connection.php");
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    if(isset($_SESSION['Edit_General_Order_ID']) && isset($_GET['Item_ID'])){
        $Store_Order_ID = $_SESSION['Edit_General_Order_ID'];
        
        //get item id
        $Item_ID = $_GET['Item_ID'];
        
        //check if item selected is already selected
        $sql_select = mysqli_query($conn,"select Order_Item_ID from tbl_store_order_items where
                                        Store_Order_ID = '$Store_Order_ID' and
                                            Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);
        
        if($num > 0){
            echo 'Yes';
        }else{
            echo 'No';
        }
        
    }else{
        echo 'No';
    }

?>