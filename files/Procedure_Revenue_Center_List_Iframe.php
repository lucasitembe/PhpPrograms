<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    
    if(isset($_GET['Patient_Type']) && $_GET['Patient_Type'] == 'Outpatient'){ 
        echo '<legend style="background-color:#006400;color:white" align="right"><b>'.strtoupper('Procedure Payments ~ outpatient cash').'</b></legend>';
    }else if(isset($_GET['Patient_Type']) && $_GET['Patient_Type'] == 'Inpatient'){
        echo '<legend style="background-color:#006400;color:white" align="right"><b>'.strtoupper('Procedure Payments ~ inpatient cash').'</b></legend>';
    }else {
        echo '<legend style="background-color:#006400;color:white" align="right"><b>'.strtoupper('Procedure Payments').'</b></legend>';
    }

    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];   
    }else{
        $Patient_Number = '';
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
    
    if(isset($_GET['Patient_Type'])){
        $Patient_Type = $_GET['Patient_Type'];
    }else{
        $Patient_Type = '';
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

    //overide if and only if inpatient prepaid is selected
    if(strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) =='yes'){
        if(isset($_GET['Patient_Type']) && $_GET['Patient_Type'] == 'Inpatient'){
            $Billing_Type2 = 'InpatientCash';
        }
    }

    //generate filter value
    if(isset($_GET['Patient_Name']) && $_GET['Patient_Name'] != null && $_GET['Patient_Name'] != ''){
        $filter = "preg.patient_name like '%$Patient_Name%' and";
    }else if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
        $filter = "preg.Registration_ID = '$Patient_Number' and";
    }else{
        $filter = "";
    } 

    echo '<center><table width =100% border=0>';
    echo "<tr><td colspan='10'><hr></tr>";
    echo '<tr id="thead" style="width:5%;"><td><b>SN</b></td>
            <td width="5%"><b>STATUS</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width="6%"><b>PATIENT #</b></td>
            <td><b>SPONSOR</b></td>
            <td width="14%"><b>AGE</b></td>
            <td width="6%"><b>GENDER</b></td>
            <td width="9%"><b>SUB DEPARTMENT</b></td>
            <td width="14%"><b>TRANSACTION DATE</b></td>
            <td width="9%"><b>MEMBER #</b></td>
        </tr>';
	echo "<tr><td colspan='10'><hr></tr>";
        if(strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) =='yes'){
            if(isset($_GET['Patient_Type']) && $_GET['Patient_Type'] == 'Inpatient'){
                $qr="select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, ilc.Transaction_Date_And_Time,
                    preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
                    preg.Member_Number, ilc.Transaction_Type, ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
                    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sub_department sd where
                    pc.payment_cache_id = ilc.payment_cache_id and
                    preg.registration_id = pc.registration_id and
                    preg.patient_name like '%$Patient_Name%' and
                    sd.sub_department_id = ilc.sub_department_id and
                    $filter
                    ilc.status = 'active' and
                    ilc.Transaction_Date_And_Time between '$Start_Date' and '$End_Date' and
                    ilc.Check_In_Type = 'Procedure' and
                    pc.billing_type = 'Inpatient Cash'
                    group by pc.payment_cache_id order by pc.payment_cache_id desc limit 100";
            }else{
                $qr="select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, ilc.Transaction_Date_And_Time,
                    preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
                    preg.Member_Number, ilc.Transaction_Type, ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
                    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sub_department sd where
                    pc.payment_cache_id = ilc.payment_cache_id and
                    preg.registration_id = pc.registration_id and
                    preg.patient_name like '%$Patient_Name%' and
                    sd.sub_department_id = ilc.sub_department_id and
                    $filter
                    ilc.status = 'active' and
                    ilc.Transaction_Date_And_Time between '$Start_Date' and '$End_Date' and
                    ilc.Check_In_Type = 'Procedure' and
                    pc.billing_type = 'Outpatient Cash'
                    group by pc.payment_cache_id order by pc.payment_cache_id desc limit 100";
            }
        }else{
            if(isset($_GET['Patient_Type']) && $_GET['Patient_Type'] == 'Inpatient'){
                $qr="select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, ilc.Transaction_Date_And_Time,
                    preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
                    preg.Member_Number, ilc.Transaction_Type, ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
                    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sub_department sd where
                    pc.payment_cache_id = ilc.payment_cache_id and
                    preg.registration_id = pc.registration_id and
                    preg.patient_name like '%$Patient_Name%' and
                    sd.sub_department_id = ilc.sub_department_id and
                    $filter
                    ilc.status = 'active' and
                    ilc.Transaction_Date_And_Time between '$Start_Date' and '$End_Date' and
                    ilc.Check_In_Type = 'Procedure' and
                    (pc.billing_type = 'Inpatient Cash' or pc.billing_type = 'Inpatient Credit') and
                    ilc.Transaction_Type = 'Cash' and
                    ilc.payment_type = 'pre'
                    group by pc.payment_cache_id order by pc.payment_cache_id desc limit 100";
            }else{
                $qr="select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, ilc.Transaction_Date_And_Time,
                    preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
                    preg.Member_Number, ilc.Transaction_Type, ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
                    tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sub_department sd where
                    pc.payment_cache_id = ilc.payment_cache_id and
                    preg.registration_id = pc.registration_id and
                    preg.patient_name like '%$Patient_Name%' and
                    sd.sub_department_id = ilc.sub_department_id and
                    $filter
                    ilc.status = 'active' and
                    ilc.Transaction_Date_And_Time between '$Start_Date' and '$End_Date' and
                    ilc.Check_In_Type = 'Procedure' and
                    pc.billing_type = 'Outpatient Cash'
                    group by pc.payment_cache_id order by pc.payment_cache_id desc limit 100";
            }
        }
					
    //echo $qr;
    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        $Sub_Department_ID = $row['Sub_Department_ID'];
    	echo "<tr><td id='thead' style='width:5%;' >".$temp."</td>";
    	if(strtolower($row['status']) == 'active'){
    	    echo "<td><b>Not Paid</b></td>";
    	}else{
    	    echo "<td> <b>Not Approved</b></td>";  
    	} 
	
    	//GENERATE PATIENT YEARS, MONTHS AND DAYS 		
    	$date1 = new DateTime($Today);
    	$date2 = new DateTime($row['Date_Of_Birth']);
    	$diff = $date1 -> diff($date2);
    	$age = $diff->y." Years, ";
    	$age .= $diff->m." Months, ";
    	$age .= $diff->d." Days";
	
	
    	echo "<td><a href='patientbillingprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
    	echo "<td><a href='patientbillingprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='patientbillingprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
        echo "<td><a href='patientbillingprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        echo "<td><a href='patientbillingprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='patientbillingprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Name']."</a></td>";
        echo "<td><a href='patientbillingprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
        echo "<td><a href='patientbillingprocedure.php?section=Procedure&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&ProcedureWorks=ProcedureWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
    	echo "</tr>"; 
    	$temp++;
    }
?>
</table>
</center>