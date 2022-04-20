<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
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
        if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
?>
    <a href='./myclinicperformance.php?DoctorsPerformanceReportThisPage' class='art-button-green'>
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

<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		
		}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
</style>

<?php
    if(isset($_POST['Date_From'])){
	$Date_From=mysqli_real_escape_string($conn,$_POST['Date_From']);
	$Date_To=mysqli_real_escape_string($conn,$_POST['Date_To']);
    }
    if(isset($_GET['Date_From'])){
	$Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
	$Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
    }
    else{
	$Date_From='';
	$Date_To='';
    }
?>



<fieldset style='height: 500px'>
    <center>
    <form action='myclinicperformanceFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=60%>
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
</center>
    <script src="css/jquery.js"></script>
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
	<!--End datetimepicker-->
    <?php
	$Date_From='';
	$Date_To='';
	if(isset($_POST['Date_From'])){
	     $Date_From=mysqli_real_escape_string($conn,$_POST['Date_From']);
	     $Date_To=mysqli_real_escape_string($conn,$_POST['Date_To']);
	}
	elseif(isset($_GET['Date_From'])){
	     $Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
	     $Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
	}
	else{
	    $Date_From='';
	    $Date_To='';
	}
    ?>
    <br>
    <legend align=center><b>DOCTOR'S CLINIC PERFORMANCE REPORT SUMMARY FROM </b><b style="color: blue;"><?php echo date('j F, Y H:i:s',strtotime($Date_From))?> </b><b>TO</b> <b style="color: blue;"><?php echo date('j F, Y H:i:s' ,strtotime($Date_To))?></b></legend>
        <center>
            <?php
		 echo "<tr><td><hr></td></tr>";	
		echo '<center><table width =70% border=0>';
		echo "<tr>
			    <td width=3% style='text-align:left'><b>SN</b></td>
			    <td style='text-align:left'><b>DOCTOR'S NAME</b></td>
			    <td style='text-align: right;' width=30%><b>NUMBER OF PATIENTS</b></td>
		     </tr>";
			 echo "<tr><td colspan='9'><hr></td></tr>";	
		     echo "<tr>
				<td colspan=4></td></tr>";
                                $Employee_ID=$_SESSION['userinfo']['Employee_ID'];//get emp id
		    //run the query to select all data from the database according to the branch id
		    $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp
                                            WHERE Employee_Type='Doctor'
                                            AND emp.Employee_ID='$Employee_ID' ORDER BY Employee_Name ASC";
		    
		   
		    $select_doctor_result = mysqli_query($conn,$select_doctor_query);
		    
		    $empSN=0;
		    while($select_doctor_row=mysqli_fetch_array($select_doctor_result)){//select doctor
			$employeeID=$select_doctor_row['Employee_ID'];
			$employeeName=$select_doctor_row['Employee_Name'];
			//$employeeNumber=$select_doctor_row['Employee_Number'];
			
			$select_patient_item_list=mysqli_query($conn,"
                                                       SELECT COUNT(Registration_ID) AS numberOfPatients FROM tbl_consultation co,tbl_clinic c,tbl_clinic_employee ce,tbl_patient_payment_item_list ppl 
                                                        WHERE ppl.Patient_Payment_Item_List_ID =co.Patient_Payment_Item_List_ID
                                                        AND ppl.Consultant_ID=ce.Clinic_ID
                                                        AND ce.Employee_ID=co.Employee_ID
                                                        AND co.Employee_ID='$Employee_ID'
                                                        AND ce.Clinic_ID=c.Clinic_ID
                                                        AND ppl.Process_Status='signedoff' 
                                                        AND ppl.Patient_Direction='Direct To Clinic' 
                                                        AND ppl.Signedoff_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' 
                                                ");
			while($select_patient_item_list_row=mysqli_fetch_array($select_patient_item_list)){
			    $numberOFPatients=$select_patient_item_list_row['numberOfPatients'];			    
			}
			
			$empSN ++;
			echo "<tr><td>".($empSN)."</td>";
			echo "<td style='text-align:left'><a href='myclinicperformanceFilterDetail.php?Employee_ID=$employeeID&Date_From=$Date_From&Date_To=$Date_To&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage'>".$employeeName."</a></td>";
			echo "<td style='text-align:right'>".number_format($numberOFPatients)."</td></tr>";
		    }
			    ?>
			    
			    </table>
			</center>
			</center>
</fieldset>
			<?php
			    if(mysqli_num_rows($select_patient_item_list) > 0){ ?>
				<table>
			   <tr>
			    <td style='text-align: center;'>
				<a href="individualpreviewFilterClinicPerformance.php?Employee_ID=<?php echo $Employee_ID?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>&PreviewFilterPerformanceReportThisPage=ThisPage" target="_blank">
				   <input type='submit' name='previewFilterClinicPerformance' id='previewFilterDoctorPerformance' target='_blank' class='art-button-green' value='PREVIEW ALL'>
				</a>
			    </td>
			    
			   </tr>
			</table>
			    <?php }
			?>
<?php
    include("./includes/footer.php");
?>