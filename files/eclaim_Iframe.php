<?php
    include("./includes/connection.php");

	echo "<link rel='stylesheet' href='fixHeader.css'>";

    $temp = 1;
    $total = 0;
    $Title = '';

    if(isset($_GET['Start_Date'])){
    	$Start_Date = $_GET['Start_Date'];
    }else{
    	$Start_Date = '';
    }

    if(isset($_GET['End_Date'])){
    	$End_Date = $_GET['End_Date'];
    }else{
    	$End_Date = '';
    }

    if(isset($_GET['Sponsor_ID'])){
    	$Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
    	$Sponsor_ID = '';
	}

	// echo $Sponsor_ID;

    if(isset($_GET['Patient_Name'])){
    	$Patient_Name = $_GET['Patient_Name'];
    }else{
    	$Patient_Name = '';
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }

    if(isset($_GET['Patient_Name'])){
    	$select = mysqli_query($conn,"SELECT pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pp.registration_id = pr.registration_id and
							(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit') and
							pp.receipt_date between '$Start_Date' and '$End_Date' and
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Sponsor_ID = '$Sponsor_ID' and
							(pp.Billing_Process_Status='Approved' or pp.Billing_Process_Status='billed') and
							pr.Patient_Name like '%$Patient_Name%'
							GROUP BY  pp.Check_In_ID order by pp.Folio_Number") or die(mysqli_error($conn));
    }else{
    	$select = mysqli_query($conn,"SELECT pp.Check_In_ID, sp.Guarantor_Name, pp.Patient_Bill_ID, pr.Date_Of_Birth, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, pr.Patient_Name, pp.Folio_Number, pp.Sponsor_ID
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
							where pp.patient_payment_id = ppl.patient_payment_id and
							pp.registration_id = pr.registration_id and
							(pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit') and
							pp.receipt_date between '$Start_Date' and '$End_Date' and
							pp.Billing_Process_Status='billed' and
							sp.Sponsor_ID = pp.Sponsor_ID and
							pp.Sponsor_ID = '$Sponsor_ID'
							GROUP BY  pp.Check_In_ID order by pp.Folio_Number") or die(mysqli_error($conn));
    }


    echo '
		<center>
			<table width =100% border=0 class="fixTableHead">
				<thead>
					<tr style="background-color: #ccc;">
						<td width=5%><b>SN</b></td>
						<td><b>PATIENT NAME</b></td>
						<td width="15%" style="text-align: left;"><b>PATIENT #</b></td>
						<td width="15%" style="text-align: left;"><b>AGE</b></td>
						<td width="15%" style="text-align: center;"><b>SPONSOR NAME</b></td>
						<td width="12%" style="text-align: center;"><b>FOLIO NUMBER</b></td>
						<td width="15%" style="text-align: right;"><b>AMOUNT</b></td><td></td>
					</tr>
				</thead>
				';

    while($row = mysqli_fetch_array($select)){
    	$Folio_Number = $row['Folio_Number'];
    	$Patient_Bill_ID = $row['Patient_Bill_ID'];
    	$Registration_ID = $row['Registration_ID'];
        $Check_In_ID = $row['Check_In_ID'];

    	$Total_Required = 0;

    	//calculate total
    	$cal_total = mysqli_query($conn,"SELECT Price, Quantity, Discount
					from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
					where pp.Patient_Payment_ID = ppl.Patient_Payment_ID
					and pp.Folio_Number = '$Folio_Number'
					and	pp.Patient_Bill_ID = '$Patient_Bill_ID'
					and pp.Check_In_ID = '$Check_In_ID'
					and	pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    	$nm = mysqli_num_rows($cal_total);
    	if($nm > 0){
    		while ($tt = mysqli_fetch_array($cal_total)) {
    			$Total_Required += (($tt['Price'] - $tt['Discount']) * $tt['Quantity']);
    		}
    	}
    	$Date_Of_Birth = $row['Date_Of_Birth'];
    	$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";

		echo '<tr><td>'.$temp.'</td>';
        echo "<td>".ucwords(strtolower($row['Patient_Name']))."</td>";
        echo "<td style='text-align: left;'>".$row['Registration_ID']."</td>";
        echo "<td style='text-align: left;'>".$age."</td>";
        echo "<td style='text-align: center;'>".$row['Guarantor_Name']."</td>";
        echo "<td style='text-align: center;'>".$row['Folio_Number']."</td>";
        echo "<td style='text-align: right;'>".number_format($Total_Required)."</td>";
		echo "<td style='text-align: center;'>
				<input type='button' class='art-button-green' value='PREVIEW' onclick='Preview_Details(".$row['Folio_Number'].",".$row['Sponsor_ID'].",".$row['Registration_ID'].",".$row['Patient_Bill_ID'].",".$row['Check_In_ID'].")'>
				</td>";
		echo "</tr>";


		$total += $Total_Required;
		$temp++;

		//display title
		if($temp%20 == 0){
			echo $Title;
		}
    }
    echo "<tr><td colspan='8'><hr></td></tr>";
    echo "<tr><td colspan='8' style='text-align: right;'><b> GRAND TOTAL : ".number_format($total)."</td></tr>";
?></table></center>
