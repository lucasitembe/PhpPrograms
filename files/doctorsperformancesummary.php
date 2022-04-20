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
<a href='./managementworkspage.php?ManagementWorksPage=ManagementWorksPageThisPage' class='art-button-green'>
        BACK
    </a>
<br/><br/>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style> 
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
            <td style="text-align: center">
                <select name='Sponsor_ID' id='Sponsor_ID'class="form-control" onchange="filterPatient()" style='text-align: center;width:100%;display:inline'>
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
<!--             <td style="text-align: center">
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
			    <th style='text-align: left;' width=12%>TOTAL PATIENTS</th>
                            <th style='text-align: left;' width=12%>LAB SENT</th>
                            <th style='text-align: left;' width=12%>RAD SENT</th>
                            <th style='text-align: left;' width=12%>PHAR SENT</th>
                            <th style='text-align: left;' width=12%>PROC SENT</th>
                            <th style='text-align: left;' width=12%>SURG SENT</th>
		     </tr></thead>";
		   
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
	$('#date_From').datetimepicker({value:'',step:1});
	$('#date_To').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:'now'
	});
	$('#date_To').datetimepicker({value:'',step:1});
	</script>

<script>
 $('#doctorsperformancetbl').dataTable({
    "bJQueryUI":true,
	});
</script>