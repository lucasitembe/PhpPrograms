<?php
include("./includes/header.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }      
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['Section_managementworkspage'])) {
    unset($_SESSION['Section_managementworkspage']);
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Management_Works'] == 'yes') {
        ?>
        <a href='./index.php?DashBoard=DashBoardThisPage' class='art-button-green'>
            BACK
        </a>
    <?php
    }
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br/><br/>
<fieldset>
    <legend align=center><b>MANAGEMENT WORKS</b></legend>
    <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                        <a href='audit_trail_patient_edit.php?Section=Management&ReceptionReport=ReceptionReportThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Audit Trail - Patient Edit Report
                            </button>
                        </a>
                        </button>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='doctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage&this_page_from=all_doctors_performance&from_doctors_page=no'>
                            <button style='width: 100%; height: 100%'>
                                Doctor's Performance Report
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Performance Report
                        </button>

<?php } ?>
                </td>
            </tr>
<!--	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                                <a href='employeeworkperformancesummary.php?EmployeeWorkPerformanceSummary=EmployeeWorkPerformanceThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Employee Work Performance Report
                                    </button>
                                </a>
<?php } else { ?>
                                 
                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                        Employee Work Performance Report
                                    </button>
                              
<?php } ?>
                </td>
            </tr>-->
<!--	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                                <a href='doctorsperformancepatientsummary.php?DoctorsPerformancePateintSummary=DoctorsPerformanceThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Doctor's Performance Patient Report
                                    </button>
                                </a>
<?php } else { ?>
                                 
                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                        Doctor's Performance Patient Report
                                    </button>
                              
<?php } ?>
                </td>
            </tr>-->

            <tr>
                <td>
                    <a href='audit_editing_items.php'>
                        <button style='width: 100%; height: 100%;'>
                           <span>Editing Item Audit Trail</span> 
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='doctorsroundperfomancereport.php'>
                            <button style='width: 100%; height: 100%'>
                                Doctor's Round Performance Report
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Round Performance Report
                        </button>

<?php } ?>
                </td>
            </tr>

            <tr>
                <td>
                    <a href='new_audit_trail_report.php'>
                        <button style='width: 100%; height: 100%;'>
                           <span>New Audit Trial</span> 
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='consult_patient_audit.php?SystemAuditReport=SystemAuditReportThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Doctor's Consultation Audit</b>
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                             Doctor's Consultation Audit 
                        </button>

<?php } ?>
                </td>
            </tr>
             <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='systemaudit.php?SystemAuditReport=SystemAuditReportThisPage'>
                            <button style='width: 100%; height: 100%'>
                                System Audit Trail Reports
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            System Audit Trail Reports 
                        </button>

<?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='doctorsclinicperformancesummary.php'>
                            <button style='width: 100%; height: 100%'>
                                Doctor's Clinic Performance Report
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Doctor's Clinic Performance Report
                        </button>

<?php } ?>
                </td>

            </tr>
             <tr>
                <td style='text-align: center; height: 40px; width: 33%;' >
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                    <a href='employeeattendance.php'>
                            <button style='width: 100%; height: 100%'>
                             Employee Attendance</b>
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                             Employee Attendance 
                        </button>

<?php } ?>
                </td>
                <td colspan="">
                    <a href='patient_oncall_report.php'>
                        <button style='width: 100%; height: 100%;' class="btn btn-danger btn-sm">
                           <span  style="color:#fff; font-size:20px;"> Patient Oncall Report </span> 
                        </button>
                    </a>
                </td>
            </tr>
                <!-- start on call -->
            <tr>
                <td>
                    <a href='employee_password_change_log_report.php'>
                        <button style='width: 100%; height: 100%'>
                            Employee Password Change Log Report
                        </button>
                    </a> 
                </td>
                <td>
                    <a href='patient_waiting_time_limit.php'>
                        <button style='width: 100%; height: 100%;'>
                           <span>Patient Waiting Time Limit</span> 
                        </button>
                    </a>
                </td>
            </tr>
            <!-- end on call -->

            <tr>
                <td>
                    <a href='engineering_reports.php'>
                        <button style='width: 100%; height: 100%;'>
                           <span>Engineering Reports</span> 
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px; display: none;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                    <a href='patient_medication_list.php'>
                            <button disabled style='width: 100%; height: 100%'>
                               Patient Medication Report</b>
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Medication Report 
                        </button>

<?php } ?>
                </td>
            <!-- </tr>
            <tr> -->
                <td class='hide'>
                    <a href='lab_machine_integration_report.php'>
                        <button disabled style='width: 100%; height: 100%;'>
                            Lab Machines Integration Report
                        </button>
                    </a>
                </td>

                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                    <a href='surgery_performance_report.php?loc=mangnt'>
                            <button style='width: 100%; height: 100%'>
                                Surgery Performance Reports
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Surgery Performance Reports
                        </button>

<?php } ?>
                </td>
            </tr>
            <tr>
                <td class='hide'>
                    <a href='radiology_work_list.php'>
                        <button disabled style='width: 100%; height: 100%'>
                            Radiology Machines Integration Report
                        </button>
                    </a>
                </td>
                <?php 
                    $numberofexemption= 0;
                    $select_exemption_ID = mysqli_query($conn, "SELECT Patient_Name, COUNT(Exemption_ID) as exemptionnumber from tbl_temporary_exemption_form ef, tbl_patient_registration pr WHERE pr.Registration_ID=ef.Registration_ID AND Patient_Name NOT LIKE '%OUTPATIENT TEST%'  AND Exemption_ID NOT IN (SELECT Exemption_ID FROM tbl_exemption_maoni_dhs )  ") or die(mysqli_error($conn));
                    while($countID = mysqli_fetch_assoc($select_exemption_ID)){
                        $numberofexemption = $countID['exemptionnumber'];
                        //echo $numberofexemption;
                    }
                ?>
                <td>
                    <a href='ExemptionListtoDHS.php'>
                        <button style='width: 100%; height: 100%'>
                            Exemption Patient List <span class="badge " style="background-color: red;"><?php echo $numberofexemption; ?></span>
                        </button>
                    </a> 
                </td>

                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='generalledgercenter.php?Section=managementworkspage'>
                            <button style='width: 100%; height: 100%'>
                                General Ledger
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            General Ledger
                        </button>

<?php } ?>
                </td>
            </tr>
            <tr class='hide'>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                    <a target="_blank" href='http://127.0.0.1/Final_One/gaccounting/'>
                            <button disabled style='width: 100%; height: 100%'>
                               Accounting and Finance</b>
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Accounting and Finance 
                        </button>

<?php } ?>
                </td>
            </tr>
            <tr class='hide'>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                    <a href='shownosignlist.php?loc=mangnt'>
                            <button disabled style='width: 100%; height: 100%'>
                                 No show & Sign-off list
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                             No show & Sign-off list
                        </button>

<?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
<?php if ($_SESSION['userinfo']['Management_Works'] == 'yes') { ?>
                        <a href='Daily_Summary_Reports.php?SystemAuditReport=SystemAuditReportThisPage'>
                            <button disabled style='width: 100%; height: 100%'>
                                Daily Hospital Summary Reports <b>(For Emails)</b>
                            </button>
                        </a>
<?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Daily Hospital Reports 
                        </button>

<?php } ?>
                </td>
            </tr>
            <tr>
                <td>
                         <a href='Patient_ordering_control.php?SystemAuditReport=SystemAuditReportThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Patient Orders Control
                            </button>
                        </a>
                </td>
		                <td><a href='document_approval.php'><button style='width: 100%; height: 100%'>Document Approval</button></a></td>

            </tr>
        </table>
    </center>
</fieldset><br/>
<?php
include("./includes/footer.php");
?>
