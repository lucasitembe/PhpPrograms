<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
	if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
	if(isset($_SESSION['userinfo'])){
	    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
		    if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
			    header("Location: ./index.php?InvalidPrivilege=yes");
		    }else{
		    @session_start();
		    if(!isset($_SESSION['Storage_Supervisor'])){ 
			header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		    }
		} 
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
	}else{
	    @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
	}

    if (isset($_SESSION['userinfo'])) {
        echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
    }
?>
<br/><br/>
<fieldset>  
    <legend align=center><b>INWARDS & OUTWARDS</b></legend>
        <center>
        <table width = 60%>
	       <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                    <a href='returninward.php?ReturnInward=ReturnInwardThisPage'>
                        <button style='width: 100%; height: 100%'>
                            RETURN INWARDS
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            RETURN INWARDS 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
                    <a href='returnoutward.php?ReturnOutward=ReturnOutwardThisPage'>
                        <button style='width: 100%; height: 100%'>
                            RETURN OUTWARDS
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            RETURN OUTWARDS
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