<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    $Link_Control = 'False';
    $Title = '';
    $Transaction_Type = '';
    $Date_From = '';    
    $Date_To = '';
	$Transaction_channel = '';
	$transaction_channel_filter="";
	$table_card_mobile ="";
	$card_mobile_condition="";
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ./index.php?InvalidPrivilege=yes");      
    }
?>


<?php
    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['Section'])){
	    $Section = $_GET['Section'];
        if(strtolower($Section) == 'pharmacy'){
            echo "<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>";
        }else{
?>
		<a href='performancereports.php?Section=<?php echo $Section; ?>&PerformanceReport=PerformanceReportThisPage' class='art-button-green'>BACK</a>
<?php
        }
	}else{
	    $Section = '';
?>
	    <a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>BACK</a>
<?php
	}
    }
?>




<!-- get current date-->
<?php
    $Today_Date = mysqli_query($conn, "select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
	$original_Date = $row['today'];
	$new_Date = date("Y-m-d", strtotime($original_Date));
	$Today = $new_Date; 
    }
?>

<!-- get employee details-->
<?php
    if(isset($_GET['Employee_ID'])){
	$Employee_ID = $_GET['Employee_ID']; 
    }else{
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
    
    $select_Employee_Details = mysqli_query($conn, "select Employee_Name, Employee_ID from tbl_employee where employee_id = '$Employee_ID'");
    while($row = mysqli_fetch_array($select_Employee_Details)){
	$Employee_Name = $row['Employee_Name'];
	$Employee_ID = $row['Employee_ID'];
    }
?>


<br/><br/>
<center>
    
<?php 
    if(isset($_POST['SubmittedEmployeePerfomanceReportForm'])){
        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];
	$Employee_ID = $_POST['Employee_ID'];
	$Transaction_Type = $_POST['Transaction_Type'];
	$transaction_channel = $_POST['Transaction_channel'];
	$Title_Control = 'true';
	$Link_Control = 'true';
	
	// $transaction_channel_filter="";   
   //if($_POST['transaction_channel']){
   
        if($transaction_channel != "All"){
            if($transaction_channel=="CRDB"){
                $transaction_channel_filter=" AND pp.auth_code NOT LIKE '77100%' AND pp.auth_code NOT LIKE 'FH%' AND pp.auth_code NOT LIKE 'BUH100%' AND pp.auth_code NOT LIKE 'EC%'";
            }else if($transaction_channel=="CRDBmobile"){
                $transaction_channel_filter ="AND (pp.auth_code LIKE 'BUH100%' OR pp.auth_code LIKE 'FH%')";
            }else if($transaction_channel=="NMB"){
                //  $table_card_mobile = ",tbl_card_and_mobile_payment_transaction crd ";
                    $transaction_channel_filter ="AND (pp.auth_code LIKE '77100%' OR pp.auth_code LIKE 'EC%')";
                // $transaction_channel_filter="AND crd.payment_direction='to_nmb'";  
            }else{
                $transaction_channel_filter ="and pp.manual_offline LIKE 'offline%'";
            }
        }else{
            $transaction_channel_filter = '';   
        }
    }
?>

<?php
    //get employee name
    //if(isset($_SESSION['userinfo']['Employee_Name'])){
    //    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    //}
?>
<fieldset style="background-color:white;"> 
<legend align="center" style="background-color:#006400;color:white;padding:5px;">

		<?php
		    if(strtolower($Title_Control) == 'true'){
			echo "<span>
				<b>Employee Performance Report From ".date('d/m/Y H:i:s',strtotime($Date_From))." To ".date('d/m/Y H:i:s',strtotime($Date_To)).".
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Employee  Name : ".$Employee_Name.". &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Transaction Type: ".$Transaction_Type."</b>
				</span>";
		    }else{
                echo "<b>Employee Performance Report</b>";
            }
		?>
	    
	</legend>
	<br>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=100%>
        <tr>
	    <?php if(isset($_SESSION['userinfo']['Employee_Collection_Report'])){
		if(strtolower($_SESSION['userinfo']['Employee_Collection_Report']) == 'yes') { ?> 
                    <td width=10%>
			<?php if(isset($_GET['Section'])) { $Section = $_GET['Section']; }else{ $Section = ''; } ?>
			<a href='perfomancelistofemployee.php?Section=<?php echo $Section; ?>&EmployeeList=EmployeeListThisPage' class='art-button-green'>Select Employee</a>
		    </td> 
                <?php } else { ?>
		    <td><button class='art-button-green' disabled='disabled'>Select Employee</button></td> 
		<?php }} ?>
			<td width = '25%'>
			    <input type='text' name='Employee_Name' id='Employee_Name' disabled='disabled' value='<?php echo $Employee_Name; ?>'>
			    <input type='hidden' name='Employee_ID' id='Employee_ID' value='<?php echo $Employee_ID; ?>'>
			</td>

            <td style='text-align: right;' width=10%><b>From</b></td>
            <td style="text-align: center; border: 1px #ccc solid;width: 15%;">
                <input  style="text-align: center;" type='text' autocomplete='off' name='Date_From' id='date_From' style="background-color:#eeeeee;" required='required'>
            </td>
            <td style='text-align: right;' width=10%><b>To</b></td>
            <td style="text-align: center; border: 1px #ccc solid;width: 15%">
                <input  style="text-align: center;" type='text' name='Date_To' autocomplete='off' id='date_To' style="background-color:#eeeeee;" required='required'>
            </td>





            <td width = '10%' style='text-align: center;'><b>Transaction Type</b></td>
            <td width = '6%'>
                            <select name='Transaction_Type' id='Transaction_Type'>
				<option selected='selected'>All</option>
				<option>Cash</option>
				<option>Credit</option>
				<option>Cancelled</option>
			    </select>
                        </td> 
     <td width = '10%' style='text-align: center;'><b>Transaction Channel</b></td>
            <td width = '6%'>
                            <select name='Transaction_channel' id='Transaction_channel'>
				    <option selected='selected'>All</option>
					<!--<option value="all_transaction_channel">All Channel</option>-->
                                    <option>CRDB</option>
                                   <option>NMB</option>
								   <option>Offline</option>
								   <option>CRDBmobile</option>
			    </select>
                        </td>						
                        <td style='text-align: center;' width=15%>
                            <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
			</td>
			<td>
			    <?php if($Link_Control == 'true'){ ?>
				<a href='employeeperformancepreviewreport.php?Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&transaction_channel=<?php echo $transaction_channel; ?>&EmployeePerformance=EmployeePerformanceThisForm' class='art-button-green' target='_blank'>PDF PREVIEW</a>
			    <?php } ?>
			    <input type='hidden' name='SubmittedEmployeePerfomanceReportForm' value='true'>
                        </td> 
               
        </tr>
    </table>
    
</center>


<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#date_From').datetimepicker({value:'',step:01});
    $('#date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:01});
    </script>
    <!--End datetimepicker-->
   

</form>
<div style="margin-bottom: 5px;"></div>


<!-- CALCULATE ALL TOTAL-->
<?php
if($Transaction_Type == 'All'){
        $select_Filtered_Employees = mysqli_query($conn, 
                "select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        pp.employee_id = '$Employee_ID' and
                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' $transaction_channel_filter group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cash'){
        $select_Filtered_Employees = mysqli_query($conn, 
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = '$Employee_ID' and
                pr.Registration_ID = pp.Registration_ID and
                pp.Billing_Type  in ('Outpatient Cash','patient from outside','Inpatient Cash') and pp.Pre_Paid = '0'  and pp.payment_type = 'pre' $transaction_channel_filter and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Credit'){
        $select_Filtered_Employees = mysqli_query($conn, 
                "select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        pp.employee_id = '$Employee_ID' and
                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cancelled'){
        $select_Filtered_Employees = mysqli_query($conn, 
                "select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        pp.employee_id = '$Employee_ID' and
                            pp.Transaction_status = 'cancelled' and
                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }else{
        $select_Filtered_Employees = mysqli_query($conn, 
                "select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and (pp.Billing_Type <> 'Outpatient Cash' and pp.Pre_Paid <> '1') AND
                        pp.employee_id = 0 and
                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }
    //declare all total
    $Cash_Total = 0;
    $Credit_Total = 0;
    $Cancelled_Total = 0;
    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        $payment_type = $row['payment_type'];
        
        if(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0') || (strtolower($row['Billing_Type']) == 'patient from outside' && $row['Pre_Paid'] == '0') || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'pre')) && (strtolower($row['Transaction_status']) == 'active')){
            $Cash_Total = $Cash_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0')  || (strtolower($row['Billing_Type']) == 'inpatient cash')) && (strtolower($row['Transaction_status']) == 'cancelled')){
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '1') || (strtolower($row['Billing_Type']) == 'outpatient credit') || (strtolower($row['Billing_Type']) == 'inpatient credit') || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'post')) && (strtolower($row['Transaction_status']) == 'active')){
            $Credit_Total = $Credit_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '1') || (strtolower($row['Billing_Type']) == 'outpatient credit') || (strtolower($row['Billing_Type']) == 'inpatient credit')) && (strtolower($row['Transaction_status']) == 'cancelled')){
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        } 
    }
?>
<!-- END OF CALCULATION-->


        <center>
            <table width=100% border=0 style="overflow-y:scroll; height:330px; background-color:white; margin-bottom: 5px;">
                <tr>
                    <td>
                        <iframe width='100%' height=330px src="Employee_Performance_Report_Ifrmae.php?Section=<?php echo $Section; ?>&Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&transaction_channel=<?php echo $transaction_channel; ?>"></iframe>
                    </td>
                </tr>
            </table>
	    <table width = 90% style="height:40px; background-color:white;">
	    <tr> 
		<td><b>TOTAL CASH</b></td>
		<td width=13%><b><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Cash_Total, 2) : number_format($Cash_Total)); ?></b></td>
		<td><b>TOTAL CREDIT</b></td>
		<td width=13%><b><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Credit_Total, 2) : number_format($Credit_Total)); ?></b></td>
		<td><b>TOTAL CANCELLED</b></td>
		<td width=13%><b><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Cancelled_Total, 2) : number_format($Cancelled_Total)); ?></b></td>
		<td><b>GRAND TOTAL</b></td>
		<td width=13%><b><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Cash_Total + $Credit_Total, 2) : number_format($Cash_Total + $Credit_Total)).'  '.$_SESSION['hospcurrency']['currency_code']; ?></b></td>
	    </tr>
	</table>
            <br /><br />
            <table>
                <tr>
                   <?php if($Link_Control == 'true'){ ?>
                             <a href='employeeperformancepreviewreportSummary.php?Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&transaction_channel=<?php echo $transaction_channel; ?>&EmployeePerformance=EmployeePerformanceThisForm' class='art-button-green' target='_blank'>PRINT SUMMARY</a>
							<a href='IndividualEmployeePerformanceExcelReport.php?Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&transaction_channel=<?php echo $transaction_channel; ?>&EmployeePerformance=EmployeePerformanceThisForm' class='art-button-green' target='_blank'>EXCEL PREVIEW</a>
		 <?php } ?>
		  <input type='hidden' name='SubmittedEmployeePerfomanceReportForm' value='true'>
                </tr>
            </table>
        </center>
</fieldset>

<script>
    $(document).ready(function() {
        $("#Transaction_Type").select2();
        $("#Transaction_channel").select2();
    });
</script>

<?php
    include("./includes/footer.php");
?>