<?php
    session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    //get consumption id
    if(isset($_SESSION['Laboratory_Consumption_ID'])){
        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];
    }else{
        $Consumption_ID = 0;
    }

    if($Consumption_ID != 0 && $Consumption_ID != '' && $Consumption_ID != null){
        //get all items to update consumption and department balance
        $select = mysqli_query($conn,"select Quantity, Item_ID from tbl_consumption_items where Consumption_ID = '$Consumption_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($data = mysqli_fetch_array($select)) {
                $Quantity = $data['Quantity'];
                $Item_ID = $data['Item_ID'];

                //



            }
        }
    }
?>