<!---<link rel="stylesheet" href="table.css" media="screen"> -->
<style>
    
    .linkClick{
        color:#18647B;
    }.linkClick:hover{
        cursor: pointer;
    }
</style>
<link rel='stylesheet' href='fixHeader.css'>

<?php
    include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    $Title = '';
    $Date_From = $_POST['fromDate'];
    $Date_To = $_POST['toDate'];
    $Insurance = $_POST['Insurance'];
    $Payment_Type = $_POST['Patient_Type'];
    $Patient_Type = $_POST['Payment_Type'];

    /*
	echo '<br/>'.$Date_From.'<br/>';
	echo '<br/>'.$Date_To.'<br/>';
	echo '<br/>'.$Insurance.'<br/>';
	echo '<br/>'.$Payment_Type.'<br/>';
	echo '<br/>'.$Patient_Type.'<br/>';
    */
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
    echo '<center><table width =100% id="paymentDetails" class="display fixTableHead">';
    echo '<thead>
				<tr style="background-color: #ccc;">
	                 <th width=5%>SN</th>
					 <th width="25%">PATIENT NAME</th>
			         <th width="25%" style="text-align: center;">FOLIO NUMBER</th>
			         <th width="25%" style="text-align: center;">RECEIPT NO</th>
			         <th width="25%" style="text-align: right;">AMOUNT</th>
				</tr>
			</thead>';
                        
    $results = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn));
		    
    while($row = mysqli_fetch_array($results)){
	//echo "<tr><td colspan=5><hr></td></tr>";
	    echo '<tr><td>'.$temp.'</td>';
        echo "<td>".ucfirst($row['Patient_Name'])."</td>";
        echo "<td style='text-align: center;'><a href='foliosummaryreport.php?Folio_Number=".$row['Folio_Number']."&Insurance=".$row['Guarantor_Name']."&Registration_ID=".$row['Registration_ID']."&FolioSummaryReport=FolioSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$row['Folio_Number']."</a></td>";
        echo "<td style='text-align: center;'><span class='linkClick' onclick='Print_Receipt_Payment(".$row['Patient_Payment_ID'].")'>".$row['Patient_Payment_ID']."</span></td>";
        echo "<td style='text-align: right;'>".number_format($row['Amount'])."</td>"; 
		
	 echo "</tr>";
	 //$total = $total + $row['Amount'];
         //
	  $temp++;
    }
	//echo "<tr><td colspan=5><hr></td></tr>";
     // echo "<tr><td colspan=5 style='text-align: right;'><b> TOTAL : ".number_format($total)."</td></tr>";
   // echo "<tr><td colspan=5><hr></td></tr>";
  // echo "<tr></tr>";
     // echo '';
?></table>
 <div id="totalAmount"></div>
</center>


<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script>
    $('#paymentDetails').dataTable({
    "bJQueryUI":true,
    "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // Total over all pages
        total = api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            } );

        // Total over this page
        pageTotal = api
            .column( 4, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Update footer
    //            $( api.column( 3 ).footer() ).html(
    //                ''+pageTotal +' ( '+ total +' total)'
    //            );

        $('#totalAmount').html( ''+addCommas(pageTotal) +' ( '+ addCommas(total) +' total)');
    }

	});
	
	
   function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
</script>

<script>
function Print_Receipt_Payment(Patient_Payment_ID){
    // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
      var winClose=popupwindow('individualpaymentreportindirect.php?Patient_Payment_ID='+Patient_Payment_ID+'&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
      //winClose.close();
     //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');
    
}
  
function popupwindow(url, title, w, h) {
  var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
   var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow= window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
      
      return mypopupWindow;
}


</script>
