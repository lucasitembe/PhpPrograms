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
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='searchlistofoutpatientbilling.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage' class='art-button-green'>
        OUTPATIENT
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='continueoutpatientbilling.php?ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
        CONTINUOUS
    </a>
<?php  } } ?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='DirectCashsearchlistofoutpatientbilling.php?ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
        DIRECTCASH-OUTPATIENT
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){
?>
    <a href='patientbilling.php?PatientBilling=PatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='search_list_direct_cash_inpatient_billing_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)' onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<fieldset>  
            <legend align=center><b>INTPATIENT LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=320px src='search_list_direct_cash_inpatient_billing_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>