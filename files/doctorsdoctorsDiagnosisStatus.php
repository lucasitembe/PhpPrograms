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
    
        $Date_From='';//@$_POST['Date_From'];
        $Date_To='';//@$_POST['Date_To'];
        
        if(!isset($_GET['Date_From'])){
             $Date_From= date('Y-m-d H:m');
        }else{
            $Date_From=$_GET['Date_From']; 
        }
        if(!isset($_GET['Date_To'])){
             $Date_To=date('Y-m-d H:m');;
        }else{
            $Date_To=$_GET['Date_To'];
        }
?>
<a href='./dhisworkpage.php?DhisWork=DhisWorkThisPage.php' class='art-button-green'>
        BACK
    </a>
<br/><br/>

<fieldset style='overflow-y:scroll; height:500px'>
    <center>
	
	<legend  align="right" style="background-color:#006400;color:white;padding:5px;"><form action='doctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>
	
    <!--<form action='doctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">-->
	<br/>
    <table width='75%'>
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
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>DOCTOR'S PERFORMANCE REPORT SUMMARY &nbsp;&nbsp;From&nbsp;&nbsp;</b><b style="color:#e0d8d8;"><?php echo date('j F, Y H:i:s',strtotime($Date_From))?> </b><b>TO</b> <b style="color: #e0d8d8;"><?php echo date('j F, Y H:i:s' ,strtotime($Date_To))?></b></legend>
        <center>
            <?php
		echo '<center><table width =100% border="1" class="display" id="doctorsperformancetbl">';
		echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
			    <th style='text-align: left;' width=18%>NUMBER OF PATIENTS</th>
		     </tr></thead>";
		    //run the query to select all data from the database according to the branch id
		    $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";
		    
		   
		    $select_doctor_result = mysqli_query($conn,$select_doctor_query);
		    
		    $empSN=0;
		    while($select_doctor_row=mysqli_fetch_array($select_doctor_result)){//select doctor
			$employeeID=$select_doctor_row['Employee_ID'];
			$employeeName=$select_doctor_row['Employee_Name'];
			//$employeeNumber=$select_doctor_row['Employee_Number'];
			
			$result_patient_no= mysqli_query($conn,"SELECT COUNT(c.Registration_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employeeID'") or die(mysqli_error($conn));
                         
                        //echo "SELECT COUNT(c.Registration_ID) AS numberOfPatients ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employeeID'<br/>";
//			$select_patient_item_list=mysqli_query($conn,"SELECT COUNT(Registration_ID) AS numberOfPatients FROM tbl_consultation co,tbl_patient_payment_item_list ppl
//			  WHERE co.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID AND
//			  co.employee_ID=ppl.Consultant_ID AND
//			  ppl.Consultant_ID='$employeeID' AND ppl.Process_Status='signedoff'
//			  AND ppl.Signedoff_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
//			  ");
			$patient_no_number=mysqli_fetch_assoc($result_patient_no)['numberOfPatients'];
			    //$numberOFPatients=$patient_no_row['numberOfPatients'];			    
			//}
			
			$empSN ++;
			echo "<tr><td>".($empSN)."</td>";
			echo "<td style='text-align:left'><a href='doctorsperformancefilterdetails.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage'>".$employeeName."</b></td>";
			echo "<td style='text-align:center'><a href='doctorsperformancefilterdetails.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage'>".number_format($patient_no_number)."</b></td></tr>";
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
 $('#doctorsperformancetbl').dataTable({
    "bJQueryUI":true,
	});
</script>