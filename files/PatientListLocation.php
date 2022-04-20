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

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];

$employee_ID = $_SESSION['userinfo']['Employee_ID'];

if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
    $Date_To = $_GET['Date_To'];
    $Sponsor = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
   // $Process_status_filt = mysqli_real_escape_string($conn,$_GET['Process_status']);
}
if (isset($_POST['Date_From'])) {
    $Date_From = $_POST['Date_From'];
    $Date_To = $_POST['Date_To'];
    $Sponsor = mysqli_real_escape_string($conn,$_POST['Sponsor_ID']);
    //$Process_status_filt = mysqli_real_escape_string($conn,$_POST['Process_status']);
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['General_Ledger'] == 'yes') {
        ?>
       <!-- <a href="previewFilterDoctorPerformance.php?Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>&Sponsor_ID=<?php echo $Sponsor ?>"  class='art-button-green' target="_blank" id='previewFilterDoctorPerformance'>
            PREVIEW ALL
        </a>--->
        <a href='./receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage'  class='art-button-green'>
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
<br/>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style> 
<fieldset style='overflow-y:scroll; height:550px' >
    <center>

        <legend  align="right" style="background-color:#006400;color:white;padding:5px;"><form action='PatientListLocation.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>	 

        <!--<form action='doctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">-->
        <br/>
        <table width='100%'>
            <tr>
                <td style="text-align: center"><b>From</b></td>
                <td style="text-align: center">
                    <input type='text' name='Date_From' id='date_From' required='required'>    
                </td>
                <td style="text-align: center">To</td>
                <td style="text-align: center"><input type='text' name='Date_To' id='date_To'        required='required'></td>

				<td style="text-align: center">
				 <input type='text' placeholder='~~~~Search patient name~~~~' oninput='searchName(this.value)'>
				</td>
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
<!--                <td style="text-align: center">
                        <select name='Process_status' id='Process_status' onchange="filterPatient()" style='text-align: center;width:100%;display:inline'>
                           <option ></option>
                           <option value="signedoff">Signed Off</option>
                           <option value="served">Not Signed Off</option>
                       </select>
                </td>-->
                <td style='text-align: center;'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
                </td>
            </tr>	
        </table>
        </form> 
    </center>
    <!--End datetimepicker-->
    <?php

    $filter = "ch.cons_hist_Date BETWEEN '$Date_From' AND NOW()";


    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND pr.Sponsor_ID=$Sponsor";
    }
    ?>
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>PATIENT LOCATION &nbsp;&nbsp;From&nbsp;&nbsp;</b><b style="color:#e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_From)) ?> </b><b>TO</b> <b style="color: #e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_To)) ?></b></legend>
    <center>
<?php
echo '<div id="Search_Iframe"><center><table width =100% border="1" id="doctorperformancereportsummarised" class="display">';
echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>PATIENT'S NAME</th>
			    <th style='text-align: left;' width=12%>PATIENT'S NO</th>
				 <th style='text-align: left;' width=12%>CONSULTANT</th>
                            <th style='text-align: left;' width=12%>LAB</th>
                            <th style='text-align: left;' width=12%>RAD</th>
                            <th style='text-align: left;' width=12%>PHAR</th>
                            <th style='text-align: left;' width=12%>PROC</th>
                            <th style='text-align: left;' width=12%>SURG</th>
		     </tr></thead>";
//run the query to select all data from the database according to the branch id
//$select_doctor_query = "SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";

//$select_doctor_result = mysqli_query($conn,$select_doctor_query);

//$empSN = 0;
//while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
    $employeeID = $select_doctor_row['Employee_ID'];
    $employeeName = $select_doctor_row['Employee_Name'];
    //$employeeNumber=$select_doctor_row['Employee_Number'];
    //ECHO"SELECT COUNT(c.consultation_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID WHERE  ch.employee_ID='$employeeID' $filter <BR/>";

    $result_patient_no = mysqli_query($conn,"SELECT c.consultation_ID,pr.Patient_Name,pr.Registration_ID,tpc.Payment_Cache_ID,te.Employee_Name FROM tbl_consultation_history ch "
            . "JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID "
            . "JOIN tbl_employee e ON ch.employee_ID=e.employee_ID 
                      JOIN tbl_patient_payment_item_list ppl ON  ppl.Patient_Payment_Item_List_ID=c.Patient_Payment_Item_List_ID  "
            . "JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_payment_cache tpc ON tpc.Registration_ID=pr.Registration_ID JOIN tbl_item_list_cache ilc ON ilc.Payment_Cache_ID=tpc.Payment_Cache_ID JOIN tbl_employee te ON te.Employee_ID=ch.Employee_ID WHERE $filter group by pr.Registration_ID LIMIT 50") or die(mysqli_error($conn));

    while ($patientdt = mysqli_fetch_array($result_patient_no)) {
	$Patient_Name=$patientdt['Patient_Name'];
	$Registration_ID=$patientdt['Registration_ID'];	
	$Payment_Cache_ID=$patientdt['Payment_Cache_ID'];
	$Employee_Name=$patientdt['Employee_Name'];
	$empSN ++;
    echo "<tr><td>" . ($empSN) . "</td>";
    echo "<td style='text-align:left'><a href='#'>" . $Patient_Name . "</a></td>";
    echo "<td style='text-align:center'>" .$Registration_ID. "</td>";
    echo "<td style='text-align:left'>" .$Employee_Name. "</td>";
   
   
   //lab services
	$labquery=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Laboratory'"); 
	$num_lab=mysqli_num_rows($labquery);
	
	if($num_lab>0){
	$lab_status=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Laboratory' AND Status='active' AND Status<>'removed'"); 
	$num_status=mysqli_num_rows($lab_status);
	if($num_status>0){
		$status='Pending';
	}else{
		
		$status='Done';
	}

	} else {
	 $status='Not Ordered';
	}

	//RadiologyTotal
	$radquery=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Radiology'"); 
	$num_rad=mysqli_num_rows($radquery);
	
	if($num_rad>0){
	$rad_status=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Radiology' AND Status='active' AND Status<>'removed'"); 
	$num_rad_status=mysqli_num_rows($rad_status);
	if($num_rad_status>0){
		$status_rad='Pending';
	}else{
		
		$status_rad='Done';
	}

	} else {
	 $status_rad='Not Ordered';
	}
	
	//Pharmacy
	$pharmquery=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Pharmacy'"); 
	$num_pharm=mysqli_num_rows($pharmquery);
	
	if($num_pharm>0){
	$pharm_status=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Pharmacy' AND Status='active' AND Status<>'removed'"); 
	$num_pharm_status=mysqli_num_rows($pharm_status);
	if($num_pharm_status>0){
		$status_pharm='Pending';
	}else{
		
		$status_pharm='Done';
	}

	} else {
	 $status_pharm='Not Ordered';
	}
	
	//Procedure
	$procequery=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Procedure'"); 
	$num_proce=mysqli_num_rows($procequery);
	
	if($num_proce>0){
	$proce_status=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Procedure' AND Status='active' AND Status<>'removed'"); 
	$num_proce_status=mysqli_num_rows($proce_status);
	if($num_proce_status>0){
		$status_proce='Pending';
	}else{
		
		$status_proce='Done';
	}

	} else {
	 $status_proce='Not Ordered';
	}
	
	
	//Surgery
	$surgquery=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Surgery'"); 
	$num_surg=mysqli_num_rows($surgquery);
	
	if($num_surg>0){
	$surg_status=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Check_In_Type='Surgery' AND Status='active' AND Status<>'removed'"); 
	$num_surg_status=mysqli_num_rows($surg_status);
	if($num_surg_status>0){
		$status_surg='Pending';
	}else{
		
		$status_surg='Done';
	}

	} else {
	 $status_surg='Not Ordered';
	}
	
    echo "<td style='text-align:left'>" .$status. "</td>";
    echo "<td style='text-align:left'>" . $status_rad . "</td>";
    echo "<td style='text-align:left'>" . $status_pharm. "</td>";
    echo "<td style='text-align:left'>" .$status_proce. "</td>";
    echo "<td style='text-align:left'>" . $status_surg. "</td></tr>";
    }
    

?>

        </table>
		</div>
    </center>
</center>
</fieldset>

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


<script language="javascript" type="text/javascript">
    function searchName(name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='PatientListLocation_search.php?Patient_Name="+name+"'></iframe>";
	}
</script>
