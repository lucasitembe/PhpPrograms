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

<?php
    include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    $Title = '';

    $Branch = $_GET['Branch'];
    $Date_From = $_GET['date_From'];
    $Date_To = $_GET['date_To'];
    $Insurance = $_GET['Insurance'];
    $filterAuthorizationNo = $_GET['AuthorizationNo'];
    $Payment_Type = $_GET['Payment_Type'];
    $Search_Value = $_GET['Search_Value'];
    $Patient_Number = $_GET['Patient_Number'];
    $current_patient_status = $_GET['current_patient_status'];
    $Billing_Type = "";

    $filter= " ";
    $filter_one_patient= " ";
    $filter_current_patient_status = " ";
    if($filterAuthorizationNo == "with_authorization_no"){
      $filter =" and TRIM(ci.AuthorizationNo) !='' ";
    }else if($filterAuthorizationNo == "without_authorization_no"){
      $filter =" and (TRIM(ci.AuthorizationNo) = ''  or TRIM(ci.AuthorizationNo) IS NULL) ";
    }
    if(trim($Search_Value) != '' && trim($Patient_Number) == ''){
      $filter_one_patient="  AND pr.Patient_Name LIKE '%$Search_Value%' ";
    }
    if(trim($Patient_Number) != ''){
      $filter_one_patient="  AND pr.Registration_ID =$Patient_Number";
    }
    if($current_patient_status == "all"){
      $filter_current_patient_status = " (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') ";
    }else if($current_patient_status == 'Inpatient'){
      $filter_current_patient_status = " pp.Billing_Type = 'Inpatient Credit' ";
    }else if($current_patient_status == 'Outpatient'){
      $filter_current_patient_status = "  pp.Billing_Type = 'Outpatient Credit' ";
    }
   
    $select_Filtered_Patients = "  SELECT pr.Patient_Name, pr.Phone_Number, pr.Registration_ID, sp.Guarantor_Name, pp.Receipt_Date,  pp.Folio_Number,pp.Billing_Type,pp.Patient_Bill_ID, pp.Billing_Process_Status, pp.Billing_Process_Employee_ID, ci.AuthorizationNo, ci.Check_In_ID, ppl.Clinic_ID
                from tbl_patient_payments pp, tbl_check_in ci, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
                where pp.patient_payment_id = ppl.patient_payment_id and
                ci.Check_In_ID = pp.Check_In_ID and
                pp.registration_id = pr.registration_id and
                pp.receipt_date between '$Date_From' and '$Date_To' and
                sp.sponsor_id = pp.sponsor_id and
                pp.Bill_ID IS NULL and
		            pp.Transaction_status <> 'cancelled' and
                $filter_current_patient_status and
                pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
                pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
                $filter $filter_one_patient
                GROUP BY ci.Check_In_ID  ORDER BY ci.Check_In_ID DESC ";
  // if($Patient_Number =='215455'){
  //   die($select_Filtered_Patients);
  // }
//die($select_Filtered_Patients);
    echo '<center><table width =100% border=0  class="fixTableHead">';
    echo '<thead><tr id="thead">
                <td width=5%><b>SN</b></td>
                <td width="15%"><b>Patient Name</b></td>
                <td width="10%"><b>Phone Number</b></td>
                <td width="7%" style="text-align: right;"><b>Patient#</b></td>
                <td width="7%" style="text-align: right;"><b>Authorization#</b></td>
                <td width="8%" style="text-align: right;"><b>Folio Number</b></td>
                <td width="7%" style="text-align: center;"><b>Sponsor</b></td>
                <td width="8%" style="text-align: center;"><b>Clinic Name</b></td>
                <td width="8%" style="text-align: center;"><b>Served Date</b></td>
                <td width="7%" style="text-align: right;"><b>Patient Status</b></td>
                <td width="18%" style="text-align: center;"><b>Approval Status</b></td>
                <td width="10%" style="text-align: right;"><b>Amount</b></td>
			</tr></thead>';

    $results = mysqli_query($conn,($select_Filtered_Patients));
    while($row = mysqli_fetch_array($results)){
    	//get first served date
        $Folio_Number = $row['Folio_Number'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Patient_Bill_ID = $row['Patient_Bill_ID'];
        $Registration_ID = $row['Registration_ID'];
        $AuthorizationNo = $row['AuthorizationNo'];
        $Check_In_ID = $row['Check_In_ID'];
	$Clinic_ID = $row['Clinic_ID'];
        //$Billing_Type = $row['Billing_Type'];

		/*
			check if other items have already billed

		*/
    $query1 = "SELECT patient_payment_id FROM tbl_patient_payments WHERE Check_In_ID = '$Check_In_ID' AND Bill_ID IS NOT NULL";
		$billed_items = mysqli_query($conn,($query1));
		if(mysqli_num_rows($billed_items) >0){
      continue;
    }
       $row_style = '';
		    $select111 = mysqli_query($conn,"SELECT cd.Admission_ID, Admission_Status, Discharge_Clearance_Status FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
        if(mysqli_num_rows($select111) > 0){
            while($rw = mysqli_fetch_assoc($select111)){
            $Billing_Type ='Inpatient';
            $Admission_Status = $rw['Admission_Status']; 
            $Discharge_Clearance_Status = $rw['Discharge_Clearance_Status']; 
            $Discharge_Reason_ID = $rw['Discharge_Reason_ID'];
            if($Discharge_Reason_ID >0 && $Discharge_Clearance_Status=='not cleared'){
                $row_style = "style='background-color: yellow; color:#fff;'";
            }
            }            
        }else{
            $Billing_Type ='Outpatient';
            $Discharge_Reason_ID ='';
            $Discharge_Clearance_Status ='';
            $Admission_Status = '';
        }
        /*
            check if a patient has been consulted by the doctor 
        */
$query2 = "SELECT c.consultation_ID, c.Clinic_ID, c.Process_Status FROM tbl_consultation c, tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND pp.Check_In_ID ='$Check_In_ID'";
        $ConsultationInfo = mysqli_query($conn,($query2));

            $hasServedConsultation = array();
        while ($rowConsult = mysqli_fetch_assoc($ConsultationInfo)) {
            # code...
           $Consultation_ID = $rowConsult['consultation_ID'];
           $Process_Status = $rowConsult['Process_Status'];
           $Clinic_IDs = $rowConsult['Clinic_ID'];
           array_push($hasServedConsultation,$Process_Status);
        }

        /*==== Comment this code for time being ilikurusu vipimo vilivyo fanyika kwa kutengenezewa ====== */
        // if(!in_array('served',$hasServedConsultation)){
        //   continue;
        // }
        // ===== End OF comment =====

        if($Clinic_IDs > 0){
          $Clinic_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID = '$Clinic_IDs'"))['Clinic_Name'];
        }
        $signature = mysqli_fetch_assoc(mysqli_query($conn, "SELECT signature FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'  AND signature IS NOT NULL"));
        
        if(!empty($signature)){
          $row_style = "style='background-color: green; color:#fff;'";
        }
        echo '<tr '.$row_style.'><td>'.$temp.'</td>';
        echo "<td>".ucfirst($row['Patient_Name'])."</td>";
        echo "<td>".$row['Phone_Number']."</td>";
        echo "<td style='text-align: right;'>".$row['Registration_ID']."</td>";
        echo "<td style='text-align: right;'>".$row['AuthorizationNo']."</td>";
        echo "<td style='text-align: right;'><a href='foliosummaryreport.php?Folio_Number=".$row['Folio_Number']."&Insurance=".$row['Guarantor_Name']."&Registration_ID=".$row['Registration_ID']."&FolioSummaryReport=FolioSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$row['Folio_Number']."</a></td>";
        echo "<td style='text-align: center;'>".$row['Guarantor_Name']."</td>";
        echo "<td style='text-align: center;'>" . $Clinic_Name . "</td>";
echo "<td style='text-align: center;'>".$row['Receipt_Date']."</td>";
echo "<td style='text-align: left;'>".$Billing_Type."</td>";
?>
	  <td style="text-align:center;">
		<input type='button' name='View_Button' id='View_Button' value='view' onclick="openItemDialog(<?php echo $row['Folio_Number']; ?>,'<?php echo $row['Guarantor_Name']; ?>',<?php echo $row['Registration_ID']; ?>,'<?php echo $Payment_Type; ?>','<?php echo $Patient_Bill_ID; ?>',<?php echo $row['Check_In_ID']; ?>);" class='art-button-green'>

<?php
	if($row['Billing_Process_Status'] == 'Approved'){
	    $Billing_Process_Employee_ID = $row['Billing_Process_Employee_ID'];
	    //get employee Name
	    $select_employee = mysqli_query($conn,("select Employee_Name from tbl_Employee where Employee_ID = '$Billing_Process_Employee_ID'")) or die(mysqli_error($conn));
	    while($select_emp = mysqli_fetch_array($select_employee)){
            $Employee_Name = $select_emp['Employee_Name'];
	    }
	?>
	    <!--<input type="checkbox" disabled='disabled' checked='checked' >-->
		<span style='color: #037CB0;'><b style="color: #037CB0;">Approved By <?php echo $Employee_Name; ?></b></span></td>

	<?php
	}else{
	?>

	<span style='color: green;'><b>Pending.....</b></span></td>
   
	<?php
	}

	//generate amount based on folio number
	$select_total = mysqli_query($conn,("SELECT sum((price - discount)*quantity) as Amount from
					tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_check_in ci where
          ci.Check_In_ID=pp.Check_In_ID and
          ci.Check_In_ID='$Check_In_ID' and
					pp.patient_payment_id = ppl.patient_payment_id and
					pr.registration_id = pp.registration_id and
					pp.Folio_Number = '$Folio_Number' and
					pp.Patient_Bill_ID = '$Patient_Bill_ID' and
					pp.Bill_ID IS NULL and
					pp.Transaction_status <> 'cancelled' and ppl.Status<>'removed' and
					pp.Registration_ID = '$Registration_ID' and
					(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') and
					pp.Sponsor_ID = (SELECT Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Insurance' limit 1) ")) or die(mysqli_error($conn));

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
}echo "<tr><td colspan=11><hr></td></tr>";
    echo "<tr><td colspan=11 style='text-align: right;'><b> TOTAL : ".number_format($total)."</td></tr>";
    echo "<tr><td colspan=11 ><hr></td></tr>";
?></table></center>
