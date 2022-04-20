<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {

            if (isset($_SESSION['userinfo']['Management_Works']) && $_SESSION['userinfo']['Management_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }
    } elseif (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {

            if (isset($_SESSION['userinfo']['Reception_Works']) && $_SESSION['userinfo']['Reception_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}

?>


<?php if ($Section == 'Reception') { ?>
    <a href='receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php } else if (strtolower($Section) == 'management') { ?>
    <a href='./managementworkspage.php?ManagementWorksPage=ManagementWorksPageThisPage' class='art-button-green'>
        BACK
    </a>
<?php } ?>

<!--<a href='./managementworkspage.php?ManagementWorksPage=ManagementWorksPageThisPage' class='art-button-green'>
        BACK
    </a> -->
</script>
<br />
<fieldset>
    <legend align=center><b>RECEPTION REPORTS</b></legend>
    <center>
        <table width=60% style="border: 0">
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%' colspan="2">
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='nhifauthorizationreport.php?Section=<?php echo $Section; ?>&VisitedPatient=VisitedPatientThisPage'>
                            <button style='width: 100%; height: 100%'>
                                NHIF AUTHORIZATION REPORT
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            NHIF AUTHORIZATION REPORT
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='patient_progress_report.php?Section=<?php echo $Section; ?>&VisitedPatient=VisitedPatientThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Patient Progres Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Progres Report
                        </button>

                    <?php } ?>
                </td>
          <td>
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='patient_spounsor_edit.php'>
                            <button style='width: 100%; height: 100%'>
                                Patient Edit Sponsor Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Edit Sponsor Report
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
            <td>
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='visitedPatients.php?Section=<?php echo $Section; ?>&VisitedPatient=VisitedPatientThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Demographic Report <b>(All)</b>
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Demographic Report <b>(All)</b>
                        </button>

                    <?php } ?>
                </td>

                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='pf3report.php?Section=<?php echo $Section; ?>&Pf3Report=Pf3ReportThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Pf3 Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Pf3 Report
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td class='hide' style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='patientregistrationperformance.php?Section=<?php echo $Section; ?>&PatientsRegistrationPerformance=PatientsRegistrationPerformanceThisForm'>
                            <button style='width: 100%; height: 100%'>
                                Patients Registration Performance
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patients Registration Performance
                        </button>

                    <?php } ?>
                </td>

                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='patientconsultation.php?Section=<?php echo $Section; ?>&PatientConsultationReport=PatientConsultationReportThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Patient Consultation Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Consultation Report
                        </button>

                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='receptioncheckedinpatientslist.php?Section=Reception&CheckInPatient=CheckInPatientThisPage'>
                        <button style='width: 100%; height: 100%'>
                            List Of Checked In Patients
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='currentinpatientreport.php?section=reception&InpatientReport=InpatientReportThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Current Inpatients List
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Current Inpatients List
                        </button>

                    <?php } ?>
                </td>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='patient_registration_list.php?Section=<?php echo $Section; ?>'>
                            <button style='width: 100%; height: 100%'>
                                Patient Registration Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Registration Report
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='checkedinpatientslist.php?Section=<?php echo $Section; ?>&CheckInPatient=CheckInPatientThisPage' style="width:150px;">
                        <button style='width: 100%; height: 100%'>
                            Master Sheet
                        </button>
                    </a>
                </td>
                <td style='text-align: center;color:  white;border: 1px solid' height='40px' width='33%'>
                        <a href='viewappointmentPage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage&frompage=reception&this_page_from=reception_report'>
                            <button style='width: 100%; height: 100%;color: white;background-color: #d40b72;'>
                                <span style='color: white;'>Appointments Report</span> 
                            </button>
                        </a>
                </td>
                <td class='hide' style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'>
                    <?php if ($_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='performancereports.php?Section=<?php echo $Section; ?>&PerformaceReport=PerformaceReportThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Performance Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Performance Report
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
            </tr>
        </table>
    </center>
</fieldset>
<?php
include("./includes/footer.php");
?>