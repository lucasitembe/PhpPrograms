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
    <a href='./index.php?Dashboard=DashboardThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>DOCTOR'S WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ ?>
                    <a href='doctorsworkspage.php?RevisitedPatient=RevisitedPatientThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Doctor's Page Outpatient Works 
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Page Outpatient Works 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
			 <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ ?>
                    <a href='doctorsinpatientworkspage.php'>
                        <button style='width: 100%; height: 100%'>
                            Doctor's Page Inpatient Works 
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Doctor's Page Inpatient Works 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
	   
                
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>reports