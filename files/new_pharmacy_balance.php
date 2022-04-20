<?php 
    include("./includes/connection.php");
    $Item_ID = (isset($_GET['Item_ID'])) ? $_GET['Item_ID'] : 0;
    // $Sub_Department_ID = (isset($_GET['Sub_Department_ID'])) ? $_GET['Sub_Department_ID'] : 0;

    $Sub_Department_ID = $_SESSION['Sub_Department_ID'];

    $get_balance = mysqli_query($conn, "SELECT Item_Balance FROM tbl_items_balance WHERE Item_ID = '$Item_ID' AND Sub_Department_ID = $Sub_Department_ID");
    while ($balance_row = mysqli_fetch_assoc($get_balance)) {
        echo $balance_row['Item_Balance'];
    }
?>