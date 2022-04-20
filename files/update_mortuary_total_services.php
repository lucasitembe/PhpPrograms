<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    $total = 0;
    $Payment_Cache_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Payment_Cache_ID WHERE Registration_ID = '$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1"))['Payment_Cache_ID'];
    
    $select = mysqli_query($conn,"SELECT Price, Quantity, Discount from tbl_item_list_cache where Payment_Cache_ID = '$Payment_Cache_ID' AND Check_In_Type = 'Mortuary'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($row = mysqli_fetch_array($select)){
            $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
        }
    }
    echo $total;
?>