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
    $select = mysqli_query($conn,"select Price, Quantity from tbl_laboratory_items_list_cache where Registration_ID = '$Registration_ID'
                            and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($row = mysqli_fetch_array($select)){
            $total = $total + (($row['Price'] * $row['Quantity']));
        }
    }
    echo '<h4>Total : '.number_format($total).'</h4>';
?>