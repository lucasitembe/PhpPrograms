<style>
    .linkstyle{
        color:#3EB1D3;
    }

    .linkstyle:hover{
        cursor:pointer;
    }
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
    } elseif (isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$process_status = '';

if ($is_perf_by_signe_off == '1') {
    $process_status = "  AND ppl.Process_Status = 'signedoff'";
}

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];

$todayqr = mysqli_fetch_assoc(mysqli_query($conn,"SELECT NOW() AS TODAYDATE"))['TODAYDATE'];
$today = $todayqr; //date('Y-m-d H:m:s');

$Date_From = ''; //@$_POST['Date_From'];
$Date_To = ''; //@$_POST['Date_To'];
$Employee_ID = '';

if (!isset($_GET['Date_From'])) {
    $Date_From = date('Y-m-d H:m');
} else {
    $Date_From = $_GET['Date_From'];
}
if (!isset($_GET['Date_To'])) {
    $Date_To = date('Y-m-d H:m');
    ;
} else {
    $Date_To = $_GET['Date_To'];
}if (!isset($_GET['Employee_ID'])) {
    $Employee_ID = 0;
} else {
    $Employee_ID = $_GET['Employee_ID'];
}if (!isset($_GET['Sponsor_ID'])) {
    $Sponsor = "All";
} else {
    $Sponsor = $_GET['Sponsor_ID'];
}

$today = date('Y-m-d');
$thisDate = '';

$employeeID = $employee_ID = $Employee_ID; //exit;
$EmployeeName = strtoupper(mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employeeID'"))['Employee_Name']);

$process_status = '';

if ($is_perf_by_signe_off == '1') {
    $process_status = "  AND ppl.Process_Status = 'signedoff'";
}

$filter = "  ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employee_ID' AND Saved='yes' $process_status ";

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND pr.Sponsor_ID=$Sponsor";
}

$sql = "SELECT COUNT(c.Registration_ID) AS total_patient FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE $filter ";

$consultationPat = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$totalAllPat = "TOTAL PATIENT <span class='dates'>" . mysqli_fetch_assoc($consultationPat)['total_patient'] . '</span>';
?>
<a target="_blank" href='./printdoctorsperformancesummary.php?Employee_ID=<?php echo $employee_ID ?>&Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>&Sponsor_ID=<?php echo $_GET['Sponsor_ID'] ?>&src=indv' class='art-button-green'>
    PRINT
</a>
<a href='./individualdoctorsperformancesummary.php' class='art-button-green'>
    BACK
</a>

<br/><br/>

<fieldset style='overflow-y:scroll; height:500px'>
    <center>

        <legend  align="right" style="background-color:#006400;color:white;padding:5px;">
            <br/>
            <form action='individualdoctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>	 

        <table width='75%' border='0' id='actionsTable'>
            <tr>

                <td style="text-align: center"><b>From</b></td>
                <td style="text-align: center">
                    <input type='text' name='Date_From' id='date_From' required='required'>    
                </td>
                <td style="text-align: center">To</td>
                <td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required'></td> 
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
                    <input type='submit' name='Print_Filter'  id='Print_Filter' class='art-button-green' value='FILTER'>
                </td>
            </tr>	
        </table>
        </form>
    </center>
    <br>
    <legend align='center' style="background-color:#037DB0;color: white;padding: 5px;text-align: center">
        <b>MY PERFORMANCE REPORT SUMMARY</b><br/>
        <b>FROM <span class='dates'><?php echo date('j M, Y H:i:s', strtotime($Date_From)) ?></span> TO <span class='dates'><?php echo date('j M, Y H:i:s', strtotime($Date_To)) ?></span></b>
        <br/><b><?php echo $totalAllPat; ?></b>
    </legend>    
    <div >
        <?php
        $avoidDoctorNameDuplicate = 0;
        $Employee_Name_Cur = '';

        $dataRange = returnBetweenDates($Date_From, $Date_To);
        $totalPPP = 0;
        foreach ($dataRange as $value) {
            $thisDate = date('d, M y', strtotime($value)) . '';
            $sql = "SELECT  pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch  JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE $filter  AND  DATE(ch.cons_hist_Date)='$value'";

            $consultationDateRange = mysqli_query($conn,$sql) or die(mysqli_error($conn));

            if (mysqli_num_rows($consultationDateRange) > 0) {
                //$rowPatient = mysqli_fetch_array($consultationDateRange);
                $noOfPatient = mysqli_num_rows($consultationDateRange);
                $totalPPP +=$noOfPatient;
                echo "<div class='daterange'>$thisDate<span style='float:right'>PATIENT NO. $noOfPatient </span></div>";
                echo '<center><table width =100% border="1" class="display" >';
                echo "<thead>
                        <tr>
                          <th width=3% style='text-align:left'>SN</th>
                          <th style='text-align:left'>PATIENT NAME</th>
                          <th style='text-align:left'>SPONSOR</th>
                          <th style='text-align:left'>CONSULT</th>
                          <th style='text-align:left'>LAB</th> 
                          <th style='text-align:left'>RAD</th>
                          <th style='text-align:left'>PHAR</th>
                          <th style='text-align:left'>PROC</th>
                          <th style='text-align:left'>FINAL DIAG</th>
                          <th style='text-align:left'>PHONE</th>
                          <th style='text-align:left'>BILL</th>
                          <th style='text-align:left'>TIME</th>
                        </tr>
                      </thead>";
//retrieve consultations for the employee		
//$consultations=mysqli_query($conn,"SELECT pr.Patient_Name,pr.Phone_Number,co.Registration_ID,co.employee_ID,co.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID FROM tbl_consultation co JOIN tbl_patient_registration pr ON pr.Registration_ID=co.Registration_ID JOIN tbl_patient_payment_item_list ppl ON co.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE co.employee_ID='$employeeID' AND DATE(co.Consultation_Date_And_Time)='$today'") or die(mysqli_error($conn));
                $consultations = mysqli_query($conn,"SELECT pr.Patient_Name,sp.Guarantor_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ch.cons_hist_Date,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID  JOIN tbl_sponsor sp ON pr.Sponsor_ID=sp.Sponsor_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE $filter  AND  DATE(ch.cons_hist_Date)='$value'") or die(mysqli_error($conn));

//echo "SELECT pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employee_ID'";
                $empSN = 1;

                if (mysqli_num_rows($consultations) > 0) {


                    while ($row = mysqli_fetch_array($consultations)) {

                        $Registration_ID = $row['Registration_ID'];
                        $patient_name = $row['Patient_Name'];
                        $Guarantor_Name = $row['Guarantor_Name'];
                        $Patient_Payment_ID = $row['Patient_Payment_ID'];
                        $employeeName = $row['Employee_Name'];
                        $Employee_Name_Cur = $row['Employee_Name'];
                        $Phone_Number = $row['Phone_Number'];
                        $consultation_ID = $row['consultation_ID'];
                        $consultation_Date = $row['cons_hist_Date'];


                        $finalDiagnosis = "<span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >No</span>";
                        // $proviDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >No</span>";

                        $checkIfHasFinalDiagnosis = mysqli_query($conn,"
		   SELECT * FROM tbl_disease_consultation dc INNER JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  dc.employee_ID='$employeeID'  AND  DATE(dc.Disease_Consultation_Date_And_Time)='$value' AND dc.diagnosis_type='diagnosis'
		") or die(mysqli_error($conn));

                        // $checkIfHasprovisionalDiagnosis=mysqli_query($conn,"
                        // SELECT * FROM tbl_disease_consultation dc INNER JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  dc.employee_ID='$employeeID' AND dc.diagnosis_type='provisional_diagnosis'
                        // ") or die(mysql_error);
                        //echo "SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID' AND dc.diagnosis_type='diagnosis'";

                        if (mysqli_num_rows($checkIfHasFinalDiagnosis) > 0) {
                            $finalDiagnosis = "<span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >Yes</span>";
                        }

                        // if(mysqli_num_rows($checkIfHasprovisionalDiagnosis)>0){ 
                        // $proviDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >Yes</span>";
                        // }
                        //select checking type
                        $select_checking_type = mysqli_query($conn,"SELECT 
     (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID' AND  DATE(pc.Payment_Date_And_Time)='$value' ) as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID' AND  DATE(pc.Payment_Date_And_Time)='$value' ) as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  AND  DATE(pc.Payment_Date_And_Time)='$value' ) as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Registration_ID='$Registration_ID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  AND  DATE(pc.Payment_Date_And_Time)='$value' ) as Procedur
	 ") or die(mysqli_error($conn));

                        $rowChkType = mysqli_fetch_assoc($select_checking_type);
                        $Laboratory = $rowChkType['Laboratory'];
                        $Radiology = $rowChkType['Radiology'];
                        $Pharmacy = $rowChkType['Pharmacy'];
                        $Procedur = $rowChkType['Procedur'];
                        $hrefpatientfile = 'Patientfile_Record_Detail.php?consultation_ID=' . $consultation_ID . '&Registration_ID=' . $Registration_ID . '&Section=ManagementPatient&Employee_ID=' . $employeeID . '&Date_From=' . $_GET['Date_From'] . '&Date_To=' . $_GET['Date_To'] . '&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage&PatientFile=PatientFileThisForm';
                        //if( $avoidDoctorNameDuplicate==1){$employeeName='';}
                        echo "<tr><td>" . ($empSN++) . "</td>";
                        //echo "<td style='text-align:left'>".$employeeName."</td>";
                        echo "<td style='text-align:left'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >" . $patient_name . "</span></td>";
                        echo "<td style='text-align:left'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >" . $Guarantor_Name . "</span></td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >Yes</span></td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >" . ($Laboratory > 0 ? 'Yes' : 'No') . "</span></td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >" . ($Radiology > 0 ? 'Yes' : 'No') . "</span></td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >" . ($Pharmacy > 0 ? 'Yes' : 'No') . "</span></td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >" . ($Procedur > 0 ? 'Yes' : 'No') . "</span></td>";
                        echo "<td style='text-align:center'>" . ($finalDiagnosis) . "</td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >" . ($Phone_Number) . "</span></td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >" . ($Patient_Payment_ID) . "</span></td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . ")' class='linkstyle' >" . ($consultation_Date) . "</span></td>";
                        echo '</tr>';
                        // $avoidDoctorNameDuplicate=1;
                    }
                }
                echo '</table></center><br/>';
            }
        }

        function returnBetweenDates($startDate, $endDate) {
            $startStamp = strtotime($startDate);
            $endStamp = strtotime($endDate);

            if ($endStamp > $startStamp) {
                while ($endStamp >= $startStamp) {

                    $dateArr[] = date('Y-m-d', $startStamp);

                    $startStamp = strtotime(' +1 day ', $startStamp);
                }
                return $dateArr;
            } else {
                return $startDate;
            }
        }
        ?>    
        </table>

        </center>
    </div>
</fieldset>

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
                        $('#date_From').datetimepicker({value: '', step: 30});
                        $('#date_To').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            startDate: 'now'
                        });
                        $('#date_To').datetimepicker({value: '', step: 30});
</script>

<script>
    $('.display').dataTable({
        "bJQueryUI": true,
    });
</script>
<script>
    function filterEmployeePatients() {
        //alert('hallow');
        var doctorID, date_From, date_To;

        doctorID = document.getElementById('doctorID').value;
        date_From = document.getElementById('date_From').value;
        date_To = document.getElementById('date_To').value;
        //ajax requests

        var mypostrequest = new ajaxRequest();
        mypostrequest.onreadystatechange = function () {
            if (mypostrequest.readyState == 4) {
                if (mypostrequest.status == 200 || window.location.href.indexOf("http") == -1) {
                    document.getElementById("doctorsperformancetbl").innerHTML = mypostrequest.responseText;

                }
                else {
                    alert("An error has occured making the request");
                }
            }
        }
        var parameters = "filterDoctorsPatient=true&doctorID=" + doctorID + "&date_From=" + date_From + "&date_To=" + date_To;
        mypostrequest.open("POST", "filterPerformanceDoctorPatient.php", true);
        mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        mypostrequest.send(parameters);
        //alert(parameters);


        //alert(Price +" "+Quantity+" "+Discount+" "+ppil);
    }
</script>
<script>
    function ajaxRequest() {
        var activexmodes = ["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]; //activeX versions to check for in IE
        if (window.ActiveXObject) { //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
            for (var i = 0; i < activexmodes.length; i++) {
                try {
                    return new ActiveXObject(activexmodes[i]);
                }
                catch (e) {
                    //suppress error
                }
            }
        }
        else if (window.XMLHttpRequest) // if Mozilla, Safari etc
            return new XMLHttpRequest();
        else
            return false;
    }
</script>