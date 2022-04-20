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


    //get sub department id
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
//    sub department_id
     if(isset($_GET['Item_ID'])){
        $Store_Issue = $_GET['Store_Issue'];
    }else{
        $Store_Issue = 0;
    }
    
//    if(isset($_POST['action'])){
//        if($_POST['action']=='save'){
//        $id=$_POST['id'];
//        $Store_Issue=$_POST['Store_Issue'];
//          $select_Balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
//                                    Item_ID = '$id' and Sub_Department_ID = '$Store_Issue'") ;//or die(mysqli_error($conn));  
//          $result=  mysqli_fetch_assoc($select_Balance);
////            echo $result['Item_Balance'];
//        } 
//    }

        //get Item Balance
    $select_Balance = mysqli_query($conn,"select Item_Balance, Item_Temporary_Balance from tbl_items_balance where
                                    Item_ID = '$Item_ID' and
                                    Sub_Department_ID = '$Store_Issue'");// or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_Balance);
    if($num > 0){
        while($row = mysqli_fetch_array($select_Balance)){
            $Item_Balance2 = $row['Item_Balance'];
            $Item_Temporary_Balance2 = $row['Item_Temporary_Balance'];
        }
    }else{
        //insert item if not available (with zero balance)
        mysqli_query($conn,"insert into tbl_items_balance(Item_ID, Sub_Department_ID) 
                        VALUES ('$Item_ID','$Store_Issue')");//or die(mysqli_error($conn));
        $Item_Balance2 = 0;
        $Item_Temporary_Balance2 = 0;
    }
    
    //get sub department name
    $select_name = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_name);
    if($num > 0){
        while($row = mysqli_fetch_array($select_name)){
            $Sub_Department_Name = $row['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }

    //get Item Balance
    $select_Balance = mysqli_query($conn,"select Item_Balance, Item_Temporary_Balance from tbl_items_balance where
                                    Item_ID = '$Item_ID' and
                                    Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_Balance);
    if($num > 0){
        while($row = mysqli_fetch_array($select_Balance)){
            $Item_Balance = $row['Item_Balance'];
            $Item_Temporary_Balance = $row['Item_Temporary_Balance'];
        }
    }else{
        //insert item if not available (with zero balance)
        mysqli_query($conn,"insert into tbl_items_balance(Item_ID, Sub_Department_ID) 
                        VALUES ('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
        $Item_Balance = 0;
        $Item_Temporary_Balance = 0;
    }

    $Balance = $Item_Balance; // - $Item_Temporary_Balance;
    echo $Balance;
?>