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
    <a href='foodworkpage.php?BloodBank=BloodBankThisPage' class='art-button-green'>
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
            <legend align=center><b>SETUP WORKS</b></legend>
        <center>
		<table width = '60%'>
           <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='foodmenusetup.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Food Menu Setup</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Food Menu Setup</b> 
                        </button>
                  
                    <?php } ?>
                </td>
				</tr>
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='foodrestictionsetup.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>Restriction Setup</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                       <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            <b>Restriction Setup</b> 
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