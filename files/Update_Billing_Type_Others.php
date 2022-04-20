<?php
    @session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    
    //get sponsor name
    if($Registration_ID != 0){
	$select_sponsor = mysqli_query($conn,"select Guarantor_Name from tbl_Patient_Registration pr, tbl_Sponsor sp where
					pr.Sponsor_ID = sp.Sponsor_ID and
					    pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num_rows = mysqli_num_rows($select_sponsor);
	    if($num_rows > 0){
		while($row = mysqli_fetch_array($select_sponsor)){
		    $Guarantor_Name = $row['Guarantor_Name'];
		}
	    }else{
		$Guarantor_Name = '';
	    }
    }else{
	$Guarantor_Name = '';
    }
    
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    
    if($Registration_ID != 0 && $Employee_ID != 0){
        $select_billing_type = mysqli_query($conn,"select Billing_Type from tbl_reception_items_list_cache_others where
                                                    Employee_ID = '$Employee_ID' and
                                                        Registration_ID = '$Registration_ID' order by Reception_List_Item_ID desc limit 1") or die(mysqli_error($conn));
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
		if(strtolower($Guarantor_Name) == 'cash'){
        ?>
        
	    <select name='Billing_Type' id='Billing_Type' required='required' onchange='Get_Item_Price2()'>
            <option selected='selected'></option>
            <option>Outpatient Cash</option> 
            </select>				    
        
	<?php }else{ ?>
	
            <select name='Billing_Type' id='Billing_Type' required='required' onchange='Get_Item_Price2()'>
            <option selected='selected'></option>
            <option>Outpatient Cash</option>
            <option>Outpatient Credit</option>
            </select>				    	    
	    
        <?php }   }    } ?>