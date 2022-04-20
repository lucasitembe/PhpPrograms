<!--<link rel="stylesheet" href="table.css" media="screen">-->

<?php
    @session_start();
    include("./includes/connection.php");
    $filter='';
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }
    if(isset($_GET['Search_Patient_number'])){
        $Search_Patient_number = $_GET['Search_Patient_number'];   
    }
    
    if(isset($Patient_Name) && !empty($Patient_Name)){
        $filter=" AND pr.Patient_Name like '%$Patient_Name%'";
    }
    if(isset($Search_Patient_number) && !empty($Search_Patient_number)){
        $filter.=" AND pr.Registration_ID='$Search_Patient_number'";
    }
    
    
    //GET BRANCH ID
    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
       
    echo '<center><table width ="100%" class="hover">';
    echo "<tr id='thead'>
	    <td style='width:5%;'><b>SN</b></td>
                <td>&nbsp;</td>
		<td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>DATE CONSULTED</b></td>
                <td><b>PHONE NUMBER</b></td>
                <td><b>FINAL DIAGNOSIS</b></td>
                <td><b>PROCEDURE</b></td>
                <td><b>SUGERY</b></td>
                <td><b>TREATMENT</b></td>
                <td><b>RADIOLOGY</b></td>
                <td><b>LABORATORY</b></td>
                <td><b>ACTION</b></td>    
	</tr>";
//    $qr = "SELECT pr.Registration_ID,pr.Patient_Name,pr.Gender,pr.Date_Of_Birth,
//		  pr.Phone_Number,ppl.Patient_Payment_Item_List_ID,s.Guarantor_Name,
//		  Member_Number,Transaction_Date_And_Time,pp.Patient_Payment_ID,
//		  (SELECT c.Consultation_Date_AND_Time FROM tbl_consultation c WHERE c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID) as consulted_date,
//		  (Select MAX(ic.Payment_Item_Cache_List_ID)
//				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
//				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
//				AND c.consultation_ID = pc.consultation_ID
//				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
//				AND ic.Check_In_Type='Laboratory') as laboratory,
//		  (Select MAX(ic.Payment_Item_Cache_List_ID)
//				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
//				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
//				AND c.consultation_ID = pc.consultation_ID
//				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
//				AND ic.Check_In_Type='Procedure') as _procedure,
//		  (Select MAX(ic.Payment_Item_Cache_List_ID)
//				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
//				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
//				AND c.consultation_ID = pc.consultation_ID
//				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
//				AND ic.Check_In_Type='Sugery') as sugery,
//		  (Select MAX(ic.Payment_Item_Cache_List_ID)
//				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
//				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
//				AND c.consultation_ID = pc.consultation_ID
//				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
//				AND ic.Check_In_Type='Treatment') as treatment,
//		  (Select MAX(dc.Disease_Consultation_ID)
//				FROM tbl_disease_consultation dc,tbl_consultation c
//				WHERE dc.consultation_ID = c.consultation_ID
//				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
//				AND dc.diagnosis_type='Diagnosis') as diagnosis,
//		  (Select MAX(ic.Payment_Item_Cache_List_ID)
//				FROM tbl_item_list_cache ic,tbl_consultation c, tbl_payment_cache pc
//				WHERE pc.Payment_Cache_ID=ic.Payment_Cache_ID
//				AND c.consultation_ID = pc.consultation_ID
//				AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID
//				AND ic.Check_In_Type='Radiology') as radiology
//		  
//		  FROM tbl_patient_registration pr,tbl_sponsor s,
//		  tbl_patient_payment_item_list ppl,tbl_consultation c,
//		  tbl_patient_payments pp
//		  WHERE pr.Registration_ID = c.Registration_ID AND
//		  pr.Sponsor_ID = s.Sponsor_ID AND
//		  c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND
//		  c.Employee_ID =".$_SESSION['userinfo']['Employee_ID']." AND
//		  c.Process_Status = 'served' AND
//		  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND
//		  pp.Registration_ID = pr.Registration_ID AND
//		  ppl.Process_Status = 'served' AND
//		  pp.Branch_ID = ".$_SESSION['userinfo']['Branch_ID']." AND
//		  pr.Patient_Name like '%$Patient_Name%'
//		  GROUP BY pp.Registration_ID
//		  order by c.Consultation_Date_And_Time";
		  
		  $qr = "SELECT pr.Registration_ID,pr.Patient_Name,pr.Gender,pr.Date_Of_Birth,
		  pr.Phone_Number,ppl.Patient_Payment_Item_List_ID,pr.Sponsor_ID,
		  Member_Number,Transaction_Date_And_Time,pp.Patient_Payment_ID,cons_hist_Date,c.consultation_ID
		  
		  FROM tbl_patient_registration pr,
		  tbl_patient_payment_item_list ppl,tbl_consultation c,
		  tbl_patient_payments pp,tbl_consultation_history ch
		  WHERE
		  pr.Registration_ID = c.Registration_ID AND
                   ch.Consultation_ID=c.Consultation_ID AND
		  c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND
		  ch.employee_ID =".$_SESSION['userinfo']['Employee_ID']." AND
		  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND
		  pp.Registration_ID = pr.Registration_ID AND
		  pp.Branch_ID = ".$_SESSION['userinfo']['Branch_ID']." AND
                   ppl.Process_Status NOT IN ('no show','signedoff') AND   
                  ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')
                  $filter
		  GROUP BY pp.Patient_Payment_ID LIMIT 100";
		  
//                  $qr="";
    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
	$i=1;
        while($row = mysqli_fetch_array($select_Filtered_Patients)){
            $consultation_ID=$row['consultation_ID'];
            $Sponsor_ID=$row['Sponsor_ID'];
            $sql_chek_for_diagnosis_result=mysqli_query($conn,"SELECT diagnosis_type FROM tbl_disease_consultation WHERE consultation_ID='$consultation_ID' AND diagnosis_type='diagnosis' LIMIT 1") or die(mysqli_error($conn));
            $sql_select_sponsor_name_result=mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
        $Guarantor_Name=mysqli_fetch_assoc($sql_select_sponsor_name_result)['Guarantor_Name'];
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
	if(mysqli_num_rows($sql_chek_for_diagnosis_result)>0){
	    $diagnosis = 'Yes';
	}else{
	    $diagnosis = 'No';
	}
        echo "<tr><td id='thead'><center>$i</center></td>";
        if($diagnosis=='Yes'){
            echo "<td ><input type='checkbox' class='signedoff_check' id='".$row['Patient_Payment_ID']."'/></td>";
        }else{
          echo "<td >&nbsp;</td>";  
        }
        
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'>".$Guarantor_Name."</a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'>".$row['cons_hist_Date']."</a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
	echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'><center>".$diagnosis."</center></a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'><center>".$procedure."</center></a></td>";
	echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'><center>".$sugery."</center></a></td>";
	echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'><center>".$treatment."</center></a></td>";
	echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'><center>".$radiology."</center></a></td>";
        echo "<td><a href='clinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=yes' target='_parent' style='text-decoration: none;'><center>".$laboratory."</center></a></td>";
	if($diagnosis=='Yes'){
	?>
    <td>
	<input type='button' value='SIGN OFF' class='art-button-green'
               onclick="patientsignoff('<?php echo $row['Registration_ID']; ?>','<?php echo $row['Patient_Payment_ID']; ?>','<?php echo str_replace("'", '', $row['Patient_Name'])?>')">
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
