<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Rch_Works'])){
	    if($_SESSION['userinfo']['Rch_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
                    @session_start();
                    if(!isset($_SESSION['Rch_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Rch&InvalidSupervisorAuthentication=yes");
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
            <legend align=center><b>RCH WORKS</b></legend>
        <center><table width = 60%>
<!--            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Rch_Works'] == 'yes'){ ?>
                    <a href='searchvisitorsoutpatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Ongeza Mgonjwa RCH
                        </button>
                    </a>
                    <?php } ?>
                     
                        
                </td>
                
                
            </tr>-->
            
            
             <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Rch_Works'] == 'yes'){ ?>
                    <a href='searchvisitorsfamilyplaningpatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Rejesta ya Uzazi wa Mpango
                        </button>
                    </a>
                    <?php } ?>
                     
                        
                </td>  
            </tr>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Rch_Works'] == 'yes'){ ?>
                    <a href='Postnatal_Rejester.php?section=Rch&RchWorks=RchWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Rejesta ya Mtoto na Mama baada ya Kujifungua
                        </button>
                    </a>
                    <?php } ?>
                     
                        
                </td>
            </tr>
		
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Rch_Works'] == 'yes'){ ?>
                    <a href='searchvisitorsWajawazitopatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Rejesta ya Wajawazito
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Rejesta ya Wajawazito 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr> 
            
            <tr>
				
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Rch_Works'] == 'yes'){ ?>
                    <a href='searchvisitorsWatotopatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Rejesta ya Watoto
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Rejesta ya Watoto
                        </button>
                  
                    <?php } ?>
                </td>
            </tr> 
            
             <tr>
				
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Rch_Works'] == 'yes'){ ?>
                    <a href='searchvisitorsWazazipatientlistforrch.php?section=Rch&RchWorks=RchWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Rejesta ya Wazazi
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Rejesta ya Wazazi
                        </button>
                  
                    <?php } ?>
                </td>
            </tr> 
            
            
            <tr>
				
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Rch_Works'] == 'yes'){ ?>
                    <a href='searchvisitorsLabourrch.php?section=Rch&RchWorks=RchWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Labour,Delivery,Postnatal case notes
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Labour,Delivery,Postnatal case notes
                        </button>
                  
                    <?php } ?>
                </td>
            </tr> 
            
            <tr>
				
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Rch_Works'] == 'yes'){ ?>
                    <a href='searchvisitorsoutpatientlistrchmahudhurio.php?section=Rch&RchWorks=RchWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Mahudhurio
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Mahudhurio
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