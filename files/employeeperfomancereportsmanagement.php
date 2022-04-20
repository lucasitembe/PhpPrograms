     <?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    $Link_Control = 'False';
    $Title = '';
    $Transaction_Type = '';
    $Date_From = '';
    $Date_To = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ./index.php?InvalidPrivilege=yes");
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
	    header("Location: ./index.php?InvalidPrivilege=yes");
    }
?>

 

<!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date_From").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
        
        $(function () { 
            $("#date_To").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
    
<!--    end of datepicker script-->


<?php
    /* if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
        INPATIENT
    </a>
<?php  } } */ ?>

<?php
    /*if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='../DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        DIRECT CASH
    </a>
<?php  } } */ ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Management_Works'] == 'yes'){ 
?>
    <a href='revenuecollectionreport.php?RevenueCollectionReport=RevenueCollectionReportThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 
<!-- get current date-->
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
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
    
    $select_Employee_Details = mysqli_query($conn,"select Employee_Name, Employee_ID from tbl_employee where employee_id = '$Employee_ID'");
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
	$Title_Control = 'true';
	$Link_Control = 'true';
    }
?>

<?php
    //get employee name
    //if(isset($_SESSION['userinfo']['Employee_Name'])){
    //    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    //}
?>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=90%>
        <tr>
	    <script>
		function deny_employee() {
		    alert("You are not allowed to view other employee transaction records");
		}
	    </script>
	    
	    <?php if(isset($_SESSION['userinfo']['Session_Master_Priveleges'])){
		if(strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes') { ?> 
                    <td width=10%><a href='perfomancelistofemployeemanagement.php?EmployeeList=EmployeeListThisPage' class='art-button-green'>Select Employee</a></td> 
                <?php } else { ?>
		    <td><input type='button' value='Select Employee' class='art-button-green' onclick='deny_employee()'></td> 
		<?php }} ?>
			<td width = '25%'>
			    <input type='text' name='Employee_Name' id='Employee_Name' disabled='disabled' value='<?php echo $Employee_Name; ?>'>
			    <input type='hidden' name='Employee_ID' id='Employee_ID' value='<?php echo $Employee_ID; ?>'>
			</td>
			<td width = '5%' style='text-align: center;'><b>From</b></td>
                        <td width = '10%'>
                            <input type='text' name='Date_From' id='date_From' required='required'>
                        </td> 
                        <td width = '5%' style='text-align: center;'><b>To</b></td>
                        <td width = '10%'>
                            <input type='text' name='Date_To' id='date_To' required='required'> 
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
                        <td style='text-align: center;' width=15%>
                            <input type='button' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
			</td>
			<td>
			    <?php if($Link_Control == 'true'){ ?>
				<a href='employeeperformancepreviewreport.php?Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>&EmployeePerformance=EmployeePerformanceThisForm' class='art-button-green' target='_blank'>PREVIEW</a>
			    <?php } ?>
			    <input type='hidden' name='SubmittedEmployeePerfomanceReportForm' value='true'>
                        </td> 
               
        </tr>
    </table>
    <table width=90%>
        <tr>
            <td colspan=7 style='text-align: center;'>
		<?php
		    if(strtolower($Title_Control) == 'true'){
			echo "<span>
				<b>Employee Performance Report From ".date('d/m/Y',strtotime($Date_From))." To ".date('d/m/Y',strtotime($Date_To)).".
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Employee Name : ".$Employee_Name.". &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Transaction Type: ".$Transaction_Type."</b>
				</span>";
		    }
		?>
	    </td>
        </tr>
        
    </table>
</center>
</form>


<!-- CALCULATE ALL TOTAL-->
<?php
if($Transaction_Type == 'All'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        pp.employee_id = '$Employee_ID' and
                            pp.Receipt_Date between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cash'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        pp.employee_id = '$Employee_ID' and
                            (pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Inpatient Cash') and
                                pp.Receipt_Date between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Credit'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        pp.employee_id = '$Employee_ID' and
                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
                                pp.Receipt_Date between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cancelled'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        pp.employee_id = '$Employee_ID' and
                            pp.Transaction_status = 'cancelled' and
                                pp.Receipt_Date between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }else{
        $select_Filtered_Employees = mysqli_query($conn,
                "select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        pp.employee_id = 0 and
                            pp.Receipt_Date between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }
    //declare all total
    $Cash_Total = 0;
    $Credit_Total = 0;
    $Cancelled_Total = 0;
    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        if(((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash')) and (strtolower($row['Transaction_status']) == 'active')){ 
            $Cash_Total = $Cash_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash')) and (strtolower($row['Transaction_status']) == 'cancelled')){
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit')) and (strtolower($row['Transaction_status']) == 'active')){
            $Credit_Total = $Credit_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit')) and (strtolower($row['Transaction_status']) == 'cancelled')){
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        } 
    }
?>
<!-- END OF CALCULATION-->


 
	
	
	

<fieldset>  
        <center>
            <table width=100% border=1>
                <tr>
				<td>
				 <div id="EmployeePerformanceReportDiv" style="width:100%;height=300px">
				 
				 
				 
				 </div>
				</td>
                </tr>
            </table>
	    <table width = 50%>
	    <tr> 
		<td><b>TOTAL CASH</b></td>
		<td width=16%><b><?php echo number_format($Cash_Total); ?></b></td>
		<td><b>TOTAL CREDIT</b></td>
		<td width=16%><b><?php echo number_format($Credit_Total); ?></b></td>
		<td><b>TOTAL CANCELLED</b></td>
		<td width=16%><b><?php echo number_format($Cancelled_Total); ?></b></td>
	    </tr>
	</table>
        </center>
</fieldset>

<?php
    include("./includes/footer.php");
?>



<script>
 $('#Print_Filter').click(function(e){
 e.preventDefault();
 var fromDate=$('#date_From').val();
 var toDate=$('#date_To').val();
 var Transaction=$('#Transaction_Type').val();
 var Employee_ID=$('#Employee_ID').val();
     $.ajax({
        type:'POST', 
        url:'Employee_Performance_Report_Ifrmae.php',
        data:'action=search&fromDate='+fromDate+'&toDate='+toDate+'&Transaction='+Transaction+'&Employee_ID='+Employee_ID,
        cache:false,
        success:function(html){
            $('#EmployeePerformanceReportDiv').html(html);
        }
     });
 
 
 });


</script>