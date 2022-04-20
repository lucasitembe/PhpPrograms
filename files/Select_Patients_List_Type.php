<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['List_Type'])){
        $List_Type = $_GET['List_Type'];
    }else{
        $List_Type = '';
    }
    
     //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
	$Employee_ID = 0;
    }
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Age = $Today - $original_Date; 
    }
?>
<style>
    table,tr,td{
    border-collapse:collapse !important;
    border:none !important;
    
    }
    tr:hover{
    background-color:#eeeeee;
    cursor:pointer;
    }
 </style> 
    <table width =100% border=0>
	<tr><td colspan="7"><hr></td></tr>
	<tr ID="thead">
	    <td style="width:5%;"><b>SN</b></td>
	    <td><b>PATIENT NAME</b></td>
	    <td width=10%><b>PATIENT NO</b></td>
	    <td width=18%><b>AGE</b></td>
	    <td width=8%><b>GENDER</b></td>
	    <td width=15%><b>SPONSOR</b></td>
	    <td width=12%><b>VISITED DATE</b></td>
	</tr>
	<tr><td colspan="7"><hr></td></tr>
<?php
    if($List_Type == 'Direct To Doctor'){
        $select_Filtered_Patient =
            mysqli_query($conn,"SELECT * FROM tbl_patient_payments pp,
                tbl_patient_payment_item_list ppl,
                tbl_patient_registration pr,
                tbl_sponsor sp,
                tbl_check_in ci WHERE
                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pr.Registration_ID = pp.Registration_ID and
                pp.Sponsor_ID = sp.Sponsor_ID and
                ci.Check_In_ID = pp.Check_In_ID and
                ppl.Consultant_ID = '$Employee_ID' and
                ppl.Status = 'active' and
                DATE(ci.Check_In_Date_And_Time) = CURDATE() and
                Check_In_Type = 'doctor room' and
                Patient_Direction = 'Direct To Doctor Via Nurse Station'
                ORDER BY Patient_Payment_Item_List_ID desc") or die(mysqli_error($conn));   
    }else if($List_Type == 'Direct To Clinic'){
        $select_Filtered_Patient =
            mysqli_query($conn,"SELECT * FROM tbl_patient_payments pp,
                tbl_patient_payment_item_list ppl,
                tbl_patient_registration pr,
                tbl_sponsor sp,
                tbl_clinic_employee ce,
                tbl_check_in ci WHERE
                ce.Clinic_ID = ppl.Clinic_ID and
                ce.Employee_ID = '$Employee_ID' and
                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pr.Registration_ID = pp.Registration_ID and
                pp.Sponsor_ID = sp.Sponsor_ID and
                ci.Check_In_ID = pp.Check_In_ID and
                ppl.Status = 'active' and
                Check_In_Type = 'doctor room' and
                DATE(ci.Check_In_Date_And_Time) = CURDATE() and
                Patient_Direction = 'Direct To Clinic Via Nurse Station'
                ORDER BY Patient_Payment_Item_List_ID desc") or die(mysqli_error($conn));   
    }
    
    $temp = 1;
    while($row = mysqli_fetch_array($select_Filtered_Patient)){
	
	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
    ?>
	    <tr>
		<td id='thead'><?php echo $temp; ?></td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo ucwords(strtolower($row['Patient_Name'])); ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $row['Registration_ID']; ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $age; ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $row['Gender']; ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $row['Guarantor_Name']; ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $row['Check_In_Date_And_Time']; ?></a>
		</td>

    <?php
	$temp++;
	echo "</tr>";
    }
?>
</table>