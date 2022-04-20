<?php
    @session_start();
    include("./includes/connection.php");
    
    //authentication
    if(!isset($_SESSION['userinfo'])){
        @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
//    if(isset($_SESSION['userinfo'])){
//	if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
//		if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
//			header("Location: ./index.php?InvalidPrivilege=yes");
//		}else{
//		    @session_start();
//		    if(!isset($_SESSION['Storage_Supervisor'])){
//			header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
//		    }
//		}
//	}else{
//		header("Location: ./index.php?InvalidPrivilege=yes");
//	}
//    }else{
//	@session_destroy();
//        header("Location: ../index.php?InvalidPrivilege=yes");
//    }
    
    //get Purchase Order id from the session
    if(isset($_SESSION['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
    }else{
        $Purchase_Order_ID = 0;
    }
    
    if($Purchase_Order_ID != 0 && $Purchase_Order_ID != null && $Purchase_Order_ID != ''){
        $sql_get_total = mysqli_query($conn,"select sum(Price * Quantity_Required) as Total_Amount from tbl_purchase_order_items where Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_get_total);
        if($num > 0){
            while($row = mysqli_fetch_array($sql_get_total)){
                $Total_Amount = $row['Total_Amount'];
            }
        }else{
            $Total_Amount = 0;
        }
    }
    echo '<h4><b>Total : '.number_format($Total_Amount).'</b></h4>';
?>