<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Dental_Works'])){
	    if($_SESSION['userinfo']['Dental_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
                    @session_start();
                    if(!isset($_SESSION['Dental_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Dental&InvalidSupervisorAuthentication=yes");
                    }
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
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>DENTAL WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Dental_Works'] == 'yes'){ ?>
                    <a href='#?DentalWorksPage=DentalWorksPageThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Work Forms
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Work Forms 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
        <?php
            $Purchase_Session = $_SESSION['userinfo']['Dental_Works'];
            $Session_Category = 'Dental';
            include("./includes/Purchase_Menu.php");
        ?>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Dental_Works'] == 'yes'){ ?>
                    <a href='./dentalclinicalnotes.php?DentalClinicalnotes=DentalClinicalnotesThispage'>
                        <button style='width: 100%; height: 100%'>
                            Clinical Notes
                        </button>
                    </a>
                    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Clinical Notes
                        </button>
                    <?php } ?>
                </td>
            </tr>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Dental_Works'] == 'yes'){ ?>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Reports
                        </button>
                    </a>
                    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Reports 
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