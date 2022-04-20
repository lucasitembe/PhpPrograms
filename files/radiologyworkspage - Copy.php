<?php
    include("./includes/header.php");
	
	/* ****************************SESSION CONTROL****************************** */
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Radiology_Works'])){
	    if($_SESSION['userinfo']['Radiology_Works'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
	    } else {
                if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){   
					@session_start();
                    if(!isset($_SESSION['Radiology_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Radiology&InvalidSupervisorAuthentication=yes");
                    }
				}
            }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
	/* **************************** SESSION ********************************** */
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>RADIOLOGY WORKS</b></legend>
        <center><table width = 60%>
			<tr>
				<td style='text-align:center;' >
					<strong>OUTPATIENTS</strong>
				</td>
				
				<td style='text-align:center;'>
					<strong>INPATIENTS</strong>
				</td>
			</tr>
			
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                    <a href='PatientRadiology.php?PatientType=out&RadiologyWorksPage=RadiologyWorksPageThisPage'>
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
				
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                    <a href='PatientRadiology.php?PatientType=in&RadiologyWorksPage=RadiologyWorksPageThisPage'>
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

			<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                    <a href='Radiology_Report.php'>
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
				
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                    <a href='Radiology_Report.php'>
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
			
        <?php
            $Purchase_Session = $_SESSION['userinfo']['Radiology_Works'];
            $Session_Category = 'Radiology';
            //include("./includes/Purchase_Menu.php");
        ?>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                        <?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                            <a href='./Radiology_Management_Report.php'>
                                <button style='width: 100%; height: 100%'>
                                    Management Report
                                </button>
                            </a>
                        <?php }else{ ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Management Report
                            </button>
                        <?php } ?>
                    </td>
                </tr>
				
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                        <?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                            <a href='./radiologycategorylist.php?RadiologyParameterSetupThisPage=RadiologyParameterSetupThispage'>
                                <button style='width: 100%; height: 100%'>
                                    Radiology Setup
                                </button>
                            </a>
                        <?php }else{ ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Radiology Setup
                            </button>
                        <?php } ?>
                    </td>
                </tr>
		<tr>
		    <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
			<?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
			<a href='itemsconfiguration.php?Section=Radiology&ItemsConfiguration=ItemConfigurationThisPage'>
			    <button style='width: 100%; height: 100%'>
				Items Configuration
			    </button>
			</a>
			<?php }else{ ?>
			 
			    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
				Items Configuration 
			    </button>
		      
			<?php } ?>
		    </td>
		</tr>
		<tr>
		    <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
			<?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
			<a href='General_Performance_Filtered_Control_Session.php?Location=Radiology'>
			    <button style='width: 100%; height: 100%'>
				General Performance Report
			    </button>
			</a>
			<?php }else{ ?>
			 
			    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
				General Performance Report
			    </button>
		      
			<?php } ?>
		    </td>
		</tr> 
		<tr>
		    <td style='text-align: center; height: 40px; width: 33%;' colspan='2'>
			    <?php if(isset($_SESSION['userinfo'])){
		    ?>
		    <a href='#'>
			<button style='width: 100%; height: 100%'>
			Radiology Devices Integration / Management
			</button>
		    </a>
		    <?php }else{ ?>
			    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
				Radiology Devices Integration / Management
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