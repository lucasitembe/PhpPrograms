<?php
include("./includes/connection.php");

$id=mysqli_real_escape_string($conn,$_POST['id']);

$query=mysqli_query($conn,"DELETE FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='$id'") or die(mysqli_error($conn));