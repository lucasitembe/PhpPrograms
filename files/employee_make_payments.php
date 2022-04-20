<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
?>




<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
	<a href="employee_voucher_payments_list.php?employee=<?=$_GET['employee']?>" class='art-button-green'>PAYMENT LIST</a>
    <a href='supplier_payments_list.php?SearchListRevenueFromOtherSources=SearchListRevenueFromOtherSourcesThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } }
	$payee_ID=mysqli_real_escape_string($conn,$_GET['employee']);
	$Employee_Name=$_SESSION['userinfo']['Employee_Name'];
	$select_supplier=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE  employee_ID=$payee_ID");
	if(mysqli_num_rows($select_supplier) > 0){
		while($row=mysqli_fetch_assoc($select_supplier)){
			$payee_name	= $row['Employee_Name'];
			$payee_ID	= $row['Employee_ID'];
			 $Email			= "";
			//$Address		= $row['Postal_Address'];
			$Telephone		= $row['Phone_Number'];
		}
	}else{
			$payee_name	= "";
			$payee_ID	= "";
			$Email			= "";
			$Address		= "";
			$Telephone		= "";
	}
 ?>
	
<fieldset>  
    <legend align="right"><b>VOUCHER PAYMENTS: REVENUE CENTER</b></legend>
    <center> 
        <table width="100%">
            <tr>
                <td width='10%' style="text-align:right;"><b>Employee Name</b></td>
                <td width='15%' ><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo "$payee_name"; ?>'></td>
                <td width='11%'style="text-align:right;"><b>Employee Number</b></td>
                <td width='12%'><input type='text' name='Registration_Number' disabled='disabled' id='Registration_Number' value='<?php echo "$payee_ID"; ?>'></td>
                <td style="text-align:right;"><b>Prepared By</b></td>
                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo "$Employee_Name"; ?>'></td>
            
                <td style="text-align:right;"><b>Supervised By</b></td>
                <?php
                if(isset($_SESSION['supervisor'])){
                    if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
                        if($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                            $Supervisor = $_SESSION['supervisor']['Employee_Name'];
                        } else {
                            $Supervisor = "Unknown Supervisor";
                        }
                    } else {
                        $Supervisor = "Unknown Supervisor";
                    }
                } else {
                    $Supervisor = "Unknown Supervisor";
                }
                ?>
                <td><input type='text' name='Supervisor_Name' id='Supervisor_Name' disabled='disabled' value='<?php echo "$Supervisor"; ?>'></td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
            <td style='text-align:right;'><b>Email</b></td><td><input type='text' name='Email' id='Email' disabled='disabled' value='<?php echo "$Email"; ?>'></td>
            </tr>
            
        </table>
    </center>
</fieldset>
<br><hr><br>
<fieldset><legend>Voucher Payments Details</legend>
	<center>
		<form name="submit_cheque_form" action="" method='post'>
		<table width='80%'>
			<tr><td style='text-align:right;'><b>Cheque / Cash: </b></td><td><input type='text' name='cheque_no' id='chequen_no' placeholder='Enter Cheque Number' required='required'></td></tr>
			<tr><td style='text-align:right;'><b>Amount: </b></td><td><input type='text' name='amount' id='amount' placeholder='Enter Amount' required='required'></td></tr>
			<tr><td style='text-align:right;'><b>Amount in words</b></td><td><input type='text' name='word_amount' id='word_amount' placeholder='Enter Amount in words' required='required'></td></tr>
			<!--tr><td style='text-align:right;'><b>Title of Account</b></td><td><input type='text' name='account_title' id='account_title' placeholder='Enter Title of Account' required='required'></td></tr>
			<tr><td style='text-align:right;'><b>Account Code</b></td><td><input type='text' name='account_code' id='account_code' placeholder='Enter Account Code' required='required'></td></tr-->
			<tr><td style='text-align:right;'><b>Payment Details</b></td><td><textarea name='narration' id='narration' style='height:100px;' placeholder='Enter payments Details' required='required'></textarea></td></tr>
			<tr><td style='text-align:right;' colspan='2'><br><input type='reset' name='cancel_voucher' class='art-button-green' id='cancel_voucher' value='Cancel'><input type='submit' name='save_voucher' class='art-button-green' id='save_voucher' value='Submit' onclick='validate_cheque_inputs();'><br><br><br></td></tr>
		</table>
		<input type="hidden" name="submit_invoice" id="submit_invoice" value="submit_invoice">
		</form>
	</center>
</fieldset>
<?php
	if(isset($_POST['submit_invoice'])){
		$cheque_no 		= trim(mysqli_real_escape_string($conn,$_POST['cheque_no']));
		$amount 		= trim(mysqli_real_escape_string($conn,$_POST['amount']));
		$word_amount	= trim(mysqli_real_escape_string($conn,$_POST['word_amount']));
		//$account_title 	= trim(mysqli_real_escape_string($conn,$_POST['account_title']));
		//$account_code 	= trim(mysqli_real_escape_string($conn,$_POST['account_code']));
		$narration  	= trim(mysqli_real_escape_string($conn,$_POST['narration']));
		$voucher_date 	= date("Y-m-d");
		$Employee_ID 	= $_SESSION['userinfo']['Employee_ID'];
		if(!empty($cheque_no) && !empty($amount) && !empty($word_amount) && !empty($narration) && !empty($cheque_no)) {

			$results=mysqli_query($conn,"INSERT INTO tbl_voucher (voucher_date, Supplier_ID, Employee_ID, amount, word_amount,cheque_number, narration,payee_type) VALUES('$voucher_date', '$payee_ID', '$Employee_ID', '$amount', '$word_amount', '$cheque_no', '$narration', 'employee')") or die(mysqli_error($conn));
			if($results){
				echo "<script>
					alert('Payments done successifully');
					window.location.href='employee_make_payments.php?employee=".$payee_ID."';
				</script>";
			}else{
				echo "<script>
					alert('Process failed');
					window.location.href='employee_make_payments.php?employee=".$payee_ID."';
				</script>";
			}
		}else{
			echo "<script>alert('Fill All Fields')</script>";
		}
	}
?>
<script type="text/javascript">
	function validate_cheque_inputs(){
		var cheque_no 		=document.submit_cheque_form.cheque_no.value;
		var amount 			=document.submit_cheque_form.amount.value;
		var word_amount 	=document.submit_cheque_form.word_amount.value;
		//var account_title 	=document.submit_cheque_form.account_title.value;
		//var account_code 	=document.submit_cheque_form.account_code.value;
		var narration 		=document.submit_cheque_form.narration.value;
		var is_valid		=true;
		if(cheque_no.trim() =='' || amount.trim() =='' || word_amount.trim() ==''){
			alert('Fill all fieds before submitting');
			is_valid = false;
		}
		return is_valid;
	}
</script>
<?php
    include("./includes/footer.php");
?>