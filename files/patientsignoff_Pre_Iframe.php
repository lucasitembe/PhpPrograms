<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    @session_start();
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
    //GET BRANCH ID
    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    ?>
    <script type='text/javascript'>
        function patientsignoff(Registration_ID,Patient_Payment_ID,Patient_Name) {
	    var Confirm_Signoff=confirm("Are you sure you want to signoff "+Patient_Name+"?");
	    if (Confirm_Signoff) {
		var Employee_ID = "<?php echo $_SESSION['userinfo']['Employee_ID']; ?>";
	     if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','patientsignoff_ajax.php?Patient_Payment_ID='+Patient_Payment_ID+'&Registration_ID='+Registration_ID+'&Employee_ID='+Employee_ID,true);
	    mm.send();
	    return true
	    }else{
             return false;
	    }
        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
                document.location.reload();
            }
        }
    </script>
    <?php
    echo '<center><table width =100% >';
    echo "<tr id='thead'>
		<td style='width:3%;'><b>SN</b></td>
		<td><b>PATIENT NAME</b></td>
                <td style='width:8%;'b>SPONSOR</b></td>
                    <td style='width:12%;'><b>DATE CONSULTED</b></td>
                        <td><b>PHONE#</b></td>
			<td style='width:12%;'><b>FINAL DIAGNOSIS</b></td>
                        <td><b>PROCEDURE</b></td>
                        <td><b>SURGERY</b></td>
			<td><b>TREATMENT</b></td>
			<td><b>RADIOLOGY</b></td>
			<td><b>LAB</b></td>
			<td style='width:6%;'><b>ACTION</b></td>
	  </tr>";
    $qr = "SELECT pr.Registration_ID,pr.Patient_Name,pr.Gender,pr.Date_Of_Birth,
		  pr.Phone_Number,ppl.Patient_Payment_Item_List_ID,s.Guarantor_Name,
		  Member_Number,Transaction_Date_And_Time,pp.Patient_Payment_ID,
		  (SELECT c.Consultation_Date_AND_Time FROM tbl_consultation c WHERE c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID limit 1) as consulted_date,
		  (Select MAX(ic.Payment_Item_Cache_List_ID)
				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
				AND c.consultation_ID = pc.consultation_ID
				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
				AND ic.Check_In_Type='Laboratory' limit 1) as laboratory,
		  (Select MAX(ic.Payment_Item_Cache_List_ID)
				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
				AND c.consultation_ID = pc.consultation_ID
				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
				AND ic.Check_In_Type='Procedure' limit 1) as _procedure,
		  (Select MAX(ic.Payment_Item_Cache_List_ID)
				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
				AND c.consultation_ID = pc.consultation_ID
				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
				AND ic.Check_In_Type='Sugery' limit 1) as sugery,
		  (Select MAX(ic.Payment_Item_Cache_List_ID)
				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
				AND c.consultation_ID = pc.consultation_ID
				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
				AND ic.Check_In_Type='Pharmacy' limit 1) as treatment,
		  (Select MAX(dc.Disease_Consultation_ID)
				FROM tbl_disease_consultation dc,tbl_consultation c
				WHERE dc.consultation_ID = c.consultation_ID
				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
				AND dc.diagnosis_type='Diagnosis' limit 1) as diagnosis,
		  (Select MAX(ic.Payment_Item_Cache_List_ID)
				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
				AND c.consultation_ID = pc.consultation_ID
				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
				AND ic.Check_In_Type='Radiology' limit 1) as radiology
		  FROM tbl_patient_registration pr,tbl_sponsor s,
		  tbl_patient_payment_item_list ppl,tbl_consultation c,tbl_consultation_history ch,
		  tbl_patient_payments pp
		  WHERE
		  pr.Registration_ID = c.Registration_ID AND
		  pr.Sponsor_ID = s.Sponsor_ID AND
                   ch.Consultation_ID=c.Consultation_ID AND
		  c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND
		  ch.employee_ID =".$_SESSION['userinfo']['Employee_ID']." AND
		  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND
		  pp.Registration_ID = pr.Registration_ID AND
		  pp.Branch_ID = ".$_SESSION['userinfo']['Branch_ID']." AND
                  ppl.Process_Status NOT IN ('no show','signedoff') AND  
                  ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')
		  GROUP BY pp.Patient_Payment_ID";
		  
		  
    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    $i=1;
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	if($row['laboratory']>0){
	    $laboratory = 'Yes';
	}else{
	    $laboratory = 'No';
	}
	if($row['_procedure']>0){
	    $procedure = 'Yes';
	}else{
	    $procedure = 'No';
	}
	if($row['treatment']>0){
	    $treatment = 'Yes';
	}else{
	    $treatment = 'No';
	}
	if($row['sugery']>0){
	    $sugery = 'Yes';
	}else{
	    $sugery = 'No';
	}
	if($row['radiology']>0){
	    $radiology = 'Yes';
	}else{
	    $radiology = 'No';
	}
	if($row['diagnosis']>0){
	    $diagnosis = 'Yes';
	}else{
	    $diagnosis = 'No';
	}
	echo "<tr><td id='thead'><center>$i</center></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['consulted_date']."</a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
	echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'><center>".$diagnosis."</center></a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'><center>".$procedure."</center></a></td>";
	echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'><center>".$sugery."</center></a></td>";
	echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'><center>".$treatment."</center></a></td>";
	echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'><center>".$radiology."</center></a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' target='_parent' style='text-decoration: none;'><center>".$laboratory."</center></a></td>";
	if($diagnosis=='Yes'){
	?>
    <td>
	<input type='button' value='SIGN OFF' class='art-button-green'
	       onclick="patientsignoff('<?php echo $row['Registration_ID']; ?>','<?php echo $row['Patient_Payment_ID']; ?>','<?php echo str_replace("'", '', $row['Patient_Name']) ?>')">
    </td>
	<?php
	}else{
	?>
	<td>
	    <input type='button' value='SIGN OFF' class='art-button-green' onclick="alert('You Cannot SignOff A Patient Without A Final Diagnosis.\nPlease Specify It And Perform This Action. ')">
	</td>
	<?php    
	}
	$i++;
    }
    echo "</tr></table></center>";
?>