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




<!-- link menu -->
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='Checked In - Outpatient List'){
	    document.location = "searchlistofoutpatientbilling.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage";
	}else if (patientlist=='Checked In - Inpatient List') {
	    document.location = "searchlistofinpatientbilling.php?SearchListPatientBilling=SearchListPatientBillingThisPage";
	}else if (patientlist=='Direct Cash - Outpatient') {
	    document.location = "DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
	}else if (patientlist=='Direct Cash - Inpatient') {
	    document.location = "DirectCashsearchlistinpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
	}else if (patientlist=='AdHOC Payments - Outpatient') {
	    document.location = "continueoutpatientbilling.php?ContinuePatientBilling=ContinuePatientBillingThisPage";
	}else if (patientlist=='Patient from outside') {
	    document.location = "#";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <option selected='selected'>Chagua Orodha Ya Wagonjwa</option>
    <option>
	Checked In - Outpatient List
    </option>
    <option>
	Checked In - Inpatient List
    </option>
    <option>
	Direct Cash - Outpatient
    </option>
    <option>
	Direct Cash - Inpatient
    </option>
    <option>
	AdHOC Payments - Outpatient
    </option>
    <!--<option>
	Patient from outside
    </option>-->
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> 




<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

 

 

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='search_list_Continue_patient_billing_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<br/>
<center>
    <table style="width:40%;margin-top:10px;">
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)' onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
    </table>
</center>
<br>
<fieldset>  
            <legend align=center><b>AdHOC Payments/ CONTINUING OUTPATIENT LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=380px src='search_list_Continue_patient_billing_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>