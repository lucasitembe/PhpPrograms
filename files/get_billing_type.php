<?php
include("includes/connection.php");
$sponsor_id = $_GET['sponsor_id'];
$sql = "SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsor_id'";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

while ($row = mysqli_fetch_assoc($result)) {
    extract($row);
}
echo $payment_method;
?>