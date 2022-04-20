<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    
    if(isset($_GET['Re_Order_Value'])){
        $Re_Order_Value = $_GET['Re_Order_Value'];
    }else{
        $Re_Order_Value = '';
    }

    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = '';
    }

    

    
    if(isset($_GET['Filter_Option'])){
        $Filter_Option = $_GET['Filter_Option'];
    }else{
        $Filter_Option = false;
    }
    
    if(isset($_GET['Filter_Option'])){
        if($Sub_Department_ID != null && $Sub_Department_ID != '' && $Re_Order_Value != '' && $Sub_Department_ID != 0){
            $sql_update = mysqli_query($conn,"UPDATE tbl_items_balance set Reorder_Level = '$Re_Order_Value', Employee_ID = '$Employee_ID', Update_Date_Time = NOW(), Reorder_Level_Status = 'normal' where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            echo 'yes';
        }elseif($Sub_Department_ID != null && $Sub_Department_ID != '' && $Re_Order_Value != '' && $Sub_Department_ID == 0){
            $sql_update = mysqli_query($conn,"UPDATE tbl_items_balance set Reorder_Level = '$Re_Order_Value', Employee_ID = '$Employee_ID', Update_Date_Time = NOW(), Reorder_Level_Status = 'normal'") or die(mysqli_error($conn));
            echo 'yes';            
        }else{
            echo 'no';
        }
    }else{
        if($Sub_Department_ID != '' && $Re_Order_Value != '' && $Sub_Department_ID != 0){
            $sql_update = mysqli_query($conn,"UPDATE tbl_items_balance set Reorder_Level = '$Re_Order_Value', Employee_ID = '$Employee_ID', Update_Date_Time = NOW() where
                                        Sub_Department_ID = '$Sub_Department_ID' and
                                            Reorder_Level_Status = 'normal'") or die(mysqli_error($conn));
            echo 'yes';
        }elseif($Sub_Department_ID != '' && $Re_Order_Value != '' && $Sub_Department_ID == 0){
            $sql_update = mysqli_query($conn,"UPDATE tbl_items_balance set Reorder_Level = '$Re_Order_Value', Employee_ID = '$Employee_ID', Update_Date_Time = NOW() where
                                      Reorder_Level_Status = 'normal'") or die(mysqli_error($conn));
            echo 'yes';            
        }else{
            echo 'no';
        }
    }
?>