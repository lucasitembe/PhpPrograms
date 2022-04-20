<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	
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
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
?>
<legend align="right">PATIENT SPECTACLES REPORT</legend>
    <table width="100%" class="table table-striped table-hover">
        <thead style="background-color:bdb5ac">
        <!-- bdb5ac -->
        <!-- e6eded -->
        <!-- a3c0cc -->
            <tr>
                <th style="text-align:left;"><b>SN</b></th>
                <th style="text-align:left;"><b>PATIENT NAME</b></th>
                <th style="text-align:left;"><b>PATIENT NUMBER</b></th>
                <th style="text-align:left;"><b>SPONSOR</b></th>
                <th style="text-align:left;"><b>AGE</b></th>
                <th style="text-align:left;"><b>GENDER</b></th>
                <th style="text-align:left;"><b>PHONE NUMBER</b></th>
                <th style="text-align:left;"><b>CLINIC</b></th>
                <th style="text-align:left;"><b>ITEM</b></th>
                <th style="text-align:left;"><b>DISPENSED DATE</b></th>
                <th style="text-align:left;"><b>DISPENSED BY</b></th>
            </tr>
        </thead>

<?php
    if(isset($_SESSION['Optical_info'])){
        $Sub_Department_ID = $_SESSION['Optical_info'];
    }else{
        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
    }  
	if($Sponsor_ID == 0){
		$select = mysqli_query($conn,"SELECT pc.Registration_ID,it.Product_Name, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
                preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
                preg.Member_Number, ilc.Transaction_Type,ilc.Dispense_Date_Time,emp.Employee_Name  from
                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd,tbl_items it,tbl_employee emp where
                pc.payment_cache_id = ilc.payment_cache_id and
                sd.Sub_Department_ID = ilc.Sub_Department_ID and
                preg.registration_id = pc.registration_id and
                sp.Sponsor_ID = preg.Sponsor_ID and
                ilc.status = 'dispensed' and it.Item_ID=ilc.Item_ID and
                ilc.Check_In_Type = 'Optical' and emp.Employee_ID=ilc.Dispensor and
                ilc.Sub_Department_ID = '$Sub_Department_ID' and (Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and (ilc.Transaction_Type = 'Cash' or ilc.Transaction_Type = 'Credit') and
                ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date'
                group by pc.payment_cache_id order by pc.Payment_Cache_ID") or die(mysqli_error($conn));
	}else{
		$select = mysqli_query($conn,"SELECT pc.Registration_ID,it.Product_Name, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
                preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
                preg.Member_Number, ilc.Transaction_Type,ilc.Dispense_Date_Time,emp.Employee_Name from
                tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd,tbl_items it,tbl_employee emp where
                pc.payment_cache_id = ilc.payment_cache_id and
                sd.Sub_Department_ID = ilc.Sub_Department_ID and
                preg.registration_id = pc.registration_id and
                sp.Sponsor_ID = preg.Sponsor_ID and sp.Sponsor_ID = '$Sponsor_ID' and
                ilc.status = 'dispensed' and it.Item_ID=ilc.Item_ID and
                ilc.Check_In_Type = 'Optical' and emp.Employee_ID=ilc.Dispensor and 
                ilc.Sub_Department_ID = '$Sub_Department_ID' and (Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and (ilc.Transaction_Type = 'Cash' or ilc.Transaction_Type = 'Credit') and
                ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date'
                group by pc.payment_cache_id order by pc.Payment_Cache_ID") or die(mysqli_error($conn));
    }
    
	$num = mysqli_num_rows($select);
	if($num > 0){
        $temp = 0;
		while ($data = mysqli_fetch_array($select)) {
            
			$no = mysqli_num_rows($select_details);
			if($no > 0){
				while ($row = mysqli_fetch_array($select_details)) {
					$date1 = new DateTime($Today);
					$date2 = new DateTime($data['Date_Of_Birth']);
					$diff = $date1 -> diff($date2);
					$age = $diff->y." Years, ";
					$age .= $diff->m." Months, ";
					$age .= $diff->d." Days";
					$Registration_ID = $data['Registration_ID'];
                    $Dispense_date = $data['Dispense_Date_Time'];
                    $Payment_Cache_ID = $data['Payment_Cache_ID'];
                    $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
                    
                    $select_item = mysqli_query($conn,"SELECT it.Product_Name from
                    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_sub_department sd,tbl_items it where
                    pc.payment_cache_id = ilc.payment_cache_id and  sd.Sub_Department_ID = ilc.Sub_Department_ID and ilc.status = 'dispensed' and it.Item_ID=ilc.Item_ID and  ilc.Check_In_Type = 'Optical' and 
                    ilc.Sub_Department_ID = '$Sub_Department_ID' AND pc.Registration_ID='$Registration_ID' AND DATE(Dispense_Date_Time)=DATE('$Dispense_date') AND ilc.Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
                    
                   $product ="";
                   while($row_item=mysqli_fetch_assoc($select_item)){

                        $pname=$row_item['Product_Name'];
                        $product.=$pname."</br> ";
                    }

					echo "<tr>";
					echo "<td style='text-align:left'>".++$temp."</td>";
					echo "<td>".ucwords(strtolower($data['Patient_Name']))."</td>";
					echo "<td>".$data['Registration_ID']."</td>";
					echo "<td>".$data['Guarantor_Name']."</td>";
					echo "<td>".$age."</td>";
					echo "<td>".$data['Gender']."</td>";
					echo "<td>".$data['Phone_Number']."</td>";
					echo "<td>".$data['Sub_Department_Name']."</td>";
					echo "<td>".$product."</td>"; 
					echo "<td>".$data['Dispense_Date_Time']."</td>";
					echo "<td>".$data['Employee_Name']."</td>";
					echo "</tr>";
				}
			}
?>
			<tr>
		        <td><?php echo ++$temp; ?></td>
		        <td><?php echo $data['Patient_Name']; ?></td>
		        <td style="text-align: left;"><?php echo $data['Registration_ID']; ?></td>
		        <td style="text-align: left;"><?php echo $data['Guarantor_Name']; ?></td>
		        <td style="text-align: left;"><?php echo $age; ?></td>
		        <td style="text-align: left;"><?php echo $data['Gender']; ?></td>
		        <td style="text-align: left;"><?php echo $data['Phone_Number']; ?></td>
		        <td style="text-align: left;"><?php echo $data['Sub_Department_Name'];?></td>
		        <td style="text-align: left;"><?php echo $data['Product_Name'];?></td>
		        <td style="text-align: left;"><?php echo $data['Dispense_Date_Time'];?></td>
		        <td style="text-align: left;"><?php echo $data['Employee_Name'];?></td>
		    </tr>
<?php
		}
?>
<?php
	}
?>
</table>
<br/><br/><br/>