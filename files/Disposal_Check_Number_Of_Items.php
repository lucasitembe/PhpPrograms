<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['Disposal_ID'])){
        $Disposal_ID = $_SESSION['Disposal_ID'];
        
        $sql_select = mysqli_query($conn,"select ds.Disposal_ID from tbl_disposal ds, tbl_disposal_items dsi where
                                    ds.Disposal_ID = dsi.Disposal_ID and
                                        ds.Disposal_Status = 'pending' and
                                            ds.Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn));
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