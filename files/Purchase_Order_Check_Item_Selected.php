<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_GET['Item_ID'])){
        
        //get item id
        $Item_ID = $_GET['Item_ID'];
        
        //check if item selected is already selected
        $sql_select = mysqli_query($conn,"select Item_ID from tbl_purchase_cache where
                                    Item_ID = '$Item_ID' and
                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
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