<!--<link rel="stylesheet" href="table.css" media="screen">-->
<script type='text/javascript'>
    function Approval(Sponsor_ID,Folio_Number,Payment_Type,Registration_ID,Approval_ID){
	if(window.XMLHttpRequest) {
	    myObject = new XMLHttpRequest();
	}else if(window.ActiveXObject){ 
	    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObject.overrideMimeType('text/xml');
	}
	myObject.onreadystatechange = function (){
	    data = myObject.responseText;
	    if (myObject.readyState == 4) {
		Approval_ID.disabled = 'disabled';
		document.getElementById('Approval_Status_'+Folio_Number+'_'+Sponsor_ID+'_'+Registration_ID).innerHTML = data;
	    }
	}; //specify name of function that will handle server response........
	myObject.open('GET','Approval_Bill.php?Registration_ID='+Registration_ID+'&Payment_Type='+Payment_Type+'&Sponsor_ID='+Sponsor_ID+'&Folio_Number='+Folio_Number,true);
	myObject.send();
	//alert("kelvin");
    }
    
</script>

<link rel='stylesheet' href='fixHeader.css'>

<?php
    include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    $Title = '';
	
    $Branch = $_GET['Branch']; 
    $Date_From = $_GET['date_From'];
    $Date_To = $_GET['date_To'];
    $Insurance = $_GET['Insurance'];
    $Payment_Type = $_GET['Payment_Type'];
    
    
    
    $select_Filtered_Patients = "SELECT pr.Patient_Name, pr.Registration_ID, sp.Guarantor_Name, pp.Folio_Number,PP.Patient_Bill_ID, pp.Billing_Process_Status, pp.Billing_Process_Employee_ID 
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
                where pp.patient_payment_id = ppl.patient_payment_id and
                pp.registration_id = pr.registration_id and
                pp.receipt_date between '$Date_From' and '$Date_To' and
                sp.sponsor_id = pp.sponsor_id and
                pp.Bill_ID IS NULL and
		pp.Transaction_status <> 'cancelled' and
                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') and              
                pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
                pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
                GROUP BY pr.Registration_ID, pp.Folio_Number, PP.Patient_Bill_ID order by pp.Folio_Number ";
                    
                    
                    
    echo '<center><table width =100% border=0 class="fixTableHead"><thead>';
    echo '
			<tr style="background-color: #ccc;" id="thead"><td width=5%><b>SN</b></td><td width="25%"><b>Patient Name</b></td>
				<td width="7%" style="text-align: right;"><b>Patient#</b></td>
				<td width="8%" style="text-align: right;"><b>Folio Number</b></td>
				<td width="9%" style="text-align: center;"><b>Sponsor</b></td>
				<td width="15%" style="text-align: center;"><b>First Served Date</b></td>
				<td width="25%" style="text-align: center;"><b>Approval Status</b></td>
				<td width="15%" style="text-align: right;"><b>Amount</b></td>
			</tr>
		</thead>';
      
    $results = mysqli_query($conn,$select_Filtered_Patients);
    while($row = mysqli_fetch_array($results)){
    	//get first served date
        $Folio_Number = $row['Folio_Number'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Patient_Bill_ID = $row['Patient_Bill_ID'];
        $Registration_ID = $row['Registration_ID'];
        $first_saved_date = mysqli_query($conn,"SELECT Payment_Date_And_Time from  tbl_patient_payments
					    where Folio_Number = '$Folio_Number' and
					    Patient_Bill_ID = '$Patient_Bill_ID' and
						Sponsor_ID = (select sponsor_id from tbl_sponsor 
						where guarantor_name = '$Guarantor_Name' limit 1) 
        				and registration_ID = '$Registration_ID'
						AND receipt_date between '$Date_From' 
						and '$Date_To'
						order by patient_payment_id asc limit 1") or 
        die(mysqli_error($conn));
        $num2 = mysqli_num_rows($first_saved_date);
        if($num2 > 0){
            while($num_rows = mysqli_fetch_array($first_saved_date)){
            	$Payment_Date_And_Time = $num_rows['Payment_Date_And_Time'];
        	}

        }else{
            $Payment_Date_And_Time = '';
        }
     
        echo '<tr><td>'.$temp.'</td>';
        echo "<td>".ucfirst($row['Patient_Name'])."</td>";
        echo "<td style='text-align: right;'>".$row['Registration_ID']."</td>";
        echo "<td style='text-align: right;'><a href='foliosummaryreport.php?Folio_Number=".$row['Folio_Number']."&Insurance=".$row['Guarantor_Name']."&Registration_ID=".$row['Registration_ID']."&FolioSummaryReport=FolioSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$row['Folio_Number']."</a></td>";
        echo "<td style='text-align: center;'>".$row['Guarantor_Name']."</td>";
echo "<td style='text-align: center;'>".$Payment_Date_And_Time."</td>";
?>
	    <td>
		<!-- <input type='button' name='View_Button' id='View_Button' value='view' onclick="openItemDialog(<?php echo $row['Folio_Number']; ?>,'<?php echo $row['Guarantor_Name']; ?>',<?php echo $row['Registration_ID']; ?>,'<?php echo $Payment_Type; ?>','<?php echo $Patient_Bill_ID; ?>');" class='art-button-green'> -->

<?php
	if($row['Billing_Process_Status'] == 'Approved'){
	    $Billing_Process_Employee_ID = $row['Billing_Process_Employee_ID'];
	    //get employee Name
	    $select_employee = mysqli_query($conn,"SELECT Employee_Name from tbl_Employee where Employee_ID = '$Billing_Process_Employee_ID'") or die(mysqli_error($conn));
	    while($select_emp = mysqli_fetch_array($select_employee)){
            $Employee_Name = $select_emp['Employee_Name'];
	    }
	?>    
	    <!--<input type="checkbox" disabled='disabled' checked='checked' >-->
		<span style='color: #037CB0;'><b style="color: #037CB0;">Approved By <?php echo $Employee_Name; ?></b></span></td>
		
	<?php
	}else{
	?>
	
	<!-- <span style='color: green;'><b>Pending.....</b></span></td> -->
    <!--<span id='Approval_Status_<?php echo $row['Folio_Number']; ?>_<?php echo $row['Sponsor_ID']; ?>_<?php echo $row['Registration_ID']; ?>' name='Approval_Status_<?php echo $row['Folio_Number']; ?>_<?php echo $row['Sponsor_ID']; ?>_<?php echo $row['Registration_ID']; ?>' style='color: green;'><b>Pending.....</b></span></td>-->    
        
	<?php
	}
	
	//generate amount based on folio number
	$select_total = mysqli_query($conn,"SELECT sum((price - discount)*quantity) as Amount from
					tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr where
					pp.patient_payment_id = ppl.patient_payment_id and
					pr.registration_id = pp.registration_id and
					pp.Folio_Number = '$Folio_Number' and
					pp.Patient_Bill_ID = '$Patient_Bill_ID' and
					pp.Bill_ID IS NULL and
					pp.Transaction_status <> 'cancelled' and
					pp.Registration_ID = '$Registration_ID' and
					(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') 
					AND pp.receipt_date between '$Date_From' and '$Date_To' 
					AND pp.Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Insurance' limit 1)") or die(mysqli_error($conn));
	
	$num_rows = mysqli_num_rows($select_total);
	if($num_rows > 0){
	    while($dt = mysqli_fetch_array($select_total)){
		$Amount = $dt['Amount'];
	    }
	}else{
	    $Amount = 0;
	}
	echo "<td style='text-align: right;'>".number_format($Amount)."</td>";
	echo "</tr>";
	$total = $total + $Amount;
	$temp++;
    }echo "<tr><td colspan=8><hr></td></tr>";
    echo "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : ".number_format($total)."</td></tr>";
    echo "<tr><td colspan=8 ><hr></td></tr>";
?></table></center>