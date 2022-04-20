<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ./index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Modify_Cash_information'])){
	    if($_SESSION['userinfo']['Modify_Cash_information'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ./index.php?InvalidPrivilege=yes");
    }
?>

<!-- CHECK USER PRIVILEGES BEFORE TO CONTINUE THE PROCESS-->
    <?php
	//if(isset($_GET['userinfo']['Modify_Cash_information'])){
	//    if(strtolower($_SESSION['userinfo']['Modify_Cash_information']) != 'yes'){
	//	header("Location: ./edittransaction.php?EditTransaction=EditTransactionThisPage&INvalidPrivilege=yes");
	//    }
	//} 
    ?>
<!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date_From").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
        
        $(function () { 
            $("#date_To").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
    <script>
	function Search_Receipt_Number(Receipt_No) {
	    if (Receipt_No != '' || Receipt_No != null) {
		document.getElementById('Edit_Receipt').innerHTML = "<iframe width='100%' height=400px src='edit_patient_cash_billing_iframe.php?Receipt_No="+Receipt_No+"'></iframe>";
	    }
	}
    </script>
    
<!--    end of datepicker script-->


<?php
    /* if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
        INPATIENT
    </a>
<?php  } } */ ?>

<?php
    /*if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='../DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        DIRECT CASH
    </a>
<?php  } } */ ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
?>
    <a href='./edittransaction.php?EditTransaction=EditTransactionThiPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 



<br/><br/>
<center>
    
<?php 
    if(isset($_POST['Print_Filter'])){ 
        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];       
    }
?>
<fieldset>
    <legend align="right"><b>Edit Receipts</b></legend>
    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=100%>
	<tr>
	    <td style="text-align: center;font-size: 16px;font-family: verdana;"><b>Search By Date</b></td>
	    <td style="text-align: center;"><b>From</b></td>
	    <td><input type='text' name='Date_From' id='date_From' required='required'></td> 
	    <td style="text-align: center;"><b>To</b></td>
	    <td><input type='text' name='Date_To' id='date_To' required='required'></td>
	    <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
	    <td style="text-align: center;font-size: 16px;font-family: verdana;"><b>Search By Receipt Number</b></td>
	    <td style="text-align: center"><input type="text" name="Receipt_No" id="Receipt_No" onkeyup='Search_Receipt_Number(this.value)'/></td>
	</tr>
    </table>
    <br>
    <table width=80%>
        <tr>
            <td colspan=7 style='text-align: center;'><?php //echo $status; ?></td>
        </tr>        
    </table>
    </center>
</form>
    <br>
</fieldset>
<fieldset>
    <center>
            <table width=100% border=1>
                <tr>
                    <td id='Edit_Receipt'>
                        <iframe width='100%' height=400px src="edit_patient_cash_billing_iframe.php?Branch=<?php echo $Branch; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Insurance=<?php echo $Insurance; ?>&Patient_Name =<?php echo $Patient_Name; ?>"></iframe>
                    </td>
                </tr>
            </table>
        </center>
</fieldset>
	
<?php
    include("./includes/footer.php");
?>