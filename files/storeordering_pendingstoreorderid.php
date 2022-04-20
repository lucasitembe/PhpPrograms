<?php
    @session_start();
    include("./includes/connection.php");

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }

    $Store_Order_ID = "";

    $Pending_Store_Order_ID_SQL = mysqli_query($conn,"SELECT Store_Order_ID
                                       FROM tbl_store_orders
                                       WHERE Sub_Department_ID = '$Sub_Department_ID' AND
                                       Employee_ID = '$Employee_ID' AND
                                       Order_Status = 'pending' AND
                                       Control_Status IN ('available','pending')") or die(mysqli_error($conn));

    $Pending_Store_Order_ID_Num = mysqli_num_rows($Pending_Store_Order_ID_SQL);
    if($Pending_Store_Order_ID_Num > 0){
        while($row = mysqli_fetch_array($Pending_Store_Order_ID_SQL)){
            $Store_Order_ID = $row['Store_Order_ID'];
        }
    }

    echo $Store_Order_ID;
?>