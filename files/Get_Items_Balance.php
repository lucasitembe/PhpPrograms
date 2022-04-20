<?php
    include("./includes/connection.php");
    $Select_Price='';
	
	if(isset($_GET['Sub_Department_ID'])){
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}else{
        $Sub_Department_ID = '';
	}
	
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
	
        $Select_Price="SELECT Item_Balance as balance  FROM tbl_items_balance WHERE Item_ID='$Item_ID' AND Sub_Department_ID='$Sub_Department_ID'";
        $result = mysqli_query($conn,$Select_Price);
        $row = mysqli_fetch_assoc($result);
		
		echo $row['balance'];
    }else{
		echo 'Not available';
    }  
?>