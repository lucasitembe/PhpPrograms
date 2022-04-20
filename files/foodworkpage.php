<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='laundryfoodpage.php?BloodBank=BloodBankThisPage' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/>
<br/><br/>
<fieldset>  
            <legend align=center><b>FOOD WORKS</b></legend>
        <center><table width = 60%>
           
				
			<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='foodpagerestriction.php'>
                        <button style='width: 100%; height: 100%'>
                            Patients Restriction Setup
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patients Restriction Setup 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr> 	
			 <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='foodpage.php'>
                        <button style='width: 100%; height: 100%'>
                            Food Transaction
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Food Transaction 
                        </button>
                  
                    <?php } ?>
                </td>
              </tr> 
			  
			   <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='foodkitchen.php'>
                        <button style='width: 100%; height: 100%'>
                            Menu Of Today
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
								Menu Of Today
                        </button>
                  
                    <?php } ?>
                </td>
                </tr>
			  <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='foodpagecomment.php'>
                        <button style='width: 100%; height: 100%'>
                            Food Dietary Comments
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Food Dietary Comments 
                        </button>
                  
                    <?php } ?>
                </td>
              </tr>
			  
			    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='foodbilling.php'>
                        <button style='width: 100%; height: 100%'>
                            Patients Food Billing 
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patients Food Billing  
                        </button>
                  
                    <?php } ?>
                </td>
              </tr>
				
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='foodmpdfrial.php?Registration_ID=16'>
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
				
				
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='Foodsetup.php'>
                        <button style='width: 100%; height: 100%'>
                            Food Menu Setup
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Food Menu Setup 
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