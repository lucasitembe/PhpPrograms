<?php
//include("./includes/header.php");
//include("./includes/connection.php");
//if (!isset($_SESSION['userinfo'])) {
//    @session_destroy();
//    header("Location: ../index.php?InvalidPrivilege=yes");
//}
//if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Mtuha_Reports'])) {
//        if ($_SESSION['userinfo']['Mtuha_Reports'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
//} else {
//    @session_destroy();
//    header("Location: ../index.php?InvalidPrivilege=yes");
//}
//
//
////get current year
//$Today_Date = mysqli_query($conn,"select now() as today");
//while ($row = mysqli_fetch_array($Today_Date)) {
//    $original_Date = $row['today'];
//    $Current_Year = date("Y", strtotime($original_Date));
//    $myDate = $Current_Year . '-02-01';
//}

//$d = new DateTime($myDate); echo $d->format( 'Y-m-t' );
header("Location:governmentReports.php");

?>


<?php /* if(isset($_SESSION['userinfo'])){
  if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){
  ?>
  <a href='mapdiseasegroup.php?section=DHIS&MapDiseaseGroup=MapDiseaseGroupThisPage' class='art-button-green'>
  MAP DISEASE TO GROUP
  </a>
  <?php  } } */ ?>

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
<a href='governmentReports.php?GovernmentReports=GovernmentReportsThisPage' class='art-button-green'>
           <!--<button style='width: 100%; height: 100%'>-->
              DHIS2 REPORTS
            <!--</button>-->
        </a>
<br/>
<br/>
 <a href='doctorsDiagnosisStatus.php?Doctorsfinaldiagnosis=DoctorsfinaldiagnosisThisPage' class='art-button-green'>
           <!--<button style='width: 100%; height: 100%'>-->
              DOCTORS FINAL DIAGNOSIS REPORT
            <!--</button>-->
        </a>

    <?php }
}
?>

<a href="dailyPatientAttendance.php" class='art-button-green'>DAILY PATIENT ATTENDANCE</a>
<!--<a href='./dhisworkpage.php?DhisWork=DhisWorkThisPage' class='art-button-green'>
        BACK
    </a>-->
<br/>
<br/>
<br/>
<br/>
<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DHIS2 REPORTS</b></legend>
    <center><table width = 60%>
        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <!-- <a href="#" onclick="alert('* This button * is under repair. Sorry for inconvenience!')"><button style='width: 100%; height: 100%'>OPD Reports</button></a> -->
                    <a href="opd_reports_home.php"><button style='width: 100%; height: 100%'>OPD Reports</button></a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <!-- <a href="#" onclick="alert('* This button * is under repair. Sorry for inconvenience!')"><button style='width: 100%; height: 100%'>IPD Reports</button></a> -->
                    <a href="ipd_reports_home.php"><button style='width: 100%; height: 100%'>IPD Reports</button></a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <!-- <a href="#" onclick="alert('* This button * is under repair. Sorry for inconvenience!')"><button style='width: 100%; height: 100%'>Radiology Reports</button></a> -->
                    <a href="radiology_reports_home.php"><button style='width: 100%; height: 100%'>Radiology Reports</button></a>
                </td>
        </tr>
        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <!-- <a href="#" onclick="alert('* This button * is under repair. Sorry for inconvenience!')"><button style='width: 100%; height: 100%'>Procedure Reports</button></a> -->
                    <a href="procedure_reports_home.php"><button style='width: 100%; height: 100%'>Procedure Reports</button></a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <!-- <a href="#" onclick="alert('* This button * is under repair. Sorry for inconvenience!')"><button style='width: 100%; height: 100%'>Laboratory Reports</button></a> -->
                    <a href="laboratory_reports_home.php"><button style='width: 100%; height: 100%'>Laboratory Reports</button></a>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <!-- <a href="#" onclick="alert('* This button * is under repair. Sorry for inconvenience!')"><button style='width: 100%; height: 100%'>Pharmacy Reports</button></a> -->
                    <a href="pharmacy_reports_home.php"><button style='width: 100%; height: 100%'>Pharmacy Reports</button></a>
                </td>
        </tr>
        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href="#" onclick="alert('* This button * is under repair. Sorry for inconvenience!')"><button style='width: 100%; height: 100%'>Death Reports</button></a>
                    <!--<a href="death_reports_home.php"><button style='width: 100%; height: 100%'>Death Reports</button></a>-->
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href="#" onclick="alert('* This button * is under repair. Sorry for inconvenience!')"><button style='width: 100%; height: 100%'><b>DHIS2 API<b></button></a>
                    <!--<a href="dhis2_api.php"><button style='width: 100%; height: 100%'><b>DHIS2 API<b></button></a>-->
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href="#" onclick="alert('* This button * is under repair. Sorry for inconvenience!')"><button style='width: 100%; height: 100%'>Diagnosis Reports</button></a>
                    <!--<a href="diagnosis_report_home.php"><button style='width: 100%; height: 100%'>Diagnosis Reports</button></a>-->
                </td>
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






<!--<br/>
<fieldset>
    <legend align=center>DHIS2 WORKS</legend>
    <center>
        <table width = 90%>
            <tr>
                <td style='text-align: right;'>
                    Branch
                </td>
                <td>
                    <select id='Branch_ID' name='Branch_ID' onchange="gotolink()">
                        <?php
                        $branch_qr = "SELECT * FROM tbl_branches ";
                        $branch_result = mysqli_query($conn,$branch_qr);
                        while ($branch_row = mysqli_fetch_assoc($branch_result)) {
                            ?><option value='<?php echo $branch_row['Branch_ID']; ?>'><?php echo $branch_row['Branch_Name']; ?></option><?php
                        }
                        ?>
                    </select>
                </td>
                <td width = 10% style='text-align: right'>
                    Month
                </td>
                <td width = 10%>
                    <select name="Month" id="Month" onchange="Clear_Fieldset()">
                        <option selected="selected"></option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </td>
                <td style="text-align: right;">Year</td>
                <td>
                    <select name="Year" id="Year" onchange="Clear_Fieldset()">
                        <option></option>
                        <?php
                        for ($i = 2010; $i <= $Current_Year; $i++) {
                            echo "<option value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: right'>
                    Report
                </td>
                <td>
                    <select id='report_type' name="report_type" onchange="Clear_Fieldset()">
                        <option></option>
                        <option>OPD Report</option>
                        <option>IPD Report</option>
                        <option>DENTAL Report</option>
                    </select>
                </td>
                <td width="10%">
                    <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Filter_Dhis_Report();">
                </td>
                <td width="10%">
                    <input type="button" name="Filter" id="Filter" value="SEND TO CLOUD" class="art-button-green" onclick="Send_Cloud();">
                </td>
                <td width="10%">
                    <input type="button" name="Filter" id="Filter" value="PREVIEW REPORT" class="art-button-green" onclick="Preview_Report();">
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 400px; background-color:white;' id='Details_Area'>

</fieldset>
<br>
<input type="submit" name="print_mtuha_excel_report" style="float:right;" class="art-button-green" value="EXPORT TO EXCEL" onclick="Excel_Report();">

<script type="text/javascript">
    function Preview_Report() {
        var Month = document.getElementById("Month").value;
        var Year = document.getElementById("Year").value;
        var report_type = document.getElementById("report_type").value;

        if (Month == null || Month == '' || Year == null || Year == '' || report_type == null || report_type == '') {
            if (Month == null || Month == '') {
                document.getElementById("Month").focus();
                document.getElementById("Month").style = 'border: 3px solid red';
            } else {
                document.getElementById("Month").style = 'border: 3px white';
            }
            if (Year == null || Year == '') {
                document.getElementById("Year").focus();
                document.getElementById("Year").style = 'border: 3px solid red';
            } else {
                document.getElementById("Year").style = 'border: 3px white';
            }
            if (report_type == null || report_type == '') {
                document.getElementById("report_type").focus();
                document.getElementById("report_type").style = 'border: 3px solid red';
            } else {
                document.getElementById("report_type").style = 'border: 3px white';
            }
        } else {
            document.getElementById("report_type").style = 'border: 3px white';
            document.getElementById("Year").style = 'border: 3px white';
            document.getElementById("Month").style = 'border: 3px white';
            if (report_type == 'OPD Report') {
                window.open('./opdreport.php?Month=' + Month + '&Year=' + Year + '&OpdReport=OpdReportThisPage', '_blank');
            }
        }
    }
</script>

<script type="text/javascript">
    function Send_Cloud() {
        var Month = document.getElementById("Month").value;
        var Year = document.getElementById("Year").value;
        var report_type = document.getElementById("report_type").value;

        if (Month == null || Month == '' || Year == null || Year == '' || report_type == null || report_type == '') {
            if (Month == null || Month == '') {
                document.getElementById("Month").focus();
                document.getElementById("Month").style = 'border: 3px solid red';
            } else {
                document.getElementById("Month").style = 'border: 3px white';
            }
            if (Year == null || Year == '') {
                document.getElementById("Year").focus();
                document.getElementById("Year").style = 'border: 3px solid red';
            } else {
                document.getElementById("Year").style = 'border: 3px white';
            }
            if (report_type == null || report_type == '') {
                document.getElementById("report_type").focus();
                document.getElementById("report_type").style = 'border: 3px solid red';
            } else {
                document.getElementById("report_type").style = 'border: 3px white';
            }
        } else {
            document.getElementById("report_type").style = 'border: 3px white';
            document.getElementById("Year").style = 'border: 3px white';
            document.getElementById("Month").style = 'border: 3px white';
            if (report_type == 'OPD Report') {
                //window.open('./Send_To_Cloud.php?Month='+Month+'&Year='+Year+'&OpdReport=OpdReportThisPage','_blank');
                if (window.XMLHttpRequest) {
                    myObjectCloud = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectCloud = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectCloud.overrideMimeType('text/xml');
                }
                myObjectCloud.onreadystatechange = function () {
                    data299 = myObjectCloud.responseText;
                    if (myObjectCloud.readyState == 4) {
                        alert(data299);
                    }
                }; //specify name of function that will handle server response........

                myObjectCloud.open('GET', 'Send_To_Cloud.php?Month=' + Month + '&Year=' + Year, true);
                myObjectCloud.send();
            }
        }
    }
</script>

<script type="text/javascript">
    function Filter_Dhis_Report() {
        var Month = document.getElementById("Month").value;
        var Year = document.getElementById("Year").value;
        var report_type = document.getElementById("report_type").value;

        if (Month == null || Month == '' || Year == null || Year == '' || report_type == null || report_type == '') {
            if (Month == null || Month == '') {
                document.getElementById("Month").focus();
                document.getElementById("Month").style = 'border: 3px solid red';
            } else {
                document.getElementById("Month").style = 'border: 3px white';
            }
            if (Year == null || Year == '') {
                document.getElementById("Year").focus();
                document.getElementById("Year").style = 'border: 3px solid red';
            } else {
                document.getElementById("Year").style = 'border: 3px white';
            }
            if (report_type == null || report_type == '') {
                document.getElementById("report_type").focus();
                document.getElementById("report_type").style = 'border: 3px solid red';
            } else {
                document.getElementById("report_type").style = 'border: 3px white';
            }
        } else {
            document.getElementById("report_type").style = 'border: 3px white';
            document.getElementById("Year").style = 'border: 3px white';
            document.getElementById("Month").style = 'border: 3px white';
            document.getElementById('Details_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObjectFilter = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFilter.overrideMimeType('text/xml');
            }
            myObjectFilter.onreadystatechange = function () {
                data29 = myObjectFilter.responseText;
                if (myObjectFilter.readyState == 4) {
                    document.getElementById('Details_Area').innerHTML = data29;
                }
            }; //specify name of function that will handle server response........

            if (report_type == 'OPD Report') {
                myObjectFilter.open('GET', 'Filter_Dhis_Report.php?Month=' + Month + '&Year=' + Year, true); //OPD REPORT
                myObjectFilter.send();
            } else if (report_type == 'IPD Report') {
                myObjectFilter.open('GET', 'IPD_Filter_Dhis_Report.php?Month=' + Month + '&Year=' + Year, true); //IPD REPORT
                myObjectFilter.send();
            } else if (true) {
                myObjectFilter.open('GET', 'DENTAL_Filter_Dhis_Report.php?Month=' + Month + '&Year=' + Year, true); // DENTAL REPORT
                myObjectFilter.send();
            }
        }
    }
</script>

<script type="text/javascript">
    function Clear_Fieldset() {
        var Month = document.getElementById("Month").value;
        var Year = document.getElementById("Year").value;
        var report_type = document.getElementById("report_type").value;

        if (Month != null && Month != '' && Year != null && Year != '') {
            if (report_type == 'OPD Report' || report_type == 'IPD Report' || report_type == 'DENTAL Report') {
                Filter_Dhis_Report();
            } else {
                document.getElementById('Details_Area').innerHTML = '';
            }
        } else {
            document.getElementById('Details_Area').innerHTML = '';
        }
    }
</script>
<script type="text/javascript">
  function Excel_Report(){
    var Branch_ID = $('#Branch_ID').val();
    var Month = $('#Month').val();
    var Year = $('#Year').val();
    var report_type = $('#report_type').val();
    if(Year != '' && Month != '' && report_type != ''){
      alert('mtuha is loading '+Branch_ID+', '+Month+', '+Year+', '+report_type);
      if(report_type =='OPD Report'){
        window.location.href='print_mtuha_opd_reports.php?Month='+Month+'&Year='+Year;
      }else if(report_type =='IPD Report'){
        window.location.href='print_mtuha_ipd_reports.php?Month='+Month+'&Year='+Year;
      }else if(report_type =='DENTAL Report'){
        window.location.href='print_mtuha_dental_reports.php?Month='+Month+'&Year='+Year;
      }
    }else{
      alert("Select Month, Year and Report");
    }
  }
</script>-->
<?php
//include("./includes/footer.php");
?>
