<?php
    @session_start();
    include("./includes/connection.php");
    
    //authentication
    if(!isset($_SESSION['userinfo'])){
        @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    //get Purchase Order id from the session
    if(isset($_SESSION['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
        echo $Purchase_Order_ID;
        //echo "<input type='text' name='Purchase_Number'  id='Purchase_Number' value='".$Purchase_Order_ID."'>";
    }else{
        echo 'New';
        //echo "<input type='text' name='Purchase_Number'  id='Purchase_Number' value='new'>";
    }
?>