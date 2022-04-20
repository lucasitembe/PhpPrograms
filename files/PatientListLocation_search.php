<?php
include("./includes/connection.php");
if(isset($_GET['Patient_Name'])){
	
$Patient_Name=mysqli_real_escape_string($conn,$_GET['Patient_Name']);

$patient_query="pr.Patient_Name LIKE '%$Patient_Name%'";

} else { 
$patient_query='';
}


?>

<?php
echo '<center><table width =100% border="1" id="doctorperformancereportsummarised" class="display">';
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
   //$employeeID = $select_doctor_row['Employee_ID'];
    //$employeeName = $select_doctor_row['Employee_Name'];
    //$employeeNumber=$select_doctor_row['Employee_Number'];
    //ECHO"SELECT COUNT(c.consultation_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID WHERE  ch.employee_ID='$employeeID' $filter <BR/>";
    $result_patient_no = mysqli_query($conn,"SELECT c.consultation_ID,pr.Patient_Name,pr.Registration_ID,tpc.Payment_Cache_ID,te.Employee_Name FROM tbl_consultation_history ch "
            . "JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID "
            . "JOIN tbl_employee e ON ch.employee_ID=e.employee_ID 
                      JOIN tbl_patient_payment_item_list ppl ON  ppl.Patient_Payment_Item_List_ID=c.Patient_Payment_Item_List_ID  "
            . "JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_payment_cache tpc ON tpc.Registration_ID=pr.Registration_ID JOIN tbl_item_list_cache ilc ON ilc.Payment_Cache_ID=tpc.Payment_Cache_ID JOIN tbl_employee te ON te.Employee_ID=ch.Employee_ID WHERE $patient_query group by pr.Registration_ID LIMIT 50") or die(mysqli_error($conn));

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

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<script src="media/js/sum().js"></script>
<script src="css/jquery.datetimepicker.js"></script>

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
