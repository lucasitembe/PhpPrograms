<?php
    include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    $Title = '';
    
    
    if(isset($_GET['Branch'])){
	$Branch = $_GET['Branch'];
    }else{
	$Branch = '';
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
    
    if(isset($_GET['Insurance'])){
	$Insurance = $_GET['Insurance'];
    }else{
	$Insurance = '';
    }

    if(isset($_GET['Receipt_No'])){
	$Receipt_No = $_GET['Receipt_No'];
	$select_Filtered_Patients = "select pp.Billing_Type, sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
	pr.Patient_Name, pp.Folio_Number, emp.Employee_Name
	    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp, tbl_employee emp
		where pp.patient_payment_id = ppl.patient_payment_id and
		    pp.registration_id = pr.registration_id and
			emp.Employee_ID = pp.Employee_ID and
			    (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
				sp.sponsor_id = pp.sponsor_id and
				    pp.Transaction_type <> 'Direct Cash' and
				    pp.Patient_Payment_ID like '%$Receipt_No%'
					GROUP BY  pp.patient_payment_id order by pp.patient_payment_id";
    }else if(isset($_GET['Date_From']) || isset($_GET['Date_To'])){
	$select_Filtered_Patients = "select pp.Billing_Type, sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
	pr.Patient_Name, pp.Folio_Number, emp.Employee_Name
	    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp, tbl_employee emp
		where pp.patient_payment_id = ppl.patient_payment_id and
		    pp.registration_id = pr.registration_id and
			emp.Employee_ID = pp.Employee_ID and
			    (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
				sp.sponsor_id = pp.sponsor_id and
				    pp.Transaction_type <> 'Direct Cash' and
				    pp.receipt_date between '$Date_From' and '$Date_To'
					GROUP BY  pp.patient_payment_id order by pp.patient_payment_id";
    }else{
	$select_Filtered_Patients = "select pp.Billing_Type, sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
	pr.Patient_Name, pp.Folio_Number, emp.Employee_Name
	    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp, tbl_employee emp
		where pp.patient_payment_id = ppl.patient_payment_id and
		    pp.registration_id = pr.registration_id and
			emp.Employee_ID = pp.Employee_ID and
			    (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
				sp.sponsor_id = pp.sponsor_id and
				    pp.Transaction_type <> 'Direct Cash' and
				    pp.receipt_date between '$Date_From' and '$Date_To'
					GROUP BY  pp.patient_payment_id order by pp.patient_payment_id";
    }
    
				    
				
    echo '<center><table width =100% border=0>';
    echo '<tr><td width=5%><b>SN</b></td><td width="25%"><b>PATIENT NAME</b></td>
                    <td width="20%" style="text-align: center;"><b>RECEIPT N<u>O</u></b></td>
			<td width="15%" style="text-align: center;"><b>SPONSOR</b></td>
			<td width="25%" style="text-align: center;"><b>BILLING TYPE</b></td>
			<td width="25%" style="text-align: center;"><b>PREPARED BY</b></td>
			    <td width="25%" style="text-align: right;"><b>AMOUNT</b></td>
				</tr>';
                        
    $results = mysqli_query($conn,$select_Filtered_Patients);
		    
    while($row = mysqli_fetch_array($results)){
	echo "<tr><td colspan=7><hr></td></tr>";
	echo '<tr><td>'.$temp.'</td>';
        echo "<td>".ucfirst($row['Patient_Name'])."</td>";
        echo "<td style='text-align: center;'><a href='patientbillingedit.php?Patient_Payment_ID=".$row['Patient_Payment_ID']."&Insurance=".$row['Guarantor_Name']."&Registration_ID=".$row['Registration_ID']."&Selected=Selected&EditPayment=EditPaymentThisForm' target='_Parent' style='text-decoration: none;'>".$row['Patient_Payment_ID']."</a></td>";
        echo "<td style='text-align: center;'>".$row['Guarantor_Name']."</td>";
	echo "<td style='text-align: center;'>".$row['Billing_Type']."</td>";
        echo "<td style='text-align: center;'>".$row['Employee_Name']."</td>";
        echo "<td style='text-align: right;'>".number_format($row['Amount'])."</td>"; 
	echo "</tr>";
	$total = $total + $row['Amount'];
	$temp++;
    }echo "<tr><td colspan=7><hr></td></tr>";
    echo "<tr><td colspan=7 style='text-align: right;'><b> TOTAL : ".number_format($total)."</td></tr>";
    echo "<tr><td colspan=7><hr></td></tr>";
?></table></center>
