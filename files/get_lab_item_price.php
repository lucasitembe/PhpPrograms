<?php
include("includes/connection.php");
$item_id = $_GET['item_id'];
$sponsor_id = $_GET['sponsor_id'];

$sql = "SELECT Items_Price FROM tbl_item_price WHERE Sponsor_ID = '$sponsor_id' AND Item_ID='$item_id'";

$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $Items_Price = $row['Items_Price'];

    }
} else {
    $Items_Price = 0;
}

echo $Items_Price;


?>