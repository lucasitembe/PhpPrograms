<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);   
    }else{
        $Patient_Name = '';
    }

    if(isset($_GET['Registration_Number'])){
	$Registration_Number = $_GET['Registration_Number'];
    }else{
	$Registration_Number = '';
    }

	if(isset($_GET['date_From'])){
		$date_From = $_GET['date_From'];
	}else{
		$date_From = '';
	}


	if(isset($_GET['date_To'])){
		$date_To = $_GET['date_To'];
	}else{
		$date_To = '';
	}

	if(isset($_GET['Patient_Number'])){
		$Patient_Number = $_GET['Patient_Number'];
	}else{
		$Patient_Number = '';
	}

	//Get Employee_ID
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }

$sql="";
    //get billing type 
    if(isset($_GET['Billing_Type'])){
	$Billing_Type = $_GET['Billing_Type'];
	if($Billing_Type == 'OutpatientCash'){
	    //$Temp_Billing_Type = 'Outpatient Cash';
	    //$sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit') and ilc.Transaction_Type = 'cash'";
//            $sql = " (pc.billing_type = 'Outpatient Cash') and ilc.Transaction_Type = 'cash' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others')";
            $sql .= "pc.billing_type = 'Outpatient Cash'";
            $Transaction_Type='cash';
//            $sql2 = " (pc.billing_type = 'Outpatient Cash') and ilc.Transaction_Type = 'cash' AND Check_In_Type='Others'";

            
        }elseif($Billing_Type == 'OutpatientCredit'){
	    //$Temp_Billing_Type = 'Outpatient Credit';
	   // $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit') and ilc.Transaction_Type = 'credit'";
//            $sql = " (pc.billing_type = 'Outpatient Credit' or ilc.Transaction_Type = 'credit') AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others')";
//            $sql2 = " (pc.billing_type = 'Outpatient Credit' or ilc.Transaction_Type = 'credit') AND Check_In_Type='Others'";
            $sql .= "pc.billing_type = 'Outpatient Credit'";
            $Transaction_Type='credit';
	}elseif($Billing_Type == 'InpatientCash'){
	    //$Temp_Billing_Type = 'Inpatient Cash';
            // $sql = " (pc.billing_type = 'inpatient Cash' or pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'cash'";

//            $sql = " (pc.billing_type = 'inpatient Cash') and ilc.Transaction_Type = 'cash' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others')";
//            $sql2 = " (pc.billing_type = 'inpatient Cash') and ilc.Transaction_Type = 'cash' AND Check_In_Type='Others'";
            
            $sql .= "pc.billing_type = 'inpatient Cash'";
            $Transaction_Type='cash';
	}elseif($Billing_Type == 'InpatientCredit'){
	    //$Temp_Billing_Type = 'Inpatient Credit';
            //$sql = " (pc.billing_type = 'inpatient Cash' or pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'credit'";
//            $sql = " (pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'credit' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others')";
//            $sql2 = " (pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'credit' AND Check_In_Type='Others'";
            $sql .= "pc.billing_type = 'inpatient Credit'";
            $Transaction_Type='credit';
	}elseif($Billing_Type == 'PatientFromOutside'){
	    //$Temp_Billing_Type = 'Patient From Outside';
//	    $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others'))";
//	    $sql2 = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND Check_In_Type='Others')";
            $sql .= "(pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit')";
            $Transaction_Type="(credit or cash)";
	}else{
	    //$Temp_Billing_Type = '';
//	    $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others'))";
//	    $sql2 = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND Check_In_Type='Others')";
            $sql .= "(pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit')";
            $Transaction_Type="(credit or cash)";
	}
    }else{
        $Billing_Type = '';
        //$Temp_Billing_Type = '';
//	$sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND (Check_In_Type='Pharmacy' OR Check_In_Type='Others'))";
//	$sql2 = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit' AND Check_In_Type='Others')";
        
        $sql .= "(pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit')";
        $Transaction_Type="(credit or cash)";
    }

    if($date_To != null && $date_To != '' && $date_From != null && $date_From != ''){
    	$sql .= "AND pc.Payment_Date_And_Time between '$date_From' and '$date_To'";
    }

    if(isset($_SESSION['systeminfo']['Filtered_Pharmacy_Patient_List']) && strtolower($_SESSION['systeminfo']['Filtered_Pharmacy_Patient_List']) == 'no' and $date_From == '' and $date_To == '' and $date_From == null and $date_To == null){
    	$Filter = 'limit 10';
    	if(isset($_GET['Patient_Name'])){ $Filter = 'limit 10'; }
    }else{
    	$Filter = 'limit 200';
    }
    
    
    ?>
    <?php
    //get sub department id
    if(isset($_SESSION['Pharmacy_ID'])){
    	$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
    	$Sub_Department_ID = 0;
    }

    echo '<center><table width =100% border=0>';
    $Title = '<tr id="thead"><td style="width:4%;"><b>SN</b></td>';
    if(isset($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) && strtolower($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) == 'yes'){
		$Title .= '<td width="3%" style="text-align: center;"><b></b></td>';
	}
	$Title .= '<td width><b>STATUS</b></td>
				<td><b>PATIENT NAME</b></td>
				<td><b>PATIENT NO</b></td>
				<td><b>SPONSOR</b></td>
				<td><b>AGE</b></td>
				<td><b>GENDER</b></td>
				<td><b>TRANSACTION DATE</b></td>
				<td><b>MEMBER NUMBER</b></td></tr>';
	echo $Title;
	
	if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
		$qr="(select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, Payment_Date_And_Time,
			preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
			preg.Member_Number from
			tbl_payment_cache pc,tbl_patient_registration preg where
			
			preg.registration_id = pc.registration_id and
			preg.patient_name like '%$Patient_Name%' and
			
			".$sql." and
			pc.Registration_ID = '$Patient_Number' group by pc.Payment_Cache_ID) order by Payment_Cache_ID desc $Filter";
                          //  and
			//ilc.Sub_Department_ID = '$Sub_Department_ID' 
	}else{
//		$qr="(select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, Payment_Date_And_Time,
//			preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
//			preg.Member_Number from
//			tbl_payment_cache pc, tbl_patient_registration preg where
//			preg.registration_id = pc.registration_id and
//			preg.patient_name like '%$Patient_Name%' and
//			
//			".$sql." group by pc.Payment_Cache_ID) order by Payment_Cache_ID desc $Filter";
//                     
		$qr="(select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, Payment_Date_And_Time,
			preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
			preg.Member_Number from
			tbl_payment_cache pc, tbl_patient_registration preg where
                        preg.registration_id = pc.registration_id and
			preg.patient_name like '%$Patient_Name%' and
			
			".$sql." group by pc.Payment_Cache_ID) order by Payment_Cache_ID desc $Filter";
                     
			//ilc.Sub_Department_ID = '$Sub_Department_ID' 
	}
    
					
    
    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	//check transaction status
	    $Temp_Payment_Cache_ID = $row['Payment_Cache_ID'];
            
            $sql_select_user_items_details_result=mysqli_query($conn,"SELECT Transaction_Type,Check_In_Type FROM tbl_item_list_cache WHERE 
                status <> 'dispensed' AND
                status <> 'removed' AND
                Transaction_Type='$Transaction_Type' AND 
                Payment_Cache_ID='$Temp_Payment_Cache_ID' AND   
                (Check_In_Type='Pharmacy' OR Check_In_Type='Others')
                ") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_user_items_details_result)){
                $contro_repeatition=0;
                while($rows_details=mysqli_fetch_assoc($sql_select_user_items_details_result)){
                    if($contro_repeatition>0)break;$contro_repeatition++;
	    $Temp_Transaction_Type = $rows_details['Transaction_Type'];
	    $Check_In_Type = $rows_details['Check_In_Type'];
	
	    $select_medication = "select status,Check_In_Type from tbl_item_list_cache where
				    payment_cache_id = '$Temp_Payment_Cache_ID' and
					Transaction_Type = '$Temp_Transaction_Type' and
                                             Check_In_Type='$Check_In_Type' and 
					    status = ";
	    //check for dispensed
	    $Check_Status = $select_medication."'dispensed'";
            
            $sql_select_all_medicine_with_payment_cach_id="select status from tbl_item_list_cache where
				    payment_cache_id = '$Temp_Payment_Cache_ID' and
					Transaction_Type = '$Temp_Transaction_Type' and Check_In_Type='$Check_In_Type'";
           $sql_select_all_medicine_with_payment_cach_id_result=mysqli_query($conn,$sql_select_all_medicine_with_payment_cach_id) or die(mysqli_error($conn));
            $count_medicine =mysqli_num_rows($sql_select_all_medicine_with_payment_cach_id_result);
            $count_match=0;
            if($count_medicine>0){
               while($medicine_rows=mysqli_fetch_assoc($sql_select_all_medicine_with_payment_cach_id_result)){
                  $selected_status=$medicine_rows['status'];
                  if($selected_status=="dispensed"){
                     $count_match++; 
                  }
               } 
            }
            
        //    echo "$count_medicine = $count_match -->$Temp_Payment_Cache_ID";
	    $result = mysqli_query($conn,$Check_Status);
	    $no = mysqli_num_rows($result);
            if($count_medicine==$count_match){
	   // if($no > 0){
		  //check for not yet approved
			$Check_Status = $select_medication."'active'";
			$result = mysqli_query($conn,$Check_Status);
			$no = mysqli_num_rows($result);
			if($no > 0){
			    $Medication_Status = 'Not Yet Approved';
			}else{
			    //Something is wrong
			    $Medication_Status = 'Dispensed';
			}
		//$Medication_Status = 'Dispensed';
	    }else{
		//check for paid
		$Check_Status = $select_medication."'paid'";
		$result = mysqli_query($conn,$Check_Status);
		$no = mysqli_num_rows($result);
		if($no > 0){
		   // $Medication_Status = 'Paid';
			//check for not yet approved
			$Check_Status = $select_medication."'active'";
			$result = mysqli_query($conn,$Check_Status);
			$no = mysqli_num_rows($result);
			if($no > 0){
			    $Medication_Status = 'Not Yet Approved';
			}else{
			    //Something is wrong
			    $Medication_Status = 'Paid';
			}
		}else{
		    //check for approved
		    $Check_Status = $select_medication."'approved'";
		    $result = mysqli_query($conn,$Check_Status);
		    $no = mysqli_num_rows($result);
		    if($no > 0){
			   //$Medication_Status = 'Approved';
			  //check for not yet approved
				$Check_Status = $select_medication."'active'";
				$result = mysqli_query($conn,$Check_Status);
				$no = mysqli_num_rows($result);
				if($no > 0){
					$Medication_Status = 'Not Yet Approved';
				}else{
					//Something is wrong
					$Medication_Status = 'Approved';
				}
		    }else{
			//check for not yet approved
			$Check_Status = $select_medication."'active'";
			$result = mysqli_query($conn,$Check_Status);
			$no = mysqli_num_rows($result);
			if($no > 0){
			    $Medication_Status = 'Not Yet Approved';
			}else{
			    //Something is wrong
			    $Medication_Status = 'Unknown';
			}
		    }
		}
	    }
          if($Medication_Status == 'Unknown'|| $Medication_Status=='Dispensed'){}else{
              if($Check_In_Type=="Others"){
                    $change_color="style='background:green'";
                    $change_color2="color:#FFFFFF";
                }else{
                  $change_color="";
                  $change_color2="";
                }
        echo "<tr $change_color><td style='$change_color2'>".$temp."</td>";
		$Transaction_Type = $rows_details['Transaction_Type'];
		$Payment_Cache_ID = $row['Payment_Cache_ID'];
		$Registration_ID = $row['Registration_ID'];
    	if(isset($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) && strtolower($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) == 'yes'){
	    	if(isset($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) && strtolower($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) == 'yes' && ((strtolower($Medication_Status) == 'approved' && strtolower($Transaction_Type) != 'cash') || strtolower($Medication_Status) == 'paid')){
	    		//check if selected
	    		$slct = mysqli_query($conn,"select Dispense_Cache_ID from tbl_multi_dispense_cache where Registration_ID = '$Registration_ID' and
			    						Transaction_Type = '$Transaction_Type' and Employee_ID = '$Employee_ID' and Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
	    		$nm = mysqli_num_rows($slct);
?>
				<td style='text-align: center;'>
					<input type='checkbox' id="<?php echo $temp; ?>" <?php if($nm > 0){ echo "checked='checked'"; } ?> onclick = "Select_Transaction(<?php echo $temp; ?>,<?php echo $row['Registration_ID']; ?>,'<?php echo $rows_details['Transaction_Type']; ?>',<?php echo $row['Payment_Cache_ID']; ?>)">
				</td>
<?php
	    	}else{
	        	echo "<td></td>";
	    	}
	    }
	echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$rows_details['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type' target='_parent' style='text-decoration: none;$change_color2'><b>".$Medication_Status."</b></a></td>"; 
	
	//GENERATE PATIENT YEARS, MONTHS AND DAYS
	$age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years"; 		
	$date1 = new DateTime($Today);
	$date2 = new DateTime($row['Date_Of_Birth']);
	$diff = $date1 -> diff($date2);
	$age = $diff->y." Years, ";
	$age .= $diff->m." Months, ";
	$age .= $diff->d." Days";
        
       
	echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$rows_details['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type' target='_parent' style='text-decoration: none;$change_color2'>".ucwords(strtolower($row['Patient_Name']))."</a></td>"; 
	echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$rows_details['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type' target='_parent' style='text-decoration: none;$change_color2'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$rows_details['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type' target='_parent' style='text-decoration: none;$change_color2'>".$row['Sponsor_Name']."</a></td>";
        echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$rows_details['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type' target='_parent' style='text-decoration: none;$change_color2'>".$age."</a></td>";
        echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$rows_details['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type' target='_parent' style='text-decoration: none;$change_color2'>".$row['Gender']."</a></td>";
        echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$rows_details['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type' target='_parent' style='text-decoration: none;$change_color2'>".@date("d F Y H:i:s",strtotime($row['Payment_Date_And_Time']))."</a></td>";
        echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$rows_details['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type' target='_parent' style='text-decoration: none;$change_color2'>".$row['Member_Number']."</a></td>";
        //echo "<td><a href='pharmacyworkspage.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
	echo "</tr>"; 
	$temp++;
	if($temp%21 == 0){
		echo $Title;
	}
     //
    }
        //
    }}
    }
?>

</table></center>

<script type="text/javascript">
    function Select_Transaction(temp,Registration_ID,Transaction_Type,Payment_Cache_ID){
    	var Status = 'remove';
    	if(document.getElementById(temp).checked){
    		Status = 'add';
    	}
        if (window.XMLHttpRequest) {
            myObjectGetSelected = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetSelected = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetSelected.overrideMimeType('text/xml');
        }
        myObjectGetSelected.onreadystatechange = function () {
            DataGetSel = myObjectGetSelected.responseText;
            if (myObjectGetSelected.readyState == 4) {
                
            }
        }; //specify name of function that will handle server response........
        myObjectGetSelected.open('GET', 'Multi_Dispense_Get_Selected_Transaction.php?Registration_ID='+Registration_ID+'&Transaction_Type='+Transaction_Type+'&Payment_Cache_ID='+Payment_Cache_ID+'&Status='+Status, true);
        myObjectGetSelected.send();
    }
</script>