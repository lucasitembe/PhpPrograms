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
    $select = mysqli_query($conn,"select Price, Quantity, Discount from tbl_pharmacy_inpatient_items_list_cache where Registration_ID = '$Registration_ID'
                            and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($row = mysqli_fetch_array($select)){
            $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
        }
    }
   // echo '';
     //   if($_SESSION['systeminfo']['price_precision'] == 'yes'){ echo number_format($total, 2); }else{ echo number_format($total); }
    //echo '';
    echo $total;
?>