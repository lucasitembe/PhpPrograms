<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Theater_Works'])){
	    if($_SESSION['userinfo']['Theater_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
            @session_start();
            if(!isset($_SESSION['Theater_Supervisor'])){ 
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Surgery&InvalidSupervisorAuthentication=yes");
            }
        }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Sub_Department_Name = $_SESSION['Theater_Department_Name'];

?>
<a href="theaterworkspage.php?section=Theater&TheaterWorks=TheaterWorksThisPage" class="art-button-green">BACK</a>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./theaterworkspage.php?section=Theater&TheaterWorks=TheaterWorksThisPage";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
    <legend align=center><b>THEATER WORK SETUP - <?php echo $Sub_Department_Name ?></b></legend>
        <center>
            <table width = 60%>
                    <tr>
                            <td style='text-align: center; height: 40px; width: 33%;'>
                                <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                                <a href='Theater_setup.php?TheaterSetup=Setup&theater=yes'>
                                    <button style='width: 100%; height: 100%'>
                                    Theater Free Procedure Setup
                                    </button>
                                </a>
                                <?php }else{ ?>
                                
                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Theater Free Procedure Setup<?php echo strtoupper($Sub_Department_Name) ?>
                                    </button>
                            
                                <?php } ?>
                            </td>
                    </tr>
                    <tr>
                            <td style='text-align: center; height: 40px; width: 33%;'>
                                <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                                <a href='Theater_items_attachment_setup.php?TheaterSetup=Setup&theater=yes'>
                                    <button style='width: 100%; height: 100%'>
                                    Theater Attached Items 
                                    </button>
                                </a>
                                <?php }else{ ?>
                                
                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Theater Attached Items
                                    </button>
                            
                                <?php } ?>
                            </td>
                    </tr>
                    <tr>
                            <td style='text-align: center; height: 40px; width: 33%;'>
                                <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                                <a href='Theater_Merge_department_setup.php?TheaterSetup=Setup&theater=yes'>
                                    <button style='width: 100%; height: 100%'>
                                    Theater Attached Store Department 
                                    </button>
                                </a>
                                <?php }else{ ?>
                                
                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Theater Attached Store Department
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