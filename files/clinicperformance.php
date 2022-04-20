<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Management_Works'])){
	    if($_SESSION['userinfo']['Management_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Management_Works'] == 'yes'){ 
?>
    
<?php  } } ?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<?php

$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$Employee_Type=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Type'];
?>
    <a href='./managementworkspage.php?Reception=ReceptionThisPage' class='art-button-green'>
        BACK
    </a>
<?php  ?>



<br/><br/>

<fieldset>
    <center>
    <form action='clinicperformanceFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width='60%'>
        <tr>
            <td style="text-align: center"><b>From</b></td>
	    <td style="text-align: center">
		<input type='text' name='Date_From' id='date_From' required='required'>    
	    </td>
            <td style="text-align: center">To</td>
	    <td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required'></td>    
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
        </tr>	
    </table>
    </form>

	<!--End datetimepicker-->
</center>
    <br>
    <legend align=center><b>CLINIC PERFORMANCE REPORT SUMMARY</b></legend>
        <center>
            <?php
		echo '<center><table id="clinicperformance" class="display" width =100% border=1 >';
		echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
			    <th style='text-align: left;' width=30%>NUMBER OF PATIENTS</th>
		     </tr></thead>";
		    //run the query to select all data from the database according to the branch id
		    $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";
		    
		   
		    $select_doctor_result = mysqli_query($conn,$select_doctor_query);
		    $Receipt_Date=date('Y-m-d');
		    $empSN=0;
		    while($select_doctor_row=mysqli_fetch_array($select_doctor_result)){//select doctor
			$employeeID=$select_doctor_row['Employee_ID'];
			$employeeName=$select_doctor_row['Employee_Name'];
			//$employeeNumber=$select_doctor_row['Employee_Number'];
			
			$select_patient_item_list=mysqli_query($conn,"
								    SELECT COUNT(co.Registration_ID) AS numberOfPatients FROM tbl_consultation co,tbl_clinic c,tbl_clinic_employee ce,tbl_patient_payment_item_list ppl,tbl_patient_payments pp 
									WHERE ppl.Patient_Payment_Item_List_ID =co.Patient_Payment_Item_List_ID
									AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID
									AND ppl.Consultant_ID=ce.Clinic_ID
									AND ce.Employee_ID=co.Employee_ID
									AND co.Employee_ID='$employeeID'
									AND ce.Clinic_ID=c.Clinic_ID
									AND ppl.Process_Status='signedoff'
									AND ppl.Patient_Direction='Direct To Clinic'
									AND pp.Receipt_Date='$Receipt_Date' ");
			while($select_patient_item_list_row=mysqli_fetch_array($select_patient_item_list)){
			    $numberOFPatients=$select_patient_item_list_row['numberOfPatients'];			    
			}
			
			$empSN ++;
			echo "<tr><td>".($empSN)."</td>";
			echo "<td style='text-align:left'>".$employeeName."</td>";
			echo "<td style='text-align:right'>".number_format(0)."</td></tr>";
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
   $('#clinicperformance').dataTable({
    "bJQueryUI":true,
	});
</script>
<script>
$('#date_From').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:	'now'
});
$('#date_From').datetimepicker({value:'',step:30});
$('#date_To').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:'now'
});
$('#date_To').datetimepicker({value:'',step:30});
</script>