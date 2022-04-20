<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['General_Outward_ID'])){
        $Outward_ID = $_SESSION['General_Outward_ID'];
        
        $sql_select = mysqli_query($conn,"select roi.Outward_Item_ID, roi.Outward_ID, itm.Product_Name, roi.Quantity_Returned, roi.Item_Remark, itm.Unit_Of_Measure
                                    from tbl_return_outward_items roi, tbl_items itm where
                                    itm.Item_ID = roi.Item_ID and
                                    roi.Outward_ID ='$Outward_ID'") or die(mysqli_error($conn));
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