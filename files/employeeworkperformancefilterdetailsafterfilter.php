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
    <a href='./employeeworkperformancesummary.php?EmployeeWork=EmployeeWorkThisPage' class='art-button-green'>
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
    $Date_From=$_POST['Date_From'];
    $Date_To=$_POST['Date_To'];
    $doctorID=$_POST['Employee_ID'];
    $branchID=$_POST['branchID'];
?>

<fieldset>
    <br>
    <center>
	<form action='employeeworkperformancefilterdetailsafterfilter.php?EmployeeWorkPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=60% align="center";>
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
            <td style="text-align: center">To</td>
	    <td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required' value="<?php echo date('Y-m-d H:i:s');?>"></td>    
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
		    <input type='hidden' name='Employee_ID' value='<?php echo $doctorID?>'/>
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
    <br>
    <?php
        $doctorID=$_POST['Employee_ID'];
        $select_doctor=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='$doctorID'");
        $row=mysqli_fetch_array($select_doctor);
        $doctorName=$row['Employee_Name'];
    ?>
    <legend align=center><b>PERFORMANCE DETAILS FOR  </b><b style="color:blue;"><?php echo strtoupper($doctorName)?></b> <b>FROM </b><b style="color: blue;"><?php echo date('j F, Y',strtotime($Date_From))?></b><b> TO </b><b style="color: blue;"><?php echo date('j F, Y',strtotime($Date_To))?></b></legend>
        <iframe src="employeeworkperformancefilterdetailsafterfilter_Iframe.php?branchID=<?php echo $branchID?>&Employee_ID=<?php echo $doctorID?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>" width="100%" height="340px"></iframe>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>