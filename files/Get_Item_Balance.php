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
    
    
    if($ControlValue != '' && $ControlValue == 'Revenue_Center'){
        //get Item Balance
        $select_Balance = mysqli_query($conn,"select Item_Balance, Item_Temporary_Balance from tbl_Items_Balance where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_Balance);
        if($num > 0){
            while($row = mysqli_fetch_array($select_Balance)){
                $Item_Balance = $row['Item_Balance'];
                $Item_Temporary_Balance = $row['Item_Temporary_Balance'];
            }
        }else{
            $Item_Balance = 0;
            $Item_Temporary_Balance = 0;
        }
        
        $Balance = $Item_Balance - $Item_Temporary_Balance;
        
    }else{
        //get Item Balance
        $select_Balance = mysqli_query($conn,"select Item_Balance from tbl_Items_Balance where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_Balance);
        if($num > 0){
            while($row = mysqli_fetch_array($select_Balance)){
                $Item_Balance = $row['Item_Balance'];
            }
        }else{
            $Item_Balance = 0;
        }
        $Balance = $Item_Balance;
    }
    echo $Balance;
?>