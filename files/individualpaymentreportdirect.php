<?php
    @session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

	$isReceipt = false;
	$q = mysqli_query($conn,"SELECT * FROM tbl_printer_settings") or die(mysqli_error($conn));
	$row = mysqli_fetch_assoc($q);
	$exist = mysqli_num_rows($q);
	if ($exist > 0) {
	    $Paper_Type = $row['Paper_Type'];
	    $Include_Sponsor_Name_On_Printed_Receipts = $row['Include_Sponsor_Name_On_Printed_Receipts'];
	    if ($Paper_Type == 'Receipt') {
	        $isReceipt = true;
	    }
	}else{
	    $Include_Sponsor_Name_On_Printed_Receipts = 'yes';
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
    $htm = "<style>
	    @page{
		margin-top:20px;
		margin-left:0;
	    }
	    </style>";
	include("./includes/reportheader.php");
	
    $htm .='<tr><td colspan=2><hr height="2px"></td></tr>';
    $select_Transaction_Items = mysqli_query($conn,"select * from tbl_employee emp, tbl_patient_registration preg, tbl_patient_payments pp, tbl_patient_payment_item_list ppl
		where emp.employee_id = pp.employee_id and
		    preg.registration_id = pp.registration_id and
			pp.patient_payment_id = ppl.patient_payment_id and
			    pp.Patient_Payment_ID = '$Patient_Payment_ID' limit 1"); 
   
    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
	if(strtolower($row['Billing_Type']) == 'outpatient credit' || strtolower($row['Billing_Type']) == 'inpatient credit'){
	    $htm .='<tr><td colspan=2 style="text-align: center;"><b>DEBIT NOTE</b></td></tr></center></td></tr>'; 
	}else{
	    $htm .='<tr><td colspan=2 style="text-align: center;"><b>SALES RECEIPT</b></td></tr></center></td></tr>'; 
	}	
	$htm .='<tr><td colspan=2><hr height="2px"></td></tr>'; 
	//SELECT BILLING TYPE
	if(strtolower($row['Billing_Type']) == 'outpatient cash'){
	    $Billing_Type = 'Cash';
	}elseif(strtolower($row['Billing_Type']) == 'outpatient credit'){
	    $Billing_Type = 'Credit';
	}
	//select the id of employee who made the transaction
	$Employee_ID = $row['Employee_ID'];
	$htm .='<tr>
		    <td width="40%">Name: </td>
		    <td>'.$row['First_Name']." ".$row['Last_Name'].'</td></tr>';
	if(strtolower($Include_Sponsor_Name_On_Printed_Receipts) == 'yes'){
		$htm .='<tr>
			<td>Sponsor: </td>
		    <td>'.$row['Sponsor_Name'].'</td></tr>';
	}
	$htm .='<tr><td>MRN : </td>
		    <td>'.$row['Registration_ID'].'</td></tr>'; 
	$htm .='<tr>
		    <td>Folio N<u>o</u>: </td>
		    <td>'.$row['Folio_Number'].'</td></tr>';
	$htm .='<tr><td>Claim N<u>o</u>: </td>
		    <td>'.$row['Claim_Form_Number'].'</td></tr>';
	
	$Receipt_Number = $row['Patient_Payment_ID'];
	$htm .='<tr><td>Receipt N<u>o</u>: </td>
		<td>'.$Receipt_Number.'</td></tr>';
	$htm .='<tr><td>Mode: </td>
		<td>'.$Billing_Type.'</td></tr>';
	$htm .='<tr><td colspan=2><hr height="2px"></td></tr>';
	$printed_receipt = $row['receipt_count'];
    }
    
    
$select_Transaction_Items = mysqli_query($conn,
            "select * from tbl_employee emp, tbl_patient_registration preg, tbl_patient_payments pp, tbl_patient_payment_item_list ppl
		where emp.employee_id = pp.employee_id and
		    preg.registration_id = pp.registration_id and
			pp.patient_payment_id = ppl.patient_payment_id and
			    pp.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn)); 

     while($row = mysqli_fetch_array($select_Transaction_Items)){
	$htm .= "<tr><td>Category: </td>";
        $htm .= "<td>".$row['Item_Category_Name']."</td></tr>";
	
	$htm .= "<tr><td>Item / Service: </td>";
        $htm .= "<td>".$row['Item_Name']."</td></tr>";
	
	$htm .= "<tr><td>Price: </td>";
        $htm .= "<td>".number_format($row['Price'])."</td></tr>";
	
	$htm .= "<tr><td>Discount: </td>";
        $htm .= "<td>".$row['Discount']."</td></tr>";
	
	$htm .= "<tr><td>Quantity: </td>";
        $htm .= "<td>".$row['Quantity']."</td></tr>";
	
        $htm .= "<tr><td colspan=6 style='text-align: right;'>Sub Total : ".number_format(($row['Price'] - $row['Discount'])*$row['Quantity']);
        $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity'])."</i>";
	$htm .= "<tr><td colspan=6><hr height='3px'></td></tr>";
    } 
        $htm .= "<tr><td style='text-align: right;' colspan=7><b> TOTAL : ".number_format($total)."</b></td></tr>";
	$htm .= "<tr><td colspan=6><hr height='3px'></td></tr>";

    //select the name of the employee who made the transaction based on employee id we got above
    $select_Employee_Name = mysqli_query($conn,"select Employee_Name from tbl_employee where employee_id = '$Employee_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Employee_Name)){
	$Employee_Name = $row['Employee_Name'];
    }
    
    $htm .='<tr><td>Prep By: </td>
		<td>'.$Employee_Name.'</td></tr>';
    $htm .='<tr><td>Printed By</u>: </td>
		<td>'.$_SESSION['userinfo']['Employee_Name'].'</td></tr>';
    $htm .='<tr><td colspan=2>Date:  '.$Date_Time.'</td></tr>';

$htm .= '</table>';
    
?>

<?php
    $html .= "</table></center>";
	if($printed_receipt >1){
        echo "<hr><h4 style='background:red; font-size:20px; -webkit-transform: rotate(-45deg); '>REPRINTED RECEIPT COPY</h4>";
    }

  
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 

    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>

 