<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
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
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){
?>
<?php  } } ?>
<a href='doctorspageoutpatientwork.php?<?php if(isset($Registration_ID)){echo "Registration_ID=$Registration_ID&";} ?><?php
	if(isset($_GET['Patient_Payment_ID'])){
	echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	}
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	} ?>PatientBilling=PatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<br/><br/>
<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>
<script type='text/javascript'>
    function searchByName(patientName) {
        document.getElementById('srchfrm').src = "doctorpatientlistfile_Iframe.php?name="+patientName;
    }
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<fieldset>
        <center>
	    <b>DOCTORS WORKPAGE OUTPATIENT</b>
            <p>Consulted Patients</p>
            <input type='text' placeholder='----------------Andika Jina La Mgonjwa Hapa Kutafuta----------------' style='width: 30%' id='patientName' name='patientName' onkeyup='searchByName(this.value)'>
        </center>
</fieldset>
<fieldset>
        <iframe id='srchfrm' src='doctorpatientlistfile_Iframe.php' style='width: 100%; height:360px;'>
        </iframe>
</fieldset>
<?php
    include("./includes/footer.php");
?>