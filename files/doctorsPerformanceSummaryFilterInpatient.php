<?php
include("./includes/header.php");
include("./includes/connection.php");
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

$Date_From = ''; //@$_POST['Date_From'];
$Date_To = ''; //@$_POST['Date_To'];

if (!isset($_GET['Date_From'])) {
    $Date_From = date('Y-m-d H:m');
} else {
    $Date_From = $_GET['Date_From'];
}
if (!isset($_GET['Sponsor_ID'])) {
    $Sponsor = '';
    ;
} else {
    $Sponsor = $_GET['Sponsor_ID'];
}

if (!isset($_GET['Date_To'])) {
    $Date_To = date('Y-m-d H:m');
    ;
} else {
    $Date_To = $_GET['Date_To'];
}

$filter="  wr.Ward_Round_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' ";

if (!empty($Sponsor) && $Sponsor != 'All') {
     $filter .="  AND pr.Sponsor_ID=$Sponsor";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
     $filter2 ="  AND pc.Sponsor_ID=$Sponsor";
}
else {
    $filter2 = "";
}
?>
<a href='./doctorsroundperfomancereport.php' class='art-button-green'>
    BACK
</a>
<a target="_blank" href='./printdoctorsroundperformance.php?<?php echo $_SERVER['QUERY_STRING'] ?>' class='art-button-green'>
    PRINT
</a>
<br/><br/>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<fieldset style='overflow-y:scroll; height:500px'>
    <center>

        <legend  align="right" style="background-color:#006400;color:white;padding:5px;"><form action='doctorsPerformanceSummaryFilterInpatient.php' method='get' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>
        <br/>
        <table width='75%'>
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
                    <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
                </td>
            </tr>
        </table>
        </form>
        <!--End datetimepicker-->
    </center>
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>DOCTOR'S ROUND REPORT SUMMARY &nbsp;&nbsp;From&nbsp;&nbsp;</b><b style="color:#e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_From)) ?> </b><b>TO</b> <b style="color: #e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_To)) ?></b></legend>
    <center>
        <?php
        echo '<center><table width =100% border="1" class="display" id="doctorsperformancetbl">';
        echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
          <th>LAB SENT</th>
          <th>RAD SENT</th>
          <th>PHARM SENT</th>
          <th>PROC SENT</th>
          <th>TOTAL PATIENTS</th>
			    <th style='text-align: left;' width=18%>TOTAL ITEMS ORDERED</th>
		     </tr></thead>";
        //run the query to select all data from the database according to the branch id
        $select_doctor_query = "SELECT DISTINCT(emp.Employee_ID),emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp INNER JOIN tbl_ward_round wr ON wr.Employee_ID=emp.Employee_ID  WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";


        $select_doctor_result = mysqli_query($conn,$select_doctor_query) or die(mysqli_error($conn));

        $empSN = 0;
        while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
            $employeeID = $select_doctor_row['Employee_ID'];
            $employeeName = $select_doctor_row['Employee_Name'];
            //$employeeNumber=$select_doctor_row['Employee_Number'];


              $lab_total_count = 0;
              $rad_total_count = 0;
              $pham_total_count = 0;
              $proc_total_count = 0;
              $select_checking_type = mysqli_query($conn,"SELECT
              (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND pc.Employee_ID=$employeeID AND  (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Laboratory,

              (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND pc.Employee_ID=$employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Radiology,

              (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND pc.Employee_ID=$employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Pharmacy,

              (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND pc.Employee_ID=$employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Procedur
              ") or die(mysqli_error($conn));
              while($count_spefic = mysqli_fetch_assoc($select_checking_type)){
                $lab_total = $count_spefic['Laboratory'];
                $rad_total = $count_spefic['Radiology'];
                $pham_total = $count_spefic['Pharmacy'];
                $proc_total = $count_spefic['Procedur'];

                $lab_total_count = $lab_total_count + $lab_total;
                $rad_total_count = $rad_total_count + $rad_total;
                $pham_total_count = $pham_total_count + $pham_total;
                $proc_total_count = $proc_total_count + $proc_total;
              }
              //die("SELECT COUNT(rg.Registration_ID) AS numberOfPatients FROM tbl_patient_registration as rg, tbl_payment_cache as pc WHERE pc.Registration_ID = rg.Registration_ID $filter2 AND pc.Employee_ID = $Employee_ID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'");

              $total_patient = $lab_total_count + $rad_total_count + $pham_total_count + $proc_total_count;

              $result_patient_no = mysqli_query($conn,"SELECT COUNT(rg.Registration_ID) AS numberOfPatients FROM tbl_patient_registration as rg, tbl_payment_cache as pc WHERE pc.Registration_ID = rg.Registration_ID $filter2 AND pc.Employee_ID = $employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'") or die(mysqli_error($conn));


                $patient_no_number = mysqli_fetch_assoc($result_patient_no)['numberOfPatients'];

                $empSN ++;
                echo "<tr><td>" . ($empSN) . "</td>";
                echo "<td style='text-align:left'><a href='doctorsperformancefilterdetails_inpatient.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&Sponsor_ID=$Sponsor'>" . $employeeName . "</b></td>";
                echo "<td>$lab_total_count </td>";
                echo "<td>$rad_total_count </td>";
                echo "<td>$pham_total_count</td>";
                echo "<td>$proc_total_count</td>";
                echo "<td>$patient_no_number</td>";
                echo "<td style='text-align:center'><a href='doctorsperformancefilterdetails_inpatient.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&Sponsor_ID=$Sponsor'>" . number_format($total_patient) . "</b></td></tr>";

        }
        ?>
        </table>
        <table>
        </table>
    </center>
</center>
</fieldset>
<table>
                            <!--<tr>
                                <td style='text-align: center;'>
                                    <a href="previewDoctorPerformance.php?PreviewPerformanceReportThisPage=ThisPage" target="_blank">
                                            <input type='submit' name='previewDoctorPerformance' id='previewDoctorPerformance' target='_blank' class='art-button-green' value='PREVIEW ALL'>

                                    </a>
                                </td>
</tr>-->
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
                        $('#date_From').datetimepicker({value: '', step: 30});
                        $('#date_To').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            startDate: 'now'
                        });
                        $('#date_To').datetimepicker({value: '', step: 30});
</script>

<script>
    $('#doctorsperformancetbl').dataTable({
        "bJQueryUI": true,
    });
</script>
