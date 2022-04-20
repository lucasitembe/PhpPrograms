<?php
    session_start();
    include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    
   
    
    if(isset($_GET['Payment_Cache_ID'])){
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    }else{
        $Payment_Cache_ID = '';
    }

       //add selected item
		$checkExist=mysqli_query($conn,"SELECT * FROM tbl_item_list_cache WHERE Item_ID='$Item_ID' AND Payment_Cache_ID='$Payment_Cache_ID' AND Status='active'");
		$num_rows=mysqli_num_rows($checkExist);
		
		if($num_rows>0){
			echo 1;
		}
?>