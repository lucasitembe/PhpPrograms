<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['General_Inward_ID'])){
        $Inward_ID = $_SESSION['General_Inward_ID'];
        
        $sql_select = mysqli_query($conn,"SELECT
                                      rii.Inward_Item_ID, rii.Inward_ID, itm.Product_Name,
                                      rii.Quantity_Returned, rii.Item_Remark, itm.Unit_Of_Measure
                                   FROM 
                                      tbl_return_inward_items rii, tbl_items itm
                                   WHERE
                                      itm.Item_ID = rii.Item_ID and
                                      rii.Inward_ID ='$Inward_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);
        if($num > 0 ){
            echo 'Yes';
        }else{
            echo 'No';
        }
    }else{
        echo 'No';
    }
?>