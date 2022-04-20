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
    <a href='./individualdoctorsPerformanceSummaryFilter.php?Employee_ID=<?php echo $_GET['Employee_ID']?>&Date_From=<?php echo $_GET['Date_From'];?>&Date_To=<?php echo $_GET['Date_To'];?>&DoctorsWork=WorkThisPage' class='art-button-green'>
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
    $Date_From=$_GET['Date_From'];
    $Date_To=$_GET['Date_To'];
    $doctorID=$_GET['Employee_ID'];
?>
<br>

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
 
<fieldset >
    <center>
	<br>
	<form action='individualdoctorsperformancefilterdetailsfilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
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
        $doctorID=$_GET['Employee_ID'];
        $select_doctor=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='$doctorID' AND Employee_Type='Doctor'");
        $row=mysqli_fetch_array($select_doctor);
        $doctorName=$row['Employee_Name'];
    ?>
    <legend style="background-color:#006400;color:white;padding:5px;" align=center><b>PERFORMANCE DETAILS FOR DOCTOR </b><b style="color:blue;"><?php echo strtoupper($doctorName)?></b> <b>FROM </b><b style="color: blue;"><?php echo date('j F, Y H:i:s',strtotime($Date_From))?></b><b> TO </b><b style="color: blue;"><?php echo date('j F, Y H:i:s',strtotime($Date_To))?></b></legend>
        <iframe src="individualdoctorsperformancefilterdetails_Iframe.php?Employee_ID=<?php echo $doctorID?>&Date_From=<?php echo $Date_From?>&Date_To=<?php echo $Date_To?>" width="100%" height="270px"></iframe>
<table align='center' width='60%'>
    <tr>
	<td style='text-align:center;font-size: medium;font-weight: bold'>Total Collection</td>
	<td style='text-align:center;font-size: medium;font-weight: bold'>50% Of Revenue</td>
	<!--<td style='text-align:center;font-size: medium;font-weight: bold'>40% Of Revenue</td>
	<td style='text-align:center;font-size: medium;font-weight: bold'>Remainder</td>-->
    </tr>
    <?php
	$employeeID=mysqli_real_escape_string($conn,$_GET['Employee_ID']);
			    $Date_From=$_GET['Date_From'];
			    $Date_To=$_GET['Date_To'];
                            //run the query to select all data from the database according to the branch id
                            $select_doctor_query=mysqli_query($conn,"SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' AND emp.Employee_ID='$employeeID'");
                            
                            $TotalRevenue=0;
			    
			    
			    $Receipt_Date=date('Y-m-d');
			    while($select_doctor_query_row=mysqli_fetch_array($select_doctor_query)){//return employee details
                                //return data
                                $employeeID=$select_doctor_query_row['Employee_ID'];
                                $Employee_Name=$select_doctor_query_row['Employee_Name'];
                                
                                $select_patient_item_list=mysqli_query($conn," SELECT SUM((ppl.Price - ppl.Discount) * Quantity) AS TotalRevenue FROM tbl_consultation co,tbl_patient_payment_item_list ppl,tbl_employee e,tbl_patient_payments pp 
									    WHERE ppl.Patient_Payment_Item_List_ID =co.Patient_Payment_Item_List_ID
									    AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID
									    AND ppl.Consultant_ID=e.Employee_ID
									    AND e.Employee_ID=co.Employee_ID
									    AND co.Employee_ID='$employeeID'
									    AND ppl.Process_Status='signedoff' 
									    AND ppl.Patient_Direction = 'Direct To Doctor'
									    AND pp.Receipt_Date <= '$Receipt_Date'
									    AND ppl.Signedoff_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
								      ");
				
				$TotalRevenue=mysqli_fetch_array($select_patient_item_list)['TotalRevenue'];
                            }
			    //perform the calculations
			    //1. (12% Of the collection goes to the doctors) 
			    $Twelve_Percent=(12/100)*$TotalRevenue;
			    
			    //2. (40% Percent of the collection goes to hospital)
			    $Fourty_Percent=(40/100)*$TotalRevenue;
			    
			    //Some of 12% and 40%
			    $Sum_Twelve_Fourty = $Twelve_Percent + $Fourty_Percent;
			    
			    $Remainder=$TotalRevenue - $Sum_Twelve_Fourty;
			    
			    $Fifty_Percent=(50/100) * $TotalRevenue;
			    
		//display
		echo "<tr>
		    <td style='text-align:center;font-size: small;font-weight: bold'>".number_format($TotalRevenue)."</td>
		    <td style='text-align:center;font-size: small;font-weight: bold'>".number_format($Fifty_Percent)."</td>
		</tr>";
		
		//print result
		echo "<tr>
		    <td style='text-align:left;font-size: small;font-weight: bold'>
			<a href='PrintIndividualDoctorPerformanceFilterDetails.php?Employee_ID=$doctorID&Date_From=$Date_From&Date_To=$Date_To&PrintIndividualDoctorPerformanceFilterDetailsThisPage=ThisPage' class='art-button-green' target='_blanck'>Print</a>
		    </td>
		</tr>";
			    
    
    
    ?>
</table>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>