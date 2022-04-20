 <?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Sub_Department_ID2'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID2'];
    }else{
        $Sub_Department_ID = '';
    }
    
    if(isset($_GET['New_Re_Order_Level_Value'])){
        $New_Re_Order_Level_Value = $_GET['New_Re_Order_Level_Value'];
    }else{
        $New_Re_Order_Level_Value = '';
    }
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    
    if($Sub_Department_ID != null && $Sub_Department_ID != '' && $New_Re_Order_Level_Value != null && $New_Re_Order_Level_Value != '' && $Item_ID != '' && $Item_ID != null){
        if($Sub_Department_ID != 0){
            $sql_update = mysqli_query($conn,"update tbl_reagent_items_balance set Reorder_Level = '$New_Re_Order_Level_Value', Reorder_Level_Status = 'specific' where
                                        Sub_Department_ID = '$Sub_Department_ID' and
                                            Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        }else{
            $sql_update = mysqli_query($conn,"update tbl_reagent_items_balance set Reorder_Level = '$New_Re_Order_Level_Value', Reorder_Level_Status = 'specific' where
                                            Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        }
    }
    if($sql_update){
        echo $New_Re_Order_Level_Value;
    }
?>