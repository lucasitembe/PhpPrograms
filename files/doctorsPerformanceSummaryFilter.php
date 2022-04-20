<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {

} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];

$employee_ID = $_SESSION['userinfo']['Employee_ID'];

if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
    $Date_To = $_GET['Date_To'];
    $Sponsor = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
   // $Process_status_filt = mysqli_real_escape_string($conn,$_GET['Process_status']);
}

$type_of_doctor_consultation="All";
if (isset($_POST['Date_From'])) {
    $Date_From = $_POST['Date_From'];
    $Date_To = $_POST['Date_To'];
    $Sponsor = mysqli_real_escape_string($conn,$_POST['Sponsor_ID']);
    $type_of_doctor_consultation = mysqli_real_escape_string($conn,$_POST['type_of_doctor_consultation']);
    //$Process_status_filt = mysqli_real_escape_string($conn,$_POST['Process_status']);
}else{
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Date_From = $Filter_Value.' 00:00';
    $Date_To = $Current_Date_Time;
}

if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
    $Date_To = $_GET['Date_To'];
    $Sponsor = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
}
$consultation_type="";
if($type_of_doctor_consultation!="All"){
    $consultation_type="AND ch.consultation_type='$type_of_doctor_consultation'";
}
$filter_my_perfomance_report="";
if((isset($_GET['from_doctors_page'])&&$_GET['from_doctors_page']=="yes")||(isset($_POST['from_doctors_page'])&&$_POST['from_doctors_page']=="yes")){
  $from_doctors_page="yes"; 
  $filter_my_perfomance_report=" AND employee_ID='$employee_ID'";
}else{
    $from_doctors_page="no";
}


if(isset($_GET['this_page_from'])){
   $this_page_from=$_GET['this_page_from'];
}else{
   $this_page_from=""; 
}

//if (isset($_SESSION['userinfo'])) {
//    if ($_SESSION['userinfo']['General_Ledger'] == 'yes') {
    if($from_doctors_page=="no"){
        ?>
        <a href="previewFilterDoctorPerformance.php?Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>&Sponsor_ID=<?php echo $Sponsor ?>&type_of_doctor_consultation=<?= $type_of_doctor_consultation ?>"  class='art-button-green' target="_blank" id='previewFilterDoctorPerformance'>
            PREVIEW ALL
        </a>
    <?php  ?>
        <a href='managementworkspage.php?ManagementWorksPage=ManagementWorksPageThisPage'  class='art-button-green'>
            BACK
        </a>
        <?php
    }else{ 
        
      ?>
        <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage&this_page_from=<?= $this_page_from; ?>&from_doctors_page<?= $from_doctors_page; ?>'  class='art-button-green'>
            BACK
        </a>

        <?php
    }

?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br/><br/>
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

        <legend  align="right" style="background-color:#006400;color:white;padding:5px;"><form action='doctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>	 

        <br/>
        <table width='69%'>
            <tr>
                <td style="text-align: center"><b>From</b></td>
                <td style="text-align: center">
                    <input type='text' name='Date_From' id='date_From' value="<?= $Date_From ?>" required='required'>    
                </td>
                <td style="text-align: center">To</td>
                <td style="text-align: center"><input type='text' name='Date_To' id='date_To' value="<?= $Date_To ?>"       required='required'></td> 
                <td style="text-align: center">
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()"class="form-control" style='text-align: center;width:100%;display:inline'>
                        <option value="All">All Sponsors</option>
                        <?php
                        $qr = "SELECT * FROM tbl_sponsor";
                        $sponsor_results = mysqli_query($conn,$qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option <?php if($Sponsor==$sponsor_rows['Sponsor_ID'])echo "selected='selected'"; ?>value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="type_of_doctor_consultation">
                        <option value="All">All Patient</option>
                        <option <?php if($type_of_doctor_consultation=="new_consultation")echo "selected='selected'"; ?>value="new_consultation">New Consultation</option>
                        <option <?php if($type_of_doctor_consultation=="result_consultation")echo "selected='selected'"; ?>value="result_consultation">Result Consultation</option>
                    </select>
                </td>

            <input type="text" value="<?= $from_doctors_page ?>" hidden="hidden"name="from_doctors_page"/>
                <td style='text-align: center;'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
                </td>
            </tr>	
        </table>
        </form> 
    </center>
    <!--End datetimepicker-->
    <?php
    $process_status = '';

    if ($is_perf_by_signe_off == '1') {
        $process_status = "  AND ppl.Process_Status = 'signedoff'";
    }


    $filter = "  AND ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND  Saved='yes' $process_status ";
    $filter2 = " ";


    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND pr.Sponsor_ID='$Sponsor'";
        $filter2="   AND pr.Sponsor_ID='$Sponsor'";
    }
    ?>
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>DOCTOR'S PERFORMANCE REPORT SUMMARY &nbsp;&nbsp;From&nbsp;&nbsp;</b><b style="color:yellow;"><?php echo date('j F, Y H:i:s', strtotime($Date_From)) ?> </b><b>TO</b> <b style="color: yellow;"><?php echo date('j F, Y H:i:s', strtotime($Date_To)) ?></b></legend>
    <center>
<?php
echo '<center><table width =100% border="1" id="doctorperformancereportsummarised" class="display">';
echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
			    <th style='text-align: left;' width=12%>TOTAL PATIENTS</th>
                            <th style='text-align: left;' width=12%>LAB SENT</th>
                            <th style='text-align: left;' width=12%>RAD SENT</th>
                            <th style='text-align: left;' width=12%>PHAR SENT</th>
                            <th style='text-align: left;' width=12%>PROC SENT</th>
                            <th style='text-align: left;' width=12%>SURG SENT</th>
		     </tr></thead>";
//run the query to select all data from the database according to the branch id

$select_doctor_query = "SELECT Employee_ID, Employee_Name FROM tbl_employee em WHERE Account_Status = 'active' AND Employee_ID IN(SELECT employee_ID FROM tbl_consultation_history WHERE cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND Saved = 'yes' $filter_my_perfomance_report) ORDER BY Employee_Name ASC";



$select_doctor_result = mysqli_query($conn,$select_doctor_query);

$empSN = 0;
while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
    $employeeID = $select_doctor_row['Employee_ID'];
    $employeeName = $select_doctor_row['Employee_Name'];
    

    $result_patient_no = mysqli_query($conn,"SELECT c.consultation_ID FROM tbl_consultation_history ch "
            . "JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID "
            . "JOIN tbl_employee e ON ch.employee_ID=e.employee_ID 
                      JOIN tbl_patient_payment_item_list ppl ON  ppl.Patient_Payment_Item_List_ID=c.Patient_Payment_Item_List_ID  "
            . "JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID WHERE ch.employee_ID='$employeeID' $consultation_type $filter ") or die(mysqli_error($conn));
//    }
    $patient_no_number = mysqli_num_rows($result_patient_no);

    $LaboratoryTotal = 0;
    $RadiologyTotal = 0;
    $PharmacyTotal = 0;
    $ProcedurTotal = 0;
    $SurgeryTotal = 0;

    while ($patientdt = mysqli_fetch_array($result_patient_no)) {
        $consultation_ID = $patientdt['consultation_ID'];

        $select_checking_type = mysqli_query($conn,"SELECT 
         (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND ilc.Consultant_ID='$employeeID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID') as Laboratory, 
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND ilc.Consultant_ID='$employeeID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID' ) as Radiology,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND ilc.Consultant_ID='$employeeID' AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  ) as Pharmacy,
	 
	 (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND ilc.Consultant_ID='$employeeID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  ) as Procedur,
         
         (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Surgery' AND ilc.Consultant_ID='$employeeID' AND pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND pc.consultation_id='$consultation_ID'  ) as Surger
	 ") or die(mysqli_error($conn));



        $rowChkType = mysqli_fetch_assoc($select_checking_type);
        $Laboratory = $rowChkType['Laboratory'];
        $Radiology = $rowChkType['Radiology'];
        $Pharmacy = $rowChkType['Pharmacy'];
        $Procedur = $rowChkType['Procedur'];
        $Surger = $rowChkType['Surger'];

        //Total
        if ($Laboratory > 0) {
            $LaboratoryTotal += 1;
        }
        if ($Radiology > 0) {
            $RadiologyTotal += 1;
        }
        if ($Pharmacy > 0) {
            $PharmacyTotal += 1;
        }
        if ($Procedur > 0) {
            $ProcedurTotal += 1;
        }
        if ($Surger > 0) {
            $SurgeryTotal += 1;
        }
    }



    $empSN ++;
    echo "<tr><td>" . ($empSN) . "</td>";
    echo "<td style='text-align:left'><a href='doctorsperformancefilterdetails.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&Sponsor_ID=$Sponsor&consultation_type=$type_of_doctor_consultation&from_doctors_page=$from_doctors_page' target='_blank'>" . $employeeName . "</a></td>";
    echo "<td style='text-align:center'>" . number_format($patient_no_number) . "</td>";
    echo "<td style='text-align:center'>" . number_format($LaboratoryTotal) . "</td>";
    echo "<td style='text-align:center'>" . number_format($RadiologyTotal) . "</td>";
    echo "<td style='text-align:center'>" . number_format($PharmacyTotal) . "</td>";
    echo "<td style='text-align:center'>" . number_format($ProcedurTotal) . "</td>";
    echo "<td style='text-align:center'>" . number_format($SurgeryTotal) . "</td></tr>";

}
?>

        </table>
    </center>
</center>
</fieldset>
<table width="100%">
    <tr>
        <td style='text-align: left;'>

        </td>
        <td style='text-align: right;'>
            <h1 id="sumValues" style="text-align:right;font-size:10px;padding-right: 40px"></h1>
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