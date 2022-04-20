<?php
    include("./includes/connection.php");
    if(isset($_GET['laundry_in_or_out_ID'])){
        $laundry_in_or_out_ID = $_GET['laundry_in_or_out_ID'];
    }else{
        $laundry_in_or_out_ID = 2;
    }
	
	 if(isset($_GET['Quantity1'])){
        $Quantity1 = $_GET['Quantity1'];
    }else{
        $Quantity1 = 2;
    }
	
	 if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = 2;
    }
    
   /*  $Select_Update = "UPDATE tbl_laundry_out_cache
					SET Quantity='$Quantity1'
                            where  laundry_in_or_out_ID= '$laundry_in_or_out_ID'";
    $result = mysqli_query($conn,$Select_Update); */
   
	
 
	$diff=(($Quantity)-($Quantity1));
	echo $diff;
	// $Age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
?>