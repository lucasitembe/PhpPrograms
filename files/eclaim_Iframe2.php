<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    $Title = '';
    
    
    $Branch = $_GET['Branch']; 
    $Date_From = $_GET['Date_From'];
    $Date_To = $_GET['Date_To'];
    $Insurance = $_GET['Insurance'];
    $Payment_Type = $_GET['Payment_Type'];
    $Patient_Type = $_GET['Patient_Type'];    
    $Patient_Name = mysqli_real_escape_string($conn,$_GET['Patient_Name']);
    
    
//	echo '<br/>'.$Date_From.'<br/>';
//	echo '<br/>'.$Date_To.'<br/>';
//	echo '<br/>'.$Insurance.'<br/>';
//	echo '<br/>'.$Payment_Type.'<br/>';
//	echo '<br/>'.$Patient_Type.'<br/>';
//        echo '<br/>'.$Patient_Name.'<br/>';

if(strtolower($Branch) == 'all'){

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
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit' or
								    pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
									 and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		$Title = "<b>All Outpatient Cash And Inpatient Cash From ".$Date_From." To ".$Date_To."</b>";
		//echo "Outpatient Cash & Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Inpatient Cash')
									 and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Outpatient Credit & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}elseif($Patient_Type == 'Outpatient'){
	    if($Payment_Type == 'All'){
		//echo "Outpatient Cash & Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Outpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}else{
	    if($Payment_Type == 'All'){
		//echo "Inpatient Cash & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Cash')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
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
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit' or
								    pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
										    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Outpatient Cash & Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Inpatient Cash')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Outpatient Credit & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}elseif($Patient_Type == 'Outpatient'){
	    if($Payment_Type == 'All'){
		//echo "Outpatient Cash & Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Outpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}else{
	    if($Payment_Type == 'All'){
		//echo "Inpatient Cash & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Cash')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}
    }
}else{


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
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit' or
								    pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
									 and sp.sponsor_id = pp.sponsor_id
									    and pp.receipt_date between '$Date_From' and '$Date_To' and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		$Title = "<b>All Outpatient Cash And Inpatient Cash From ".$Date_From." To ".$Date_To."</b>";
		//echo "Outpatient Cash & Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Inpatient Cash')
									 and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To' and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Outpatient Credit & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							    pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To' and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}elseif($Patient_Type == 'Outpatient'){
	    if($Payment_Type == 'All'){
		//echo "Outpatient Cash & Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To' and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Outpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To' and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To' and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}else{
	    if($Payment_Type == 'All'){
		//echo "Inpatient Cash & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To' and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Cash')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To' and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Credit')
								    and sp.sponsor_id = pp.sponsor_id
									and pp.receipt_date between '$Date_From' and '$Date_To' and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
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
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit' or
								    pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
										    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Outpatient Cash & Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Inpatient Cash')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Outpatient Credit & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}elseif($Patient_Type == 'Outpatient'){
	    if($Payment_Type == 'All'){
		//echo "Outpatient Cash & Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash' or pp.billing_type = 'Outpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Outpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Cash')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Outpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Outpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}else{
	    if($Payment_Type == 'All'){
		//echo "Inpatient Cash & Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Cash' or pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }elseif($Payment_Type == 'Cash'){
		//echo "Inpatient Cash";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Cash')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }else{
		//echo "Inpatient Credit";
		$select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
						pr.Patient_Name, pp.Folio_Number
						    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pr.Patient_Name like '%$Patient_Name%' and
							    pp.registration_id = pr.registration_id and
								(pp.billing_type = 'Inpatient Credit')
									and pp.receipt_date between '$Date_From' and '$Date_To'
									    and sp.sponsor_id = pp.sponsor_id and
										pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
									    pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
									    GROUP BY  pp.Registration_ID, pp.Folio_Number order by sp.Guarantor_Name, pp.Folio_Number";
	    }
	}
    }
   
    
}

    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td width=5%><b>SN</b></td><td width="25%"><b>PATIENT NAME</b></td>
                    <td width="25%" style="text-align: center;"><b>FOLIO NUMBER</b></td>
			<td width="25%" style="text-align: center;"><b>SPONSOR</b></td>
			    <td width="25%" style="text-align: right;"><b>AMOUNT</b></td>
				</tr>';
                        
    $results = mysqli_query($conn,$select_Filtered_Patients);
		    
    while($row = @mysqli_fetch_array($results)){
	//echo "<tr><td colspan=5><hr></td></tr>";
	echo '<tr><td>'.$temp.'</td>';
        echo "<td>".ucfirst($row['Patient_Name'])."</td>";
        echo "<td style='text-align: center;'><a href='eclaimreport.php?Folio_Number=".$row['Folio_Number']."&Insurance=".$row['Guarantor_Name']."&Registration_ID=".$row['Registration_ID']."&FolioSummaryReport=FolioSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$row['Folio_Number']."</a></td>";
        echo "<td style='text-align: center;'>".$row['Guarantor_Name']."</td>";
        echo "<td style='text-align: right;'>".number_format($row['Amount'])."</td>"; 
	echo "</tr>";
	$total = $total + $row['Amount'];
	$temp++;
    }echo "<tr><td colspan=5><hr></td></tr>";
    echo "<tr><td colspan=5 style='text-align: right;'><b> TOTAL : ".number_format($total)."</td></tr>";
    //echo "<tr><td colspan=5><hr></td></tr>";
?></table></center>
