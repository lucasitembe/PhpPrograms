<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ./index.php?InvalidPrivilege=yes");
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
	    header("Location: ./index.php?InvalidPrivilege=yes");
    }
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
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='./revenuecenterreports.php?RevenueCenterReports=RevenueCenterReportsThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 



<br/><br/>
<center>
    
<?php 
    if(isset($_POST['Print_Filter'])){
        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];
        $Insurance = $_POST['Insurance'];
        $Payment_Type = $_POST['Payment_Type'];
        $Patient_Type = $_POST['Patient_Type'];
        $Title_Control = "True";
    }else{
	$Date_From = "";
        $Date_To = "";
        $Insurance = "";
        $Payment_Type = "";
        $Patient_Type = "";
    }
?>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=60%>
        <tr>
            <td style='text-align: center;'><b>From</b></td>
            <td style='text-align: center;'><b>To</b></td>
            <td style='text-align: center;'><b>Insurance</b></td>
            <td><b>Patient Type</b></td>
            <td><b>Payment Type</b></td>
        </tr>
        <tr>
            <td><input type='text' name='Date_From' id='date_From' required='required'></td> 
            <td><input type='text' name='Date_To' id='date_To' required='required'></td>
            <td style='text-align: center;'>
                <select name='Insurance' id='Insurance'>
                    <option selected='selected'>All</option>
                    <?php
                        $data = mysqli_query($conn,"select * from tbl_sponsor");
                        while($row = mysqli_fetch_array($data)){
                            echo '<option>'.$row['Guarantor_Name'].'</option>';
                        }
                    ?>
                </select>
            </td>
            <td style='text-align: center;'>
                <select name='Patient_Type' id='Patient_Type'>
                    <option selected='selected'>All</option>
                    <option>Outpatient</option>
                    <option>Inpatient</option>
                </select>
            </td> 
            <td style='text-align: center;'>
                <select name='Payment_Type' id='Payment_Type'>
                    <option selected='selected'>All</option>
                    <option>Cash</option>
                    <option>Credit</option>
                </select>
            </td>
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td> 
        </tr>
    </table>
    <table width=80%>
        <tr>
            <td colspan=7 style='text-align: center;'>
		<?php
		    if(strtolower($Title_Control) == 'true'){
			echo "<span style='color: green; text_align: center;'>
				<b>PAYMENT DETAILS FROM ".date('d/m/Y',strtotime($Date_From))." TO ".date('d/m/Y',strtotime($Date_To))."</b>
				</span>";
		    }
		?>
	    </td>
        </tr>
        
    </table>
</center>
<fieldset>  
        <center>
            <table width=100% border=1>
                <tr>
                    <td>
                        <iframe width='100%' height=275px src="Time_Range_Summary_report_Iframe.php?Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Payment_Type=<?php echo $Payment_Type; ?>&Insurance=<?php echo $Insurance; ?>&Patient_Type=<?php echo $Patient_Type; ?>"></iframe>
                    </td>
                </tr>
            </table>
        </center>
</fieldset><br/></form>
<?php
    include("./includes/footer.php");
?>