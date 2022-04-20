<?php
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Dialysis_Works'])) {
        if ($_SESSION['userinfo']['Dialysis_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Dialysis_Supervisor'])) {
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Dialysis&InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
    <legend align=center><b>DIALYSIS WORKS</b></legend>
    <center><table width = 60%>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Dialysis_Works'] == 'yes') { ?>
                        <a href='./dialysiscashpatientlist.php?DialysisClinicalnotes=DialysisClinicalnotesThispage'>
                            <button style='width: 100%; height: 100%'>
                                UNCONSULTED PATIENTS
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            UNCONSULTED PATIENTS
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Dialysis_Works'] == 'yes') { ?>
                        <a href='dialysispatientList.php'>
                            <button style='width: 100%; height: 100%'>
                                CONSULTED PATIENTS
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            CONSULTED PATIENTS
                        </button>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Dialysis_Works'] == 'yes') { ?>
                        <a href='dialysisReport.php?src=dialysis'>
                            <button style='width: 100%; height: 100%'>
                                DIALYSIS REPORT 
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                DIALYSIS REPORT 
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Dialysis_Works'] == 'yes') { ?>
                        <a href='dialysisSessionReport.php?src=dialysis'>
                            <button style='width: 100%; height: 100%'>
                                SESSION REPORT 
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                SESSION REPORT 
                        </button>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Dialysis_Works'] == 'yes') { ?>
                        <a href='dialysis_statistics.php'>
                            <button style='width: 100%; height: 100%'>
                                STATISTICS
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                STATISTICS
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Dialysis_Works'] == 'yes') { ?>
                        <a href='dialysisIncidentsReport.php'>
                            <button style='width: 100%; height: 100%'>
                                INCIDENT/EVENT RECORDS 
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            INCIDENT/EVENT RECORDS 
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Dialysis_Works'] == 'yes') { ?>
                        <a href='monthly_unit_meeting.php'>
                            <button style='width: 100%; height: 100%'>
                                MONTHLY UNITY MEETING
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            MONTHLY UNITY MEETING
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Dialysis_Works'] == 'yes') { ?>
                        <a href='dialysisconsertform.php'>
                            <button style='width: 100%; height: 100%'>
                                DOWNLOAD HEMODIALYSIS CONSENT
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                DOWNLOAD HEMODIALYSIS CONSENT
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='dialysisitypesetup.php'>
                            <button style='width: 100%; height: 100%'>
                               DIALYSIS SETUP
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