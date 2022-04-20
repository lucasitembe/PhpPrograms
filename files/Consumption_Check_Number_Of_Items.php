<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['Laboratory_Consumption_ID'])){
        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];

        $sql_select = mysqli_query($conn,"select Consumption_ID from tbl_Consumption_items where
                                    Consumption_ID = '$Consumption_ID'") or die(mysqli_error($conn));
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