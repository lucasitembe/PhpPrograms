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
$employee_ID = $_SESSION['userinfo']['Employee_ID'];

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];

?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['General_Ledger'] == 'yes') {
        ?>
        <a href='./individualdoctorsperformancesummary.php?DoctorsPerformanceReportThisPage' class='art-button-green'>
            BACK
        </a>
    <?php }
} ?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br/><br/>
<?php
if (isset($_POST['Date_From'])) {
    $Date_From = mysqli_real_escape_string($conn,$_POST['Date_From']);
    $Date_To = mysqli_real_escape_string($conn,$_POST['Date_To']);
}
if (isset($_GET['Date_From'])) {
    $Date_From = mysqli_real_escape_string($conn,$_GET['Date_From']);
    $Date_To = mysqli_real_escape_string($conn,$_GET['Date_To']);
} else {
    $Date_From = '';
    $Date_To = '';
}if (!isset($_GET['Sponsor_ID'])) {
    $Sponsor = "All";
} else {
    $Sponsor = $_GET['Sponsor_ID'];
}
?>
<br>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
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
<fieldset style='height: 300px'>
    <center>
        <br>
        <form action='individualdoctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
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
    <script src="css/jquery.js"></script>
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
    <!--End datetimepicker-->
    <?php
    $Date_From = '';
    $Date_To = '';
     if (isset($_GET['Date_From'])) {
        $Date_From = $_GET['Date_From'];
        $Date_To = $_GET['Date_To'];
        $Sponsor = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
    }
    if (isset($_POST['Date_From'])) {
        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];
        $Sponsor = mysqli_real_escape_string($conn,$_POST['Sponsor_ID']);
    }

   $process_status='';
    
    if($is_perf_by_signe_off=='1'){
       $process_status ="  AND ppl.Process_Status = 'signedoff'";
    }

    $filter = "  AND ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND  Saved='yes' $process_status";
    

    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND pr.Sponsor_ID=$Sponsor";
    }
    ?>
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
        
            $employeeID = $_SESSION['userinfo']['Employee_ID'];
            //$employeeNumber=$select_doctor_row['Employee_Number'];

            $result_patient_no = mysqli_query($conn,"SELECT COUNT(c.consultation_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID FROM 
                     tbl_consultation_history ch  
                     JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID 
                     JOIN tbl_employee e ON ch.employee_ID=e.employee_ID 
                     JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID 
                     JOIN tbl_patient_payment_item_list ppl ON  ppl.Patient_Payment_Item_List_ID=c.Patient_Payment_Item_List_ID
                     WHERE  ch.employee_ID='$employeeID' $filter ") or die(mysqli_error($conn));

            $patient = mysqli_fetch_array($result_patient_no);
            $patient_no_number=$patient['numberOfPatients'];
            $employeeName=$patient['Employee_Name'];			    
            //}

            $empSN =1;
            echo "<tr><td>" . ($empSN++) . "</td>";
            echo "<td style='text-align:left'><a href='individualdoctorsperformancepatientsummary.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&Sponsor_ID=$Sponsor'>" . $employeeName . "</a></td>";
            echo "<td style='text-align:center'>" . number_format($patient_no_number) . "</td>";
            echo "</tr>";

?>

        </table>
    </center>
</center>
</fieldset>
<table>
    <tr>
        <td style='text-align: center;'>
           
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