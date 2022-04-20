<?php
	session_start();
	include("./includes/connection.php");
	$total = 0;

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = '';
	}

	if(isset($_GET['Sub_Department_ID'])){
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}else{
		$Sub_Department_ID = '';
	}

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}
    
    $select = mysqli_query($conn,"select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
    						from tbl_item_list_cache ilc, tbl_Items its
                            where ilc.item_id = its.item_id and
                            ilc.status = 'active' and
                            ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                            ilc.Sub_Department_ID = '$Sub_Department_ID' and
                            ilc.Transaction_Type = '$Transaction_Type' and
                            ilc.Check_In_Type = 'Procedure'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
    if($no > 0){
        while($row = mysqli_fetch_array($select)){
        	if($row['Edited_Quantity'] == 0){  
                $Quantity = $row['Quantity'];
            }else{
                $Quantity = $row['Edited_Quantity'];
            }
            $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
        }
    }
?>
<b>GRAND TOTAL : <?php echo number_format($total); ?></b>&nbsp;&nbsp;&nbsp;