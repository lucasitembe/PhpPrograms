<?php
    @session_start();
    include("./includes/connection.php");
       
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    $select_Transaction_Items = mysqli_query($conn,
				 "select Billing_Type
				    from tbl_departmental_items_list_cache alc
				    where alc.Employee_ID = '$Employee_ID' and
				    Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
			     
        $no_of_items = mysqli_num_rows($select_Transaction_Items);
?>
    <td style='text-align: right;'>
<?php
	    if($no_of_items > 0){
	       while($data = mysqli_fetch_array($select_Transaction_Items)){
		  $Billing_Type = $data['Billing_Type'];
	       }
	       if(strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash'){
?>
                <button class="art-button-green" type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" onclick="Pay_Via_Mobile_Function();">CREATE ePayment BILL</button>
                <button class="art-button-green" type="button" onclick="Send_To_Cashier();">SEND TO CASHIER</button>
<?php
	       }else{
?>
                <button class="art-button-green" type="button" onclick="Save_Information();">SAVE INFORMATION</button>
<?php
               }
	    }
?>
	<button class="art-button-green" type="button" onclick="Validate_Type_Of_Check_In();">ADD ITEM</button>
    </td>