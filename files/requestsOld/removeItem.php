<?php
include("../includes/connection.php");

$ppil=$_GET['ppil'];
$sql="UPDATE tbl_item_list_cache SET removing_status='yes' WHERE Payment_Item_Cache_List_ID='".$ppil."'";

$result=mysql_query($sql) or die(mysql_error());
echo 1;