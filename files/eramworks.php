<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['eRAM_Works'])){
	    if($_SESSION['userinfo']['eRAM_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['eRAM_Works'] == 'yes'){
?>
<?php  } } ?>
<br/><br/>
<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>
<script type='text/javascript'>
    function searchByName(patientName) {
        document.getElementById('srchfrm').src = "eramworkspatientfile_Iframe.php?name="+patientName;
    }
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<fieldset>
        <center>
	    <b>eRAM WORKS</b>
            <p>Consulted Patients</p>
            <input type='text' placeholder='----------------Andika Jina La Mgonjwa Hapa Kutafuta----------------' style='width: 30%' id='patientName' name='patientName' onkeyup='searchByName(this.value)'>
        </center>
</fieldset>
<fieldset>
        <iframe id='srchfrm' src='eramworkspatientfile_Iframe.php' style='width: 100%; height:360px;'>
        </iframe>
</fieldset>
<?php
    include("./includes/footer.php");
?>