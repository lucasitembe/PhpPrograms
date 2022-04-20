<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
	if(!isset($_SESSION['userinfo'])){
		@session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
	}
	
	if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Admission_Works'])){
			if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
	}else{
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}


	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
			echo "<a href='admissionworkspage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>BACK</a>";
		}
	}
                ?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>GOOD RECEIVING NOTE</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                    <a href='admissiongrnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Against Issue Note
                        </button>
                    </a>
                    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Against Issue Note 
                        </button>
                    <?php } ?>
                </td>
                </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ ?>
                    <a href='#grnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage'>
                        <button style='width: 100%; height: 100%'>
                            As Open Balance / Stock Taking
                        </button>
                    </a>
                    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            As Open Balance / Stock Taking
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