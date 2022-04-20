<?php
	session_start();
	include("./includes/connection.php");
	$temp = 1;
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = $_GET['Patient_Name'];
	}else{
		$Patient_Name = '';
	}

	if(isset($_GET['Patient_Number'])){
		$Patient_Number = $_GET['Patient_Number'];
	}else{
		$Patient_Number = 0;
	}


    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }
?>

<legend style="background-color:#006400;color:white" align="right"><b>ALL PAYMENTS ~ OUTPATIENT CASH</b></legend>
    <?php
        echo '<center><table width =100% border=0>';
        echo "<tr><td colspan='9'><hr></tr>";
        echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
                <td width><b>STATUS</b></td>
                <td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>AGE</b></td>
                <td><b>GENDER</b></td>
                <td><b>MEMBER NUMBER</b></td>
            </tr>';
        echo "<tr><td colspan='9'><hr></tr>";
        if($Sponsor_ID == 0){
        	if($Patient_Number != null && $Patient_Number != ''){
	        	$select = mysqli_query($conn,"select pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
					tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
					pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
					pc.Registration_ID = pr.Registration_ID and
					ilc.Transaction_Type = 'Cash' and
					sp.Sponsor_ID = pr.Sponsor_ID and
					pr.Registration_ID = '$Patient_Number' and
					pr.Patient_Name like '%$Patient_Name%' and
					ilc.ePayment_Status = 'pending' and
					(ilc.Status = 'active' or ilc.Status = 'approved') and
					pc.Billing_Type = 'Outpatient Cash' group by pr.Registration_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
        	}else{
	        	$select = mysqli_query($conn,"select pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
					tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
					pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
					pc.Registration_ID = pr.Registration_ID and
					ilc.Transaction_Type = 'Cash' and
					sp.Sponsor_ID = pr.Sponsor_ID and
					ilc.ePayment_Status = 'pending' and
					pr.Patient_Name like '%$Patient_Name%' and
					(ilc.Status = 'active' or ilc.Status = 'approved') and
					pc.Billing_Type = 'Outpatient Cash' group by pr.Registration_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
        	}
    	}else{
        	if($Patient_Number != null && $Patient_Number != ''){
	        	$select = mysqli_query($conn,"select pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
					tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
					pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
					pc.Registration_ID = pr.Registration_ID and
					ilc.Transaction_Type = 'Cash' and
					sp.Sponsor_ID = pr.Sponsor_ID and
					pr.Registration_ID = '$Patient_Number' and
					pr.Patient_Name like '%$Patient_Name%' and
					sp.Sponsor_ID = '$Sponsor_ID' and
					ilc.ePayment_Status = 'pending' and
					(ilc.Status = 'active' or ilc.Status = 'approved') and
					pc.Billing_Type = 'Outpatient Cash' group by pr.Registration_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
        	}else{
	        	$select = mysqli_query($conn,"select pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
					tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
					pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
					pc.Registration_ID = pr.Registration_ID and
					ilc.Transaction_Type = 'Cash' and
					sp.Sponsor_ID = pr.Sponsor_ID and
					pr.Patient_Name like '%$Patient_Name%' and
					sp.Sponsor_ID = '$Sponsor_ID' and
					ilc.ePayment_Status = 'pending' and
					(ilc.Status = 'active' or ilc.Status = 'approved') and
					pc.Billing_Type = 'Outpatient Cash' group by pr.Registration_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));
        	}
    	}

        while($row = mysqli_fetch_array($select)){        
        
            //GENERATE PATIENT YEARS, MONTHS AND DAYS
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";       
            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
            
              
            $select_items = mysqli_query($conn,"select itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity,itm.Seen_On_Allpayments, ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status
                                from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                ilc.Item_ID = itm.Item_ID and
                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                ilc.Transaction_Type = 'Cash' and
                                pc.Payment_Cache_ID = '".$row['Payment_Cache_ID']."' and
                                pc.Billing_Type = 'Outpatient Cash' and
                                ilc.ePayment_Status = 'pending' and itm.Seen_On_Allpayments='yes' order by ilc.Check_In_Type") or die(mysqli_error($conn));
            $num_rows= mysqli_num_rows($select_items);
            if($num_rows>0){
            echo "<tr><td id='thead'>".$temp.".</td><td><b>Not Paid</b></td><td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
            echo "</tr>"; 
            $temp++;
            }
        }
        echo "</table>";
    ?>