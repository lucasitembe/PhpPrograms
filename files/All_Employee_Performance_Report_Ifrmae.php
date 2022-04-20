<!----<link rel="stylesheet" href="table.css" media="screen">-->

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
 <script>
  $('#dtTableperformancedetails').dataTable({
    "bJQueryUI":true,
	});
 </script>
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
@session_start();
    include("./includes/connection.php");
    $temp = 1;
    
//    if(isset($_GET['Employee_ID'])){
//     $Employee_ID = $_GET['Employee_ID'];
//    }else{
//     $Employee_ID = 0;
//    }
    if(isset($_GET['Date_From'])){
     $Date_From = $_GET['Date_From'];
    }else{
     $Date_From = '';
    }
    if(isset($_GET['Date_To'])){
     $Date_To = $_GET['Date_To'];
    }else{
     $Date_To = '';
    }
    if(isset($_GET['Transaction_Type'])){
     $Transaction_Type = $_GET['Transaction_Type'];
    }else{
     $Transaction_Type = '';
    }
    
	if(isset($_GET['transaction_channel'])){
     $transaction_channel = $_GET['transaction_channel'];
    }else{
     $transaction_channel = '';
    }
	
	   $Transaction_channel = '';
	   $transaction_channel_filter="";
	   $table_card_mobile ="";
	   $card_mobile_condition="";
       if($transaction_channel != "All"){
       if($transaction_channel=="CRDB"){
           $transaction_channel_filter=" AND pp.manual_offline NOT IN ('Mobile Online')";
		  }else if($transaction_channel=="CRDBmobile"){
		   $transaction_channel_filter =" AND (pp.auth_code LIKE '%BUH%' OR  pp.auth_code LIKE '%FH%')";
          }else if($transaction_channel=="NMB"){
		  //  $table_card_mobile = ",tbl_card_and_mobile_payment_transaction crd ";
			$transaction_channel_filter =" AND (pp.auth_code LIKE '%77100%' OR pp.auth_code LIKE '%EC%')";
           // $transaction_channel_filter="AND crd.payment_direction='to_nmb'";  
       }else{
		   $transaction_channel_filter =" AND pp.manual_offline LIKE 'offline%'";
	   }
   }
    //$Employee_ID =$_POST['Employee_ID'];
    //$Date_From = $_POST['fromDate'];
    //$Date_To = $_POST['toDate'];
    //$Transaction_Type = $_POST['Transaction'];
    
    echo '<center><table width =100% border="1" id="dtTableperformancedetails" class="display" style="background-color:white;">';
?>   <thead><tr style='background-color:#006400; color:white;'>
	    <th style = 'width:5%;'>SN</th>
                    <th style='text-align: left;'>DATE & TIME</th>
                        <th style='text-align: left;'>PATIENT NAME</th>
                            <th width=10% style='text-align: left;'>RECEIPT NO</th>
                            <th width=10% style='text-align: left;'>AUTH NO</th>
                                <th style='text-align: right;' width=10%>CASH</th>
                                    <th style='text-align: right;' width=10%>CREDIT</th>
                                        <th style='text-align: right;' width=10%>CANCELLED</th>
                                            <th style='text-align: right;' width=10%>CANCEL REASON</th></tr></thead>   
<?php
    if($Transaction_Type == 'All'){
        $select_Filtered_Employees = mysqli_query($conn, 
                "select pr.Patient_Name, pp.Payment_Code, pp.Patient_Payment_ID, pp.auth_code, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid, pp.Pre_Paid,pp.Payment_Code,pp.payment_mode,pp.auth_code,pp.manual_offline from 
                    tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                    pr.Registration_ID = pp.Registration_ID and
                    pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cash'){
		
        $select_Filtered_Employees = mysqli_query($conn, 
                "select pr.Patient_Name, pp.Payment_Code, pp.Patient_Payment_ID, pp.auth_code, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid, pp.Pre_Paid,pp.Payment_Code,pp.payment_mode,pp.auth_code,pp.manual_offline
                    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                    pr.Registration_ID = pp.Registration_ID $transaction_channel_filter and 
                    pp.Billing_Type in ('Outpatient Cash','Inpatient Cash') and pp.payment_type = 'pre' and pp.auth_code <> '' AND 
                    pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 

    }elseif($Transaction_Type == 'Credit'){
        $select_Filtered_Employees = mysqli_query($conn, 
                "select pr.Patient_Name, pp.Payment_Code, pp.Patient_Payment_ID, pp.auth_code, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid , pp.Pre_Paid,pp.Payment_Code,pp.payment_mode,pp.auth_code,pp.manual_offline
                    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                    pr.Registration_ID = pp.Registration_ID and
                    (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit' or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'post')) and
                    pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cancelled'){
        $select_Filtered_Employees = mysqli_query($conn, 
                "SELECT pr.Patient_Name, pp.Payment_Code, pr.Patient_Name, pp.Patient_Payment_ID, pp.auth_code, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid , pp.Pre_Paid,pp.Payment_Code,pp.payment_mode,pp.auth_code,pp.manual_offline
                    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                    pr.Registration_ID = pp.Registration_ID and
                    pp.Transaction_status = 'cancelled' and
                    pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }else{
        $select_Filtered_Employees = mysqli_query($conn, 
            "select pr.Patient_Name, pp.Payment_Code, pp.Patient_Payment_ID, pp.auth_code, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid, pp.Pre_Paid,pp.Payment_Code,pp.payment_mode,pp.auth_code,pp.manual_offline 
            from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
            pp.employee_id = 0 and
            pr.Registration_ID = pp.Registration_ID and
            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' $transaction_channel_filter  group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }
    //declare all total
    $Cash_Total = 0;
    $Credit_Total = 0;
    $Cancelled_Total = 0;
    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        $payment_type = $row['payment_type'];
        echo "<tr><td id='thead1'>".$temp."</td>";
        echo "<td>".$row['Payment_Date_And_Time']."</td>";
        echo "<td>".ucwords(strtolower($row['Patient_Name']))."</td>";
        echo "<td style='text-align: center;'><a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$row['Patient_Payment_ID']."&IndividualSummaryReport=IndividualSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$row['Patient_Payment_ID']."</a></td>";
        
        //start 2019/01/16
        $Payment_Code=$row['Payment_Code'];
        $result = mysqli_query($conn, "SELECT `Auth_No` FROM `tbl_bank_api_payments_details` WHERE `Payment_Code`='$Payment_Code'");
        if(mysqli_num_rows($result)>0){
            $rw = mysqli_fetch_array($result);
            echo "<td style='text-align: center;'>".$rw['Auth_No']."</a></td>";
        }else{
            echo "<td style='text-align: center;'>".$row['auth_code']."</a></td>";
        }
        
        //end 2019/01/16

        if(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0') || (strtolower($row['Billing_Type']) == 'patient from outside' && $row['Pre_Paid'] == '0') || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'pre')) && (strtolower($row['Transaction_status']) == 'active') && $row['auth_code'] != ''){
            echo "<td style='text-align: right;'>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Total'], 2) : number_format($row['Total']))."</td>";
            echo "<td style='text-align: right;'>0</td>";
            echo "<td style='text-align: right;'>0</td>";
            echo "<td>".$row['Cancel_transaction_reason']."</td>";
            $Cash_Total = $Cash_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0') || (strtolower($row['Billing_Type']) == 'inpatient cash')) && (strtolower($row['Transaction_status']) == 'cancelled')){
            echo "<td style='text-align: right;'>0</td>";
            echo "<td style='text-align: right;'>0</td>";
            echo "<td style='text-align: right;'>".number_format($row['Total'])."</td>";
            echo  "<td>".$row['Cancel_transaction_reason']."</td>";
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '1') || (strtolower($row['Billing_Type']) == 'outpatient credit') || (strtolower($row['Billing_Type']) == 'inpatient credit') || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'post')) && (strtolower($row['Transaction_status']) == 'active')){
            echo "<td style='text-align: right;'>0</td>";
            echo "<td style='text-align: right;'>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Total'], 2) : number_format($row['Total']))."</td>"; 
            echo "<td style='text-align: right;'>0</td>";
            echo  "<td>".$row['Cancel_transaction_reason']."</td>";
            $Credit_Total = $Credit_Total + $row['Total'];
        }elseif(((strtolower(($row['Billing_Type']) == 'outpatient cash' || ($row['Billing_Type']) == 'inpatient cash') && $row['Pre_Paid'] == '1') || (strtolower($row['Billing_Type']) == 'outpatient credit') || (strtolower($row['Billing_Type']) == 'inpatient credit')) && (strtolower($row['Transaction_status']) == 'cancelled')){
            echo "<td style='text-align: right;'>0</td>";
            echo "<td style='text-align: right;'>0</td>";
            echo "<td style='text-align: right;'>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Total'], 2) : number_format($row['Total']))."</td>";
            echo  "<td>".$row['Cancel_transaction_reason']."</td>";
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        }
         echo "</tr>";
     //   echo "<tr><td colspan=7><hr></td></tr>";
        $temp++;        
    }
    echo "<tr><td colspan=9><hr></td></tr>";
    echo "<tr><td colspan=5 style='text-align: right;'><b>Sub Total</b></td>";
    echo "<td style='text-align: right;'><b>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Cash_Total, 2) : number_format($Cash_Total))."</b></td>";
    echo "<td style='text-align: right;'><b>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Credit_Total, 2) : number_format($Credit_Total))."</b></td>";
    echo "<td style='text-align: right;'><b>".(($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Cancelled_Total, 2) : number_format($Cancelled_Total))."</b></td></tr>";
    echo "<tr><td colspan=9><hr></td></tr>";
?></table></center>

