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
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href='./employeeworkperformancesummary.php?FinanceWork=FinanceWorkThisPage' class='art-button-green'>
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
    $doctorID=$_GET['Employee_ID'];
?>
<fieldset>
    <center>
	<form action='employeeworkperformancedetailsfilter.php?EmployeeWorkPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=60% align="center";>
        <tr>
            <td style="text-align: center"><b>From</b></td>
	    <td style="text-align: center">
		<input type='text' name='Date_From' id='date_From' required='required'>    
	    </td>
            <td style="text-align: center">To</td>
	    <td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required'></td>    
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
                    <input type="hidden" name="Employee_ID" value="<?php echo $doctorID?>"/>
            </td>
        </tr>	
    </table>
    </form> 
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
    </center>
    <?php
        $doctorID=$_GET['Employee_ID'];
        $select_employee=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='$doctorID'");
        $row=mysqli_fetch_array($select_employee);
        $employeeName=$row['Employee_Name'];
    ?>
    <br>
    <legend align=center><b>WORK PERFORMANCE DETAILS FOR </b><b style="color:blue;"><?php echo strtoupper($employeeName)?></b></legend>
        <iframe src="employeeperformancedetails_Iframe.php?Employee_ID=<?php echo $doctorID;?>" width="100%" height="340px"></iframe>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>