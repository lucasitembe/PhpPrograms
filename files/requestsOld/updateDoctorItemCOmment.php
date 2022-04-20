<?php

    include("../includes/connection.php");
    
	$ppil=isset($_POST['ppil'])?:0;
	$comm=isset($_POST['comm'])?:'';
	
	//echo $ppil.' comm='.$_POST['ppil'];
	
	$q=mysql_query("UPDATE tbl_item_list_cache SET Doctor_Comment='".$_POST['comm']."' WHERE Payment_Item_Cache_List_ID='".$_POST['ppil']."'") or die(mysql_error());
	//echo 'Success';