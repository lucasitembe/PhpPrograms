<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if (isset($_GET['Section'])) {
        if (strtolower($_GET['Section']) == 'inpatient') {
            echo "<a href='doctorsinpatientworkspage.php?DoctorsInpatient=DoctorsInpatientThisPage' class='art-button-green'>BACK</a>";
        } else {
            echo "<a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>BACK</a>";
        }
    } else {
        echo "<a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>BACK</a>";
    }
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }

    .daterange{
        background-color: rgb(3, 125, 176);
        color: white;
        display: block;
        width: 99.2%;
        padding: 4px;
        font-family: times;
        font-size: large;
        font-weight: bold;
    }
</style> 
<br/><br/>
<?php
if (isset($_POST[''])) {
    $Date_From = mysqli_real_escape_string($conn,$_POST['Date_From']);
    $Date_To = mysqli_real_escape_string($conn,$_POST['Date_To']);
} else {
    $Date_From = '';
    $Date_To = '';
}

$employee_ID = $_SESSION['userinfo']['Employee_ID'];
?>

<br/>
<fieldset style='overflow-y:scroll; height:440px' >
    <center>

        <legend  align="right" style="background-color:#006400;color:white;padding:5px;"><form action='individualdoctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>	 

        <!--<form action='doctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">-->
        <br/>
        <table width='69%'>
            <tr>
                <td style="text-align: center"><b>From</b></td>
                <td style="text-align: center">
                    <input type='text' name='Date_From' id='date_From' required='required'>    
                </td>
                <td style="text-align: center">To</td>
                <td style="text-align: center"><input type='text' name='Date_To' id='date_To'        required='required'></td>    
                <td style="text-align: center">
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:100%;display:inline'>
                        <option value="All">All Sponsors</option>
                        <?php
                        $qr = "SELECT * FROM tbl_sponsor";
                        $sponsor_results = mysqli_query($conn,$qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: center;'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
                </td>
            </tr>	
        </table>
        </form> 
    </center>
    <!--End datetimepicker-->
    <?php
    // date_default_timezone_set('Africa/Dar_es_Salaam');
    $Date_From = ''; //@$_POST['Date_From'];
    $Date_To = ''; //@$_POST['Date_To'];

    $sqltodaydate = mysqli_query($conn,'SELECT NOW() AS todayDate')or die(mysqli_error($conn));
    $today = mysqli_fetch_assoc($sqltodaydate)['todayDate'];

    //die($today);
    if (!isset($_GET['Date_From'])) {
        $Date_From = $today;
    } else {
        $Date_From = $_GET['Date_From'];
    }
    if (!isset($_GET['Date_To'])) {
        $Date_To = $today;  //date('Y-m-d H:m');;
    } else {
        $Date_To = $_GET['Date_To'];
    }if (!isset($_GET['Sponsor_ID'])) {
        $Sponsor = "All";
    } else {
        $Sponsor = $_GET['Sponsor_ID'];
    }
    ?>
    <br>
    <legend align='center' style="background-color:#037DB0;color: white;padding: 5px;text-align: center">
        <b>DOCTOR'S PERFORMANCE REPORT SUMMARY</b><br/>
        <b>FROM <span class='dates'><?php echo date('j M, Y H:i:s', strtotime($Date_From)) ?></span> TO <span class='dates'><?php echo date('j M, Y H:i:s', strtotime($Date_To)) ?></span></b>
    </legend>   
    <center>
<?php
echo '<center><table width =100% border="1" id="doctorperformancereportsummarised" class="display">';
echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
			    <th style='text-align: left;' width=12%>PATIENTS</th>
		     </tr></thead>";
//run the query to select all data from the database according to the branch id
//		    
//			$select_patient_item_list=mysqli_query($conn,"SELECT COUNT(Registration_ID) AS numberOfPatients,e.Employee_Name FROM tbl_consultation co,tbl_patient_payment_item_list ppl
//			  WHERE co.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID AND
//			  co.employee_ID=ppl.Consultant_ID AND
//			  ppl.Consultant_ID='$employeeID' AND ppl.Process_Status='signedoff'
//			  AND ppl.Signedoff_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
//			  ");

$result = mysqli_query($conn,"SELECT COUNT(c.Registration_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employee_ID' AND Saved='yes'") or die(mysqli_error($conn));
$select_doctor_row = mysqli_fetch_array($result);
//$employeeID=$select_doctor_row['employee_ID'];
$employeeName = $select_doctor_row['Employee_Name'];
$numberOFPatients = $select_doctor_row['numberOfPatients'];
echo "<tr><td>1</td>";
echo "<td style='text-align:left'><a href='individualdoctorsperformancepatientsummary.php?Employee_ID=$employee_ID&Date_From=$Date_From&Date_To=$Date_To&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage'>" . $employeeName . "</a></td>";
echo "<td style='text-align:center'>" . number_format($numberOFPatients) . "</td></tr>";
?>

        </table>
    </center>
</center>
</fieldset>
<table>
    <tr>
        <td style='text-align: center;'>
            <a href="previewFilterDoctorPerformance.php?Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>&PreviewFilterPerformanceReportThisPage=ThisPage" target="_blank">

                <input type='submit' name='previewFilterDoctorPerformance' id='previewFilterDoctorPerformance' target='_blank' class='art-button-green' value='PREVIEW ALL'>

            </a>
        </td>

    </tr>
</table>
<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
                        $('#date_From').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            startDate: 'now'
                        });
                        $('#date_From').datetimepicker({value: '', step: 1});
                        $('#date_To').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            startDate: 'now'
                        });
                        $('#date_To').datetimepicker({value: '', step: 1});
</script>
<script>
    $('#doctorperformancereportsummarised').dataTable({
        "bJQueryUI": true,
    });

</script>