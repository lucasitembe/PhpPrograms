<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Radiology_Works'])){
	    if($_SESSION['userinfo']['Radiology_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){
?>
    <a href='radiologyworkspage.php?BackToRadiologyPage=RadiologyMenuPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center"><b> RADIOLOGY PARAMETER SETUP</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='addRadiologyparameter.php?EnterRadiologyParameter=RadiologyParameterParameterPage'>
                        <button style='width: 100%; height: 100%'>
                           Enter parameter
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Enter parameter
                        </button>
                  
                    <?php } ?>
                </td>
                </tr>
				
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>