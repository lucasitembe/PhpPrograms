<?php
include("./includes/functions.php");
include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
} 

$DateGiven = date('Y-m-d');
?>
<?php
//get sub department id

 
$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}


if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Mtuha_Reports'] == 'yes') {
        ?>
        <a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>
            DISEASE CONFIGURATION
        </a>
        <a href='diagnoseddiseases.php?DiagnosedDiseases=DiagnosedDiseasesThisPage'  class='art-button-green'>
           <!--<button style='width: 100%; height: 100%'>-->
                DIAGNOSED DISEASES
            <!--</button>-->
        </a>
   <a href='doctorsperformancesummarydhis.php?DoctorsPerformanceSummary=DoctorsPerformanceThisPage' class='art-button-green'>
           <!--<button style='width: 100%; height: 100%'>-->
               DOCTOR'S PERFORMANCE REPORT
            <!--</button>-->
        </a>
 <a href='doctorsDiagnosisStatus.php?Doctorsfinaldiagnosis=DoctorsfinaldiagnosisThisPage' class='art-button-green'>
           <!--<button style='width: 100%; height: 100%'>-->
              DOCTORS FINAL DIAGNOSIS REPORT
            <!--</button>-->
        </a>
    <?php }
}
?>
<a href="define_hospital_catchment_area.php" class='art-button-green'>DEFINE HOSPITAL CATCHMENT AREA</a>
<a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>
        BACK
    </a>
<br/>
<br/>
<br/>
<br/>
<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DHIS2 REPORTS</b></legend>
    <center>
        <table  style="width:80%">
            <thead style="background-color:rgb(192,192,192)"><tr><th>OTHER Reports</th><th>OPD Reports</th><th>IPD Reports</th></tr></thead>
            <tr>

                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="diagnosis_report_home.php"><button style='width: 100%; height: 100%'>Diagnosis Reports</button></a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="opd_reports_home.php"><button style='width: 100%; height: 100%'>OPD Reports</button></a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="ipd_reports_home.php"><button style='width: 100%; height: 100%'>IPD Reports</button></a>
                </td>

            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="clinicattendance.php"><button style='width: 100%; height: 100%'>Clinic Attendance</button></a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="dailyPatientAttendance.php"><button style='width: 100%; height: 100%'>Daily attendance</button></a>
                </td>
                <td><a href="ipd_report_new.php"><button style='width: 100%; height: 100%'>Bed State</button></a></td>

            </tr>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <!-- <a href="radiology_reports_home.php"><button style='width: 100%; height: 100%'>Radiology Reports</button></a> -->
                    <a href="radiologyreports.php?frompage=DIHSREPORT"><button style='width: 100%; height: 100%'>Radiology Reports</button></a>
                </td>
                <td><a href="referral_letters.php"><button style='width: 100%; height: 100%'>Referral Letters</button></a></td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="death_reports_home.php"><button style='width: 100%; height: 100%'>Death Reports</button></a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <!-- <a href="procedure_reports_home.php"><button style='width: 100%; height: 100%'>Procedure Reports</button></a> -->
                    <a href='procedurelistreport.php?frompage=DIHSREPORT'><button style='width: 100%; height:40px'>Procedure Reports</button></a>

                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="general_opd_report.php"><button style='width: 100%; height: 100%'>General OPD</button></a>
                </td>
                 <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="daily_census_summary.php"><button style='width: 100%; height: 100%'>Daily Census Summary</button></a>
               </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="Laboratory_Reports.php?frompage=DIHSREPORT"><button style='width: 100%; height: 100%'>Laboratory Reports</button></a>
                    <!-- <a href="laboratory_reports_home.php"><button style='width: 100%; height: 100%'>Laboratory Reports</button></a> -->
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="self_and_refferal.php"><button style='width: 100%; height: 100%'>Self and referral</button></a>
                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="hospital_statistics_report.php"><button style='width: 100%; height: 100%'>Hospital Statistics</button></a>
                </td> 
               
            </tr> 
            <tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="pharmacy_reports_home.php"><button style='width: 100%; height: 100%'>Pharmacy Reports</button></a>
                </td>
                <td><a href="mtuha_book_11.php"><button style='width: 100%; height: 100%'>Mtuha Book 11 Report</button></a></td>
                
                <td style='text-align: center; height: 40px; width: 25%;'>
                <a href='medicaldashboard.php?medicaldashaboard=EmployeeWorkPerformanceThisPage'>
                    <button style='width: 100%; height: 100%'>
                        Medical Darshbord
                    </button>
                </a>
                </td>
            </tr>
			<tr>
                <td style='text-align: center; height: 40px; width: 25%;'>
                    <a href="dhis2_api.php"><button style='width: 100%; height: 100%'><b>DHIS2 API<b></button></a>
                </td>
                <td>
                    <a href="hospital_medication_report.php"><button style='width: 100%; height: 100%'>Medication Report</button></a>

                </td>
                <td style='text-align: center; height: 40px; width: 25%;'>
           <a href='rawdata.php?medication=EmployeeWorkPerformanceThisPage'>
     <button style='width: 100%; height: 100%'>
        RAW DATA
    </button>  
    <tr>    
    <td style='text-align: center; height: 40px; width: 25%;'>
           <a href='mtuha_book_report.php?medication=EmployeeWorkPerformanceThisPage'>
     <button style='width: 100%; height: 100%'>
        Mtuha Summary
 </button>
</a>
</td>  
</tr>            
            </tr> 
        </table>
    </center>
  
</fieldset>

<br/>
<br/>
<br/>
<?php
include("./includes/footer.php");
?>
