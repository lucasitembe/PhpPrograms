<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Mtuha_Reports'])) {
        if ($_SESSION['userinfo']['Mtuha_Reports'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$is_perf_by_signe_off=$_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];

$employee_ID = $_SESSION['userinfo']['Employee_ID'];
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Mtuha_Reports'] == 'yes') {
        ?>
        <a href='./doctorsperformancesummarydhis.php' class='art-button-green'>
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
if (isset($_POST[''])) {
    $Date_From = mysqli_real_escape_string($conn,$_POST['Date_From']);
    $Date_To = mysqli_real_escape_string($conn,$_POST['Date_To']);
} else {
    $Date_From = '';
    $Date_To = '';
}
?>

<br/>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style> 
<fieldset style='overflow-y:scroll; height:440px' >
    <center>

        <legend  align="right" style="background-color:#006400;color:white;padding:5px;"><form action='doctorsPerformanceSummaryFilterdhis.php' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>	 

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
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>DOCTOR'S PERFORMANCE REPORT SUMMARY &nbsp;&nbsp;From&nbsp;&nbsp;</b><b style="color:#e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_From)) ?> </b><b>TO</b> <b style="color: #e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_To)) ?></b></legend>
    <center>
        <?php
        echo '<center><table width =100% border="1" id="doctorperformancereportsummarised" class="display">';
        echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
			    <th style='text-align: left;' width=12%>PATIENTS</th>
		     </tr></thead>";
        //run the query to select all data from the database according to the branch id
        $select_doctor_query = "SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";


        $select_doctor_result = mysqli_query($conn,$select_doctor_query);

        $empSN = 0;
        while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
            $employeeID = $select_doctor_row['Employee_ID'];
            $employeeName = $select_doctor_row['Employee_Name'];
            //$employeeNumber=$select_doctor_row['Employee_Number'];
           //ECHO"SELECT COUNT(c.consultation_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID WHERE  ch.employee_ID='$employeeID' $filter <BR/>";
            $result_patient_no = mysqli_query($conn,"SELECT COUNT(c.consultation_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch "
                    . "JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID "
                    . "JOIN tbl_employee e ON ch.employee_ID=e.employee_ID 
                      JOIN tbl_patient_payment_item_list ppl ON  ppl.Patient_Payment_Item_List_ID=c.Patient_Payment_Item_List_ID  "
                    . "JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID WHERE ch.employee_ID='$employeeID' $filter ") or die(mysqli_error($conn));

            $patient_no_number = mysqli_fetch_array($result_patient_no)['numberOfPatients'];
            
            $empSN ++;
            echo "<tr><td>" . ($empSN) . "</td>";
            echo "<td style='text-align:left'><a href='doctorsperformancefilterdetailsdhis.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&Sponsor_ID=$Sponsor'>" . $employeeName . "</a></td>";
            echo "<td style='text-align:center'>" . number_format($patient_no_number) . "</td></tr>";
        }
        ?>

        </table>
    </center>
</center>
</fieldset>
<table width="100%">
    <tr>
        <td style='text-align: left;'>
            <a href="previewFilterDoctorPerformance.php?Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>&Sponsor_ID=<?php echo $Sponsor ?>" target="_blank">

                <input type='submit' name='previewFilterDoctorPerformance' id='previewFilterDoctorPerformance' target='_blank' class='art-button-green' value='PREVIEW ALL'>

            </a>
        </td>
        <td style='text-align: right;'>
            <h1 id="sumValues" style="text-align:right;font-size:18px;padding-right: 40px"></h1>
        </td>

    </tr>
</table>
<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<script src="media/js/sum().js"></script>
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
    $(document).ready(function () {

        //var total,pageTotal;
        $('#doctorperformancereportsummarised').dataTable({
            "bJQueryUI": true,
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };
                // Total over all pages
                total = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        });

                // Total over this page
                pageTotal = api
                        .column(2, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                //Update footer
//                            $( api.column( 2 ).footer() ).html(
//                                ''+pageTotal +' ( '+ total +' total)'
//                            );

                $('#sumValues').html('' + pageTotal + ' ( ' + total + ' total)');
            }
        });
    });

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }


</script>