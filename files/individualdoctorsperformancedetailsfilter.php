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
    <a href='./individualdoctorsperformancesummary.php?FinanceWork=FinanceWorkThisPage' class='art-button-green'>
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
    $doctorID=$_POST['Employee_ID'];
    $Date_From=$_POST['Date_From'];
    $Date_To=$_POST['Date_To'];
?>

<fieldset>
    <center>
	<form action='individualdoctorsperformancedetailsfilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
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
        $doctorID=$_POST['Employee_ID'];
        $Date_From=$_POST['Date_From'];
        $Date_To=$_POST['Date_To'];
        $select_doctor=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='$doctorID' AND Employee_Type='Doctor'");
        $row=mysqli_fetch_array($select_doctor);
        $doctorName=$row['Employee_Name'];
    ?>
    <br>
    <legend align=center><b>PERFORMANCE DETAILS FOR DOCTOR </b><b style="color:blue;"><?php echo strtoupper($doctorName)?></b><b> FROM </b><b style='color: blue;'><?php echo date('j F. Y',strtotime($Date_From))?></b><b> TO </b><b style='color: blue;'><?php echo date('j F, Y',strtotime($Date_To))?></b></legend>
        <iframe src="individualdoctorsperformancedetailsfilter_Iframe.php?Employee_ID=<?php echo $doctorID?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>" width="100%" height="340px"></iframe>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>