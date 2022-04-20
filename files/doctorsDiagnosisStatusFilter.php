<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Mtuha_Reports'])){
	    if($_SESSION['userinfo']['Mtuha_Reports'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    $employee_ID=$_SESSION['userinfo']['Employee_ID'];
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
?>
<a href='./doctorsDiagnosisStatus.php?DoctorsPerformanceReportThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/>
<?php
  
	$Date_From='';
	$Date_To='';
        
?>

<br/>
<fieldset style='overflow-y:scroll; height:440px' >
    <center>
	
	<legend  align="right" style="background-color:#006400;color:white;padding:5px;"><form action='doctorsDiagnosisStatusFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>	 
	
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
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
        </tr>	
    </table>
    </form> 
</center>
	<!--End datetimepicker-->
    <?php
        if(isset($_GET['Date_From'])){
           $Date_From=$_GET['Date_From'];
           $Date_To=$_GET['Date_To'];
        }
       elseif(isset($_POST['Date_From'])){
           $Date_From=$_POST['Date_From'];
           $Date_To=$_POST['Date_To'];
       }else{
           $Date_From=$today;
           $Date_To=$today;
       }
        
    ?>
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>DOCTORS WITH NO FINAL DIAGNOSIS REPORT SUMMARY &nbsp;&nbsp;From&nbsp;&nbsp;</b><b style="color:#e0d8d8;"><?php echo date('j F, Y H:i:s',strtotime($Date_From))?> </b><b>TO</b> <b style="color: #e0d8d8;"><?php echo date('j F, Y H:i:s' ,strtotime($Date_To))?></b></legend>
       <center>
            <?php
		echo '<center><table width =100% border="1" id="doctorperformancereportsummarised" class="display">';
		echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
			    <th style='text-align: left;' width=12%>PATIENTS</th>
		     </tr></thead>";
		    //run the query to select all data from the database according to the branch id
		    $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";
		    
		   
		    $select_doctor_result = mysqli_query($conn,$select_doctor_query);
		    
		    $empSN=0;
		    while($select_doctor_row=mysqli_fetch_array($select_doctor_result)){//select doctor
			$employeeID=$select_doctor_row['Employee_ID'];
			$employeeName=$select_doctor_row['Employee_Name'];
			//$employeeNumber=$select_doctor_row['Employee_Number'];
			
                         $patient_no_number=0;
                       $getConsultationResult=mysqli_query($conn,"SELECT ch.consultation_ID FROM tbl_consultation_history ch JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employeeID'") or die(mysqli_error($conn));
                        
                       while ($row = mysqli_fetch_array($getConsultationResult)) {
                           
                       
                          $result_patient=   mysqli_query($conn,"SELECT dc.Disease_Consultation_ID FROM tbl_disease_consultation dc WHERE dc.consultation_ID ='".$row['consultation_ID']."' AND dc.diagnosis_type='diagnosis'") or die(mysqli_error($conn));
                          
                          if(mysqli_num_rows($result_patient) > 0){
                              
                          }  else {
                              $patient_no_number +=1;
                          }
                         
                       
			//$patient_no_number=mysqli_fetch_array($result_patient_no)['numberOfPatients'];
			  
                        
                       }
                    
			    //$numberOFPatients=$patient_no_row['numberOfPatients'];			    
			//}
			
			$empSN ++;
			echo "<tr><td>".($empSN)."</td>";
			echo "<td style='text-align:left'><a href='doctorsDiagnosisStatusDetails.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage'>".$employeeName."</a></td>";
			echo "<td style='text-align:center'>".number_format($patient_no_number)."</td></tr>";
		    }
			    ?>
			    
			    </table>
			</center>
			</center>
            </fieldset>
			<table>
			   <tr>
	   <td style='text-align: center;'>
		<a href="previewFilterNofinalDiagnosis.php?Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>&PreviewFilterPerformanceReportThisPage=ThisPage" target="_blank">
		   
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
<script>
$('#doctorperformancereportsummarised').dataTable({
    "bJQueryUI":true,
	});

</script>