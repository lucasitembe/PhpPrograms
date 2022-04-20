<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

    
<?php
	//$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
//	$Employee_Type=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Type'];
	//if($Employee_Type == 'Others'){ ?>
		<a href='./managementworkspage.php?Reception=ReceptionThisPage' class='art-button-green'>
			BACK
		</a>
<?php// } ?>
    
    
    
</script>
<br/><br/><br>

<fieldset>
    <center>
	<br>
    <form action='employeeWorkPerformanceSummaryFilter.php?EmployeeWorkPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width='60%'>
        <tr>
	    <td style="text-align: center"><b>Branch</b></td>
	    <td style="text-align: center">
		<select name="branchID" id="branchID">
		    <option value="All">All</option>
		    <?php
			$select_branch=mysqli_query($conn,"SELECT * FROM tbl_branches");
			while($select_branch_row=mysqli_fetch_array($select_branch)){
			    $branchID=$select_branch_row['Branch_ID'];
			    $branchName=$select_branch_row['Branch_Name'];
			    echo "<option value='$branchID'>$branchName</option>";
			}
		    ?>
		</select>  
	    </td>
            <td style="text-align: center"><b>From</b></td>
	    <td style="text-align: center">
		<input type='text' name='Date_From' id='date_From' required='required' value="<?php echo date('Y-m-d H:i:s');?>">    
	    </td>
            <td style="text-align: center"><b>To</b></td>
	    <td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required' value="<?php echo date('Y-m-d H:i:s');?>"></td>    
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
        </tr>	
    </table>
    </form>
    
</center>
    <br>
    <legend style="background-color:#006400;color:white;padding:5px;" align=center><b>EMPLOYEE'S PERFORMANCE REPORT SUMMARY</b></legend>
        <center>
            <?php
		echo '<center><table width =100% border="1" id="reportSummarytbl" class="display">';
		//echo "<tr><td colspan=5><hr></td></tr>";
		echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>EMPLOYEE NAME</th>
			    <th style='text-align: right;' width=30%>NUMBER OF PATIENTS</th>
		     </tr></thead>";
		  //  echo "<tr>
				//<td colspan=5><hr></td></tr>";
		    //run the query to select all data from the database according to the branch id
		    $select_employee_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp ORDER BY Employee_Name ASC";
		    
		   
		    $select_employee_result = mysqli_query($conn,$select_employee_query);
		    
		    $empSN=0;
		    while($select_employee_row=mysqli_fetch_array($select_employee_result)){//select patient
			$employeeID=$select_employee_row['Employee_ID'];
			$employeeName=$select_employee_row['Employee_Name'];
			//$employeeNumber=$select_doctor_row['Employee_Number'];
			
			$select_patient_list=mysqli_query($conn,"SELECT COUNT(Registration_ID) AS numberOfPatients FROM tbl_check_in ci,tbl_employee emp
							 WHERE ci.Employee_ID=emp.Employee_ID
							 AND ci.Employee_ID = '$employeeID'
							 AND ci.Check_In_Date_And_Time = CURDATE()");
			while($select_patient_list_row=mysqli_fetch_array($select_patient_list)){
			    $numberOFPatients=$select_patient_list_row['numberOfPatients'];			    
			}
			
			if($numberOFPatients != 0){
			 $empSN ++;
			echo "<tr><td>".($empSN)."</td>";
			echo "<td style='text-align:left'><a href='employeeworkperformancedetails.php?Employee_ID=$employeeID&EmployeePerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage'>".$employeeName."</a></td>";
			echo "<td style='text-align:right'>".number_format($numberOFPatients)."</td></tr>";   
			}
		    }
			    ?>    
			</table>
			<table>
			</table>
			</center>
			</center>
</fieldset>
<!--<table>
			    <tr>
				<td style='text-align: center;'>
				    <a href="previewDoctorPerformance.php?PreviewPerformanceReportThisPage=ThisPage" target="_blank">
					<button>
					    <input type='submit' name='previewDoctorPerformance' id='previewDoctorPerformance' target='_blank' class='art-button-green' value='PREVIEW ALL'>
					</button>
				    </a>
				</td>
			    </tr>
			</table>-->
<?php
    include("./includes/footer.php");
?>
   <!--- <script src="css/jquery.js"></script>-->
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
	 $("#reportSummarytbl").dataTable({
       "bJQueryUI":true,
	  });
	</script>
	<!--End datetimepicker-->