<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Morgue_Works'])){
	    if($_SESSION['userinfo']['Morgue_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='morguepage.php' class='art-button-green'>
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
            <legend align="center"><b>MORGUE WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='addreason.php?ReasonForDeath=ReasonForDeathPage'>
                        <button style='width: 100%; height: 100%'>
                            REASON FOR DEATH
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            REASON FOR DEATH 
                        </button>
                  
                    <?php } ?>
                </td>
                </tr>
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='morgueEntername.php?EnterMorgueName=EnterMorgueNamePage'>
                        <button style='width: 100%; height: 100%'>
                            MORGUE NAME
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            MORGUE NAME 
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