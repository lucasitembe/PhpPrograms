<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
	
    //Get Branch ID to filter patients list
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];

?>
<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
	$original_Date = $row['today'];
	$new_Date = date("Y-m-d", strtotime($original_Date));
	$Today = $new_Date;
	$age ='';
    }
     
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }

    $temp=1;
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:5%;">SN</td><td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td width=18%><b>AGE</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';
    
    
    $select_Filtered_Patients = mysqli_query($conn,
            "select * from tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
		pr.registration_id = ci.registration_id and
		    sp.sponsor_id = pr.sponsor_id and
		    ci.check_in_status = 'saved'  and Check_In_Date = '$Today' and
			ci.branch_id = '$Branch_ID' and
			Patient_Name like '%$Patient_Name%'") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	$Date_Of_Birth = $row['Date_Of_Birth'];
	$age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
	$date1 = new DateTime($Today);
	$date2 = new DateTime($Date_Of_Birth);
	$diff = $date1 -> diff($date2);
	$age = $diff->y." Years, ";
	$age .= $diff->m." Months, ";
	$age .= $diff->d." Days";
    // end of the function 
	
	
        $select_admission = "SELECT admission_status FROM tbl_admission WHERE Registration_ID = ".$row['Registration_ID']."
	ORDER BY admision_id DESC LIMIT 1";
	$output = mysqli_query($conn,$select_admission);
	 if(mysql_numrows($output)>0){
	    $outputrow = mysqli_fetch_assoc($output);
	    if($outputrow['admission_status']!='Admitted'){
		echo "<tr><td id='thead'>".$temp."</td><td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
		$temp++;
		}
	}else{
	    echo "<tr><td id='thead'>".$temp."</td><td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
		echo "<td><a href='adhocpatientbilling.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
		$temp++;
	}
    }   echo "</tr>";
?></table></center>

