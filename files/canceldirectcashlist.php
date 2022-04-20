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

    //get branch
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch = 0;
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    } 

    $Date_From = '';
    $Date_To = '';
    $Insurance = 'All';
    $Patient_Name = '';
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
		document.getElementById('Cancel_Receipt').innerHTML = "<iframe width='100%' height=400px src='cancel_patient_direct_cash_billing_iframe.php?Receipt_No="+Receipt_No+"'></iframe>";
	    }
	}
    </script>
    <script>
	function Search_Patient_Name(Patient_Name) {
	    if (Patient_Name != '' || Patient_Name != null) {
		document.getElementById('Cancel_Receipt').innerHTML = "<iframe width='100%' height=400px src='cancel_patient_direct_cash_billing_iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
	    }
	}
    </script>
<!--    end of datepicker script-->


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
            if(isset($_GET['Section'])){
	    if(strtolower($_GET['Section']) == 'generalledger'){
?>
    <a href='edittransaction.php?Section=GeneralLedger&EditTransaction=EditTransactionThiPage' class='art-button-green'>
        BACK
    </a>
<?php
	    }else{
?>
    <a href='edittransaction.php?EditTransaction=EditTransactionThiPage' class='art-button-green'>
        BACK
    </a>
?>
  
    <?php  } }}} ?>
 



<br/><br/>
<center>

<?php 
    if(isset($_POST['Print_Filter'])){ 
        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];       
    }
?>
<fieldset>
    <legend align="right"><b>Cancel Direct Cash Receipts</b></legend>
<form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data">
    <table width=100%>
        <tr>
	     
            <td style='text-align: center;'><b>Start Date</b></td>
	    <td><input type='text' name='Date_From' id='date_From' required='required' style="text-align: center;"></td>
            <td style='text-align: center;'><b>End Date</b></td>
	    <td><input type='text' name='Date_To' id='date_To' required='required' style="text-align: center;"></td>
	    <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
	    <td style="text-align: center"><input type="text" name="Receipt_No" placeholder="Search By Receipt Number" id="Receipt_No" oninput='Search_Receipt_Number(this.value)'/></td>
		<td style="text-align: center"><input type="text" name="Receipt_No" placeholder="Search By Patient Name" id="Receipt_No" oninput='Search_Patient_Name(this.value)'/></td>
        </tr>
    </table>
    <table width=80%>
        <tr>
            <td colspan=7 style='text-align: center;'><?php //echo $status; ?></td>
        </tr>        
    </table>
</center>
</form>
</fieldset>
<br/>
<fieldset>
    <center>
            <table width=100% border=1>
                <tr>
                    <td id="Cancel_Receipt">
                        <iframe width='100%' height=400px src="cancel_patient_direct_cash_billing_iframe.php?Branch=<?php echo $Branch; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Insurance=<?php echo $Insurance; ?>&Patient_Name =<?php echo $Patient_Name; ?>"></iframe>
                    </td>
                </tr>
            </table>
        </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>