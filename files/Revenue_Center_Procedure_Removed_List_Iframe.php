<!--<link rel="stylesheet" href="table.css" media="screen">--> 
<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    if(isset($_GET['Registration_Number'])){
	$Registration_Number = $_GET['Registration_Number'];
    }else{
	$Registration_Number = '';
    }
    
    
    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
    if(isset($_GET['Billing_Type'])){
	$Billing_Type2 = $_GET['Billing_Type'];
    }else{
	$Billing_Type2 = '';
    }
    

    if(isset($_GET['Billing_Type'])){
	$Billing_Type = $_GET['Billing_Type']; 
	if($Billing_Type == 'Outpatient Cash'){ 
	    //$Temp_Billing_Type = 'Outpatient Cash';
	    $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit') and ilc.Transaction_Type = 'cash'";
	}elseif($Billing_Type == 'Outpatient Credit'){
	    //$Temp_Billing_Type = 'Outpatient Credit';
	    $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit') and ilc.Transaction_Type = 'credit'";
	}elseif($Billing_Type == 'Inpatient Cash'){
	    //$Temp_Billing_Type = 'Inpatient Cash';
	    $sql = " (pc.billing_type = 'inpatient Cash' or pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'cash'";
	}elseif($Billing_Type == 'Inpatient Credit'){
	    //$Temp_Billing_Type = 'Inpatient Credit';
	    $sql = " (pc.billing_type = 'inpatient Cash' or pc.billing_type = 'inpatient Credit') and ilc.Transaction_Type = 'credit'";
	}elseif($Billing_Type == 'Patient From Outside'){
	    //$Temp_Billing_Type = 'Patient From Outside';
	    $sql = " (pc.billing_type = 'Outpatient Cashs' or pc.billing_type = 'Inpatient Cashs')";
	}else{
	    //$Temp_Billing_Type = '';
	    $sql = " (pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit')";
	}
    }else{
        $Billing_Type = '';
        //$Temp_Billing_Type = '';
	$sql = " pc.billing_type = 'Outpatient Cash' or pc.billing_type = 'Outpatient Credit'";
    }

    
    ?>
    <?php
    
    //get sub department id
    if(isset($_SESSION['Pharmacy'])){
	$Sub_Department_Name = $_SESSION['Pharmacy'];
	$select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
	while($row = mysqli_fetch_array($select_sub_department)){
	    $Sub_Department_ID = $row['Sub_Department_ID'];
	}
    }else{
	$Sub_Department_ID = '';
    }
    echo '<center><table id="removedProcedure" width =100% border=0>';
    echo '<thead><tr><td style="width:5%;"><b>SN</b></td>
	    <td width><b>STATUS</b></td>
		<td><b>PATIENT NAME</b></td>
		    <td><b>PATIENT NUMBER</b></td>
			<td><b>SPONSOR</b></td>
			    <td><b>AGE</b></td>
				<td><b>GENDER</b></td>
				    <td><b>SUB DEPARTMENT</b></td>
					<td><b>MEMBER NUMBER</b></td></tr></thead>';

    $qr="select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID,
	    preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
		preg.Member_Number, ilc.Transaction_Type, ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
		    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sub_department sd where
		        pc.payment_cache_id = ilc.payment_cache_id and
			    preg.registration_id = pc.registration_id and
				    sd.sub_department_id = ilc.sub_department_id and
					ilc.status = 'removed' and
                                            ilc.Check_In_Type = 'Procedure'
					    group by pc.payment_cache_id,ilc.sub_department_id order by pc.payment_cache_id";

    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        $Sub_Department_ID = $row['Sub_Department_ID'];
	echo "<tr><td id='thead'>".$temp."</td>";
	if(strtolower($row['status']) == 'active'){
	    echo "<td><b>Not Paid</b></td>";
	}else{
	    echo "<td> <b>Not Approved</b></td>";  
	} 
	
	//GENERATE PATIENT YEARS, MONTHS AND DAYS
	$age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years"; 		
	$date1 = new DateTime($Today);
	$date2 = new DateTime($row['Date_Of_Birth']);
	$diff = $date1 -> diff($date2);
	$age = $diff->y." Years, ";
	$age .= $diff->m." Months, ";
	$age .= $diff->d." Days";
	
	
	echo "<td><a href='patientbillingRemovedprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
	echo "<td><a href='patientbillingRemovedprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='patientbillingRemovedprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
        echo "<td><a href='patientbillingRemovedprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        echo "<td><a href='patientbillingRemovedprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='patientbillingRemovedprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Name']."</a></td>";
        echo "<td><a href='patientbillingRemovedprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
        //echo "<td><a href='patientbillinglaboratory.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&RadiologyWorks=RadiologyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
	echo "</tr>"; 
	$temp++;
    }
    ?>
</table></center>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script>
    
     $('#removedProcedure').DataTable({
        "bJQueryUI":true
    });
    
</script>