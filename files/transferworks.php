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

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<br/><br/>
<fieldset>
            <legend align=center><b>TRANSFER WORKS</b></legend>
        <center><table width = 60%>
           
			<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                
                    <a href='doctorspagetransfer.php'>
                        <button style='width: 100%; height: 100%'>
                            Patient Transfer From List
                        </button>
                    </a>
                   
                </td>
            </tr>
			<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                
                    <a href='transferconsulted.php'>
                        <button style='width: 100%; height: 100%'>
                            Patient Transfer Consulted
                        </button>
                    </a>
                   
                </td>
            </tr>
			<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Clinic Transfer
                        </button>
                    </a>
                   
                </td>
            </tr>
			<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Patient Referal
                        </button>
                    </a>
                   
                </td>
            </tr>
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>