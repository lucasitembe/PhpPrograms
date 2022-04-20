 <?php
    session_start();
    include("./includes/connection.php");
	
	 if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    if(isset($_SESSION['userinfo'])){
       if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		//die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
	    }
	}else{
	    	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

	}
      
    }else{
	@session_destroy();
	   	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    
    
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    
    if(isset($_GET['Re_Order_Value'])){
        $Re_Order_Value = $_GET['Re_Order_Value'];
    }else{
        $Re_Order_Value = '';
    }
    
    if(isset($_GET['Filter_Option'])){
        $Filter_Option = $_GET['Filter_Option'];
    }else{
        $Filter_Option = false;
    }
    
    if(isset($_GET['Filter_Option'])){
        if($Sub_Department_ID != null && $Sub_Department_ID != '' && $Re_Order_Value != '' && $Sub_Department_ID != 0){
            $sql_update = mysqli_query($conn,"update tbl_reagent_items_balance set Reorder_Level = '$Re_Order_Value', Reorder_Level_Status = 'normal' where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            echo 'yes';
        }elseif($Sub_Department_ID != null && $Sub_Department_ID != '' && $Re_Order_Value != '' && $Sub_Department_ID == 0){
            $sql_update = mysqli_query($conn,"update tbl_reagent_items_balance set Reorder_Level = '$Re_Order_Value', Reorder_Level_Status = 'normal'") or die(mysqli_error($conn));
            echo 'yes';            
        }else{
            echo 'no';
        }
    }else{
        if($Sub_Department_ID != '' && $Re_Order_Value != '' && $Sub_Department_ID != 0){
            $sql_update = mysqli_query($conn,"update tbl_reagent_items_balance set Reorder_Level = '$Re_Order_Value' where
                                        Sub_Department_ID = '$Sub_Department_ID' and
                                            Reorder_Level_Status = 'normal'") or die(mysqli_error($conn));
            echo 'yes';
        }elseif($Sub_Department_ID != '' && $Re_Order_Value != '' && $Sub_Department_ID == 0){
            $sql_update = mysqli_query($conn,"update tbl_reagent_items_balance set Reorder_Level = '$Re_Order_Value' where
                                      Reorder_Level_Status = 'normal'") or die(mysqli_error($conn));
            echo 'yes';            
        }else{
            echo 'no';
        }
    }
?>