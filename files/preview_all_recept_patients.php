<?php
@session_start();
include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    $Title = '';
    $Date_From = $_GET['fromDate'];
    $Date_To = $_GET['toDate'];
    $Insurance = $_GET['Insurance'];
    $Payment_Type = $_GET['Patient_Type'];
    $Patient_Type = $_GET['Payment_Type'];
        
        
        
  
  
  
  $htm  = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h5>DATE FROM $Date_From TO $Date_To</h5></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

  
    if($Insurance == 'All'){
	
	//select all payment with all insurance
	
	if($Patient_Type == 'All'){
	    if($Payment_Type == 'All'){
		$Title = "<b>All Payments From ".$Date_From." To ".$Date_To."</b>";
		//Outpatient Cash, Outpatient Credit, Inpatient Cash & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit' or
								    pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
									 and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }elseif($Payment_Type == 'Cash'){
		$Title = "<b>All Outpatient Cash And Inpatient Cash From ".$Date_From." To ".$Date_To."</b>";
		//echo "Outpatient Cash & Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Inpatient Cash')
									 and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }else{
		//echo "Outpatient Credit & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }
	}elseif($Patient_Type == 'Outpatient'){
	    if($Payment_Type == 'All'){
		//echo "Outpatient Cash & Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Outpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Cash')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }else{
		//echo "Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }
	}else{
	    if($Payment_Type == 'All'){
		//echo "Inpatient Cash & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Inpatient Cash')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }else{
		//echo "Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Inpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }
	}
    }else{
	
	//select all payment based on insurance company
	if($Patient_Type == 'All'){
	    if($Payment_Type == 'All'){
		//echo "Outpatient Cash, Outpatient Credit, Inpatient Cash & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit' or
								    pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
										    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Outpatient Cash & Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Inpatient Cash')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }else{
		//echo "Outpatient Credit & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }
	}elseif($Patient_Type == 'Outpatient'){
	    if($Payment_Type == 'All'){
		//echo "Outpatient Cash & Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Outpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Cash')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }else{
		//echo "Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Outpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }
	}else{
	    if($Payment_Type == 'All'){
		//echo "Inpatient Cash & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Inpatient Cash')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }else{
		//echo "Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pp.registration_id = pr.registration_id and
							    pp.Transaction_status <> 'Cancelled' and
								(pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
	    }
	}
    }
    
    $htm .= "<table width='100%;' border='1' style='font-size:12px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
 $htm .=  "<thead>";
    
		$htm .="<tr>"
                        . " <th width=5%>SN</th>
					 <th width='25%'>PATIENT NAME</th>
			         <th width='25%' style='text-align: center;'>FOLIO NUMBER</th>
			         <th width='25%' style='text-align: center;'>RECEIPT NO</th>
			         <th width='25%' style='text-align: right;'>AMOUNT</th>"
                        . "</tr>";
            
		 $htm .= "</thead>";

    $results = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn));
		    
    while($row = mysqli_fetch_array($results)){
	//echo "<tr><td colspan=5><hr></td></tr>";
        
	    $htm .= "<tr><td>".$temp."</td>";
        $htm .="<td>".$row['Patient_Name']."</td>";
        $htm .="<td style='text-align: center;'>".$row['Folio_Number']."</a></td>";
        $htm .= "<td style='text-align: center;'>".$row['Patient_Payment_ID']."</span></td>";
        $htm .=  "<td style='text-align: right;'>".number_format($row['Amount'])."</td>"; 
		
	 $htm .= "</tr>";
	 //$total = $total + $row['Amount'];
	  $temp++;
        
//              $htm .= "<tr><td style='text-align:center;'>".$row['Patient_Name']."</td></tr>";
    }


$htm .= "</table>";

    //$htm.=$title;
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4-L');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>