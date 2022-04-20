<?php
    @session_start();
    include("./includes/connection.php");

    if(isset($_GET['Jobcard_Order_ID'])){
        $Jobcard_Order_ID = $_GET['Jobcard_Order_ID'];
    }else{
        $Jobcard_Order_ID = '';
    }

    if(isset($_GET['Jobcard_ID'])){
        $Jobcard_ID = $_GET['Jobcard_ID'];
    }else{
        $Jobcard_ID = '';
    }

    if ($Jobcard_Order_ID != '') {
        echo mysqli_query($conn,"UPDATE tbl_jobcard_orders
                           SET Order_Status = 'saved', Jobcard_ID = '$Jobcard_ID'
                           WHERE Jobcard_Order_ID = '$Jobcard_Order_ID'") or die(mysqli_error($conn));
    }

    echo false;
?>