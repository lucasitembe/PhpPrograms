<?php
    @session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    /*if(isset($_SESSION['userinfo'])){
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
    }*/
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if($Registration_ID != 0 && $Employee_ID != 0){
        $select_billing_type = mysqli_query($conn,"select Billing_Type from tbl_adhoc_items_list_cache where
                                                    Employee_ID = '$Employee_ID' and
                                                        Registration_ID = '$Registration_ID' order by Adhoc_Item_ID desc limit 1") or die(mysqli_error($conn));
            $num_of_rows = mysqli_num_rows($select_billing_type);
            if($num_of_rows > 0){
                while($row = mysqli_fetch_array($select_billing_type)){
                    $Selected_Billing_Type = $row['Billing_Type'];
                }   
        ?>
            <select name='Billing_Type' id='Billing_Type' required='required' onchange='Get_Item_Price2()'>
            <option selected='selected'><?php echo $Selected_Billing_Type; ?></option>
            </select>			
        
        <?php
            }else{
        ?>
            <select name='Billing_Type' id='Billing_Type' required='required' onchange='Get_Item_Price2()'>
            <option selected='selected'></option>
            <option>Outpatient Cash</option>
            <option>Outpatient Credit</option>
            </select>				    
        <?php    
            }   
    }
?>