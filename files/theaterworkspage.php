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
<a href='deptsupervisorauthentication.php?SessionCategory=Surgery&InvalidSupervisorAuthentication=yes' class='art-button-green'>CHANGE DEPARTMENT</a>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./theaterworkspage.php?section=Theater&TheaterWorks=TheaterWorksThisPage";
    }
</script>
<br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>THEATER WORKS - <?php echo strtoupper($Sub_Department_Name) ?></b></legend>
        <center><table width = 60%>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
            <a href='bloodconcertform.php'>
                                <button style='width: 100%; height: 100%'>
                                BLOOD CONSENT (ONLINE) FORM
                                </button>
                            </a>
            </td>

        <td style='text-align: center; height: 40px; width: 33%;'>
        <a href='theaterconcertform.php'>
                            <button style='width: 100%; height: 100%'>
                            THEATER CONSENT (ONLINE) FORM
                            </button>
                        </a>
        </td>
        </tr>
        <tr>
        <td style='text-align: center; height: 40px; width: 33%;'>
        <a href='patient_with_concert_form.php'>
                            <button style='width: 100%; height: 100%'>
                            PATIENTS WITH ONLINE CONSENT FORM
                            </button>
                        </a>
        </td>

        <td style='text-align: center; height: 40px; width: 33%;'>
        <a href='patient_with_concert_form_attached.php'>
                            <button style='width: 100%; height: 100%'>
                            PATIENTS WITH ATTACHED CONSENT FORM
                            </button>
                        </a>
        </td>
        </tr>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    <a href='Prepare_Surgery_Appointments.php?ItemsConfiguration=ItemConfigurationThisPage&theater=yes'>
                        <button style='width: 100%; height: 100%'>
                            Prepare Surgery List
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Prepare Surgery List
                        </button>
                  
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['can_have_access_to_Authorize_Surgery_List'] == 'yes'){ ?>
                    <a href='Anaesthesia_Surgery_list.php?ItemsConfiguration=ItemConfigurationThisPage&theater=yes'>
                        <button style='width: 100%; height: 100%'>
                            Anaesthesia Approval For Surgery List
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Anaesthesia Approval For Surgery List
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['can_have_access_to_Approvery_Surgery_List'] == 'yes'){ ?>
                    <a href='Approval_Surgery_Appointments.php?ItemsConfiguration=ItemConfigurationThisPage&theater=yes'>
                        <button style='width: 100%; height: 100%'>
                            Final Approval For Surgery List
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Final Approval For Surgery List
                        </button>
                  
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    <a href='Surgery_Appointments.php?ItemsConfiguration=ItemConfigurationThisPage&theater=yes'>
                        <button style='width: 100%; height: 100%'>
                            <b>Perform Surgery</b>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           <b>Perform Surgery</b>
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    <a href='theater_room_arrangement.php?Location=Surgery'>
                        <button style='width: 100%; height: 100%'>
                           Theater Room Assignment
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Theater Room Assignment
                        </button>
                  
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='Surgery_Progress_Monitoring.php?ItemsConfiguration=ItemConfigurationThisPage&theater=yes'>
                        <button style='width: 100%; height: 100%;'>
                            <b>Surgery Progress Monitoring</b>
                        </button>
                    </a>
                    
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    <a href='anaesthesia_patient_report.php?Location=Surgery'>
                        <button style='width: 100%; height: 100%'>
                           Anaesthesia Report
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Anaesthesia Report
                        </button>
                  
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='preoperativelist_theater_list.php?ItemsConfiguration=ItemConfigurationThisPage&theater=yes'>
                        <button style='width: 100%; height: 100%;'>
                            Pre Operative Checklist
                        </button>
                    </a>
                    
                </td>
            </tr>
            <tr>
                <td>
                    <a href='Surgery_performance_progress.php?Location=Surgery'>
                        <button style='width: 100%; height: 100%'>
                           Surgery Progress Status
                        </button>
                    </a>
                    </td>
        
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    
                    <a href='surgery_performance_report.php?ItemsConfiguration=ItemConfigurationThisPage&theater=yes'>
                        <button style='width: 100%; height: 100%'>
                             Surgery Performance Report
                        </button>
                    </a>

                    <?php }else{ ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Surgery Performance Repor
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    <a href='Surgery_appointment_list_reports.php?TheaterSetup=Setup&theater=yes'>
                        <button style='width: 100%; height: 100%'>
                           Surgery Appointment List Reports
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Surgery Appointment List Reports
                        </button>
                  
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    <a href='Theater_setup_Menu.php?TheaterSetup=Setup&theater=yes'>
                        <button style='width: 100%; height: 100%'>
                           Theater Setup
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Theater Setup
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    <a href='Theater_consumable_contorl.php?TheaterSetup=Setup&theater=yes'>
                        <button style='width: 100%; height: 100%'>
                           Theater Medicine & Consumable Control
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Theater Medicine & Consumable Control
                        </button>
                  
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Theater_Works'] == 'yes'){ ?>
                    <a href='Theater_consumable_control_report.php?TheaterSetup=Setup&theater=yes'>
                        <button style='width: 100%; height: 100%'>
                           Theater Medicine & Consumable Consumption Report
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                           Theater Medicine & Consumable Consumption Report
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
