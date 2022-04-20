<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['Laboratory_Consumption_ID']) && isset($_GET['Item_ID'])){
        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];

        //get item id
        $Item_ID = $_GET['Item_ID'];

        //check if item selected is already selected
        $sql_select = mysqli_query($conn,"select Consumption_Item_ID from tbl_Consumption_items where
                                        Consumption_ID = '$Consumption_ID' and
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