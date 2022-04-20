<?php
    session_start();
    include("./includes/connection.php");
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    
    if(isset($_GET['ControlValue'])){
        $ControlValue = $_GET['ControlValue'];
    }else{
        $ControlValue = '';
    }
    
    
    //get store name
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = '';
    }

    if($ControlValue != '' && $ControlValue == 'Storage' && $ControlValue != null && $Sub_Department_ID != '' && $Sub_Department_ID != null){
        //get Item Balance
        $select_Balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
                                        Item_ID = '$Item_ID' and
                                            Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_Balance);
        if($num > 0){
            while($row = mysqli_fetch_array($select_Balance)){
                $Item_Balance = $row['Item_Balance'];
            }
        }else{
            //insert to tbl_item_balance
            mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) value('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
            $Item_Balance = 0;
        }
        
        $Balance = $Item_Balance;
        
    }else{
        $Balance = 0;
    }
    echo $Balance;
?>