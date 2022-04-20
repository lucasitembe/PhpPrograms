<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
	    if($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes'){
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
  /*  if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
        INPATIENT LIST
    </a>
<?php  } }*/ ?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ 
?>
    <a href='inpatientbill.php?section=Admission&PatientFile=PatientFileThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

 

 

<script language="javascript" type="text/javascript">
    function searchPatient(){
	var Patient_Name = document.getElementById('Patient_Name').value; 
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='admittedpatientlistbillingwork_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Patient_Name' id='Patient_Name' onclick='searchPatient()' onkeyup='searchPatient()' placeholder='Patient Name'>
            </td> 
        </tr>
    </table>
</center>
    <fieldset>  
        <legend align=center><b>ADMITTED PATIENTS LIST</b></legend>
	    <center>
		<table width=100% border=1>
		    <tr>
			<td id='Search_Iframe'>
			    <iframe width='100%' height=320px src='admittedpatientlistbillingwork_Iframe.php?Patient_Name=Patient_Name'></iframe>
			</td>
		    </tr>
		</table>
	    </center>
    </fieldset><br/>
<?php
    include("./includes/footer.php");
?>