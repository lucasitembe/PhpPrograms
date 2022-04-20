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
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br>
<br>
<br>
<fieldset>
        <legend align=center><b>PATIENT BILLING WORKS</b></legend>
        <center>
	    <table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php
                    if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ ?>
		    <a href='inpatientbill.php?InPatientsBillingWorks=InPatientsBillingWorks'>
			<button style='width: 100%; height: 100%'>
                            <b>Inpatient Bill</b>
                        </button>
		    </a>
		    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Inpatient Bill</b>
                        </button>
                    <?php }?>
                </td>
	    </tr>
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>