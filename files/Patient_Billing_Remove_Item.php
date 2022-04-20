<?php
    @session_start();
    include('./includes/connection.php');
    
    //authentication
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    } else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){
                
                    //get patient registration id for future use
                    if(isset($_GET['Registration_ID'])){
                        $Registration_ID = $_GET['Registration_ID'];
                    }else{
                        $Registration_ID = '';
                    }
		    header("Location: ./receptionsupervisorauthentication.php?Registration_ID=$Registration_ID&InvalidSupervisorAuthentication=yes");
		}
	    } 
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    
    
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }else{
        $Patient_Payment_Item_List_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if($Patient_Payment_Item_List_ID != 0 && $Registration_ID != 0){
        $delete_item = mysqli_query($conn,"delete from tbl_patient_payment_item_list_cache where
                                    Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
        if($delete_item){
            header("Location: ./Patient_Billing_Iframe_Prepare.php?Registration_ID=$Registration_ID");
        }
    }
?>