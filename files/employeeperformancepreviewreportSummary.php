<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    $temp2 = 0;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     $Employee = $_SESSION['userinfo']['Employee_Name'];
    /*if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }*/
    
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
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
        $Transaction_Type = 'all'; 
    }
    
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }else{
        $Patient_Payment_ID = 0;
    }
    $total = 0;
    
    //select printing date and time
    $select_Time_and_date = mysqli_query($conn,"select now() as datetime");
    while($row = mysqli_fetch_array($select_Time_and_date)){
	$Date_Time = $row['datetime'];
    } 
   
    //get employee details
    $select_details = mysqli_query($conn,"select * from tbl_employee where employee_id = '$Employee_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_details);
    if($num > 0){
	while($row2 = mysqli_fetch_array($select_details)){
	    $Employee_Name = $row2['Employee_Name'];
	}
    }
    $htm= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>CASHIER RECEIPT</b></td>
		</tr>
                <tr>
                    <td style='text-align: center;'><b>RECEIPT DATE ". ucwords(date('d-M-Y'))."</b></td>
                </tr>
                <tr>
		   <td style='text-align: center;'><b>FROM ".$Date_From." TO ".$Date_To."</b></td>
		</tr>
                
                <tr>
		   <td style='text-align: center;'><b>RECEIVED FROM ".  ucwords($Employee_Name)."</b></td>
		</tr>
                <tr>
		   <td style='text-align: center;'><b>RECEIVED BY ".  ucwords($Employee)."</b></td>
		</tr>
                
                
            </table>";

?> 
<?php
    $htm .="<table width=100% border=1 style=' border-spacing: 0px; border-collapse: separate;'>
		<tr>
                    <td style='text-align: center;'>CASH</td>
		    <td style='text-align: center;'>CREDIT</td>
                    <td style='text-align: center;'>TOTAL</td>
                </tr>";
		    
		    
if($Transaction_Type == 'All'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = '$Employee_ID' and
                pr.Registration_ID = pp.Registration_ID and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cash'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = '$Employee_ID' and
                pr.Registration_ID = pp.Registration_ID and
                (pp.Billing_Type = 'Outpatient Cash' or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'pre')) and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Credit'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid 
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = '$Employee_ID' and
                pr.Registration_ID = pp.Registration_ID and
                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit' or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'post')) and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cancelled'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid 
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = '$Employee_ID' and
                pr.Registration_ID = pp.Registration_ID and
                pp.Transaction_status = 'cancelled' and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }else{
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid 
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = 0 and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }
    //declare all total
    $Cash_Total = 0;
    $Credit_Total = 0;
    $Cancelled_Total = 0;
    $Grand_total_cancelled = 0;
    $Grand_total_credit = 0;
    $Grand_total_cash = 0;
    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        if(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0') or (strtolower($row['Billing_Type']) == 'inpatient cash'  && strtolower($row['payment_type']) == 'pre')) and (strtolower($row['Transaction_status']) == 'active')){
            $Cash_Total = $Cash_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0') or (strtolower($row['Billing_Type']) == 'inpatient cash')) and (strtolower($row['Transaction_status']) == 'cancelled')){
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '1') or (strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash'  && strtolower($row['payment_type']) == 'post')) and (strtolower($row['Transaction_status']) == 'active')){
            $Credit_Total = $Credit_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '1') or (strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit')) and (strtolower($row['Transaction_status']) == 'cancelled')){
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        }
    }
//    echo '<br /><br /><br /><br /><br /><br />';
    $htm .= "<tr><td style='text-align: center;'>".number_format($Cash_Total)."</td>";
    $htm .= "<td style='text-align: center;'>".number_format($Credit_Total)."</td>";
    $htm .= "<td style='text-align: center;'>".number_format($Cash_Total + $Credit_Total)."</td></tr>";
    
    


    $Cash_Total = 0;
    $Credit_Total = 0;
    $Cancelled_Total = 0;
    
?>


<?php
    //echo $htm;
    $htm .= "</table><br /><br /><br />";
    $htm.="<table border='0'>
        
            <tr>
                <td>Receiver's Sign:....................................................................</td>
            </tr>
            
            <tr>
                <td>
                
                </td>
            </tr>

            <tr>
            
            <td>Cashier's Sign:......................................................................</td>
            
            </tr>
            
        </table>";
    include("MPDF/mpdf.php");
    
    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('Printed on  {DATE d-m-Y H:m:s}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>