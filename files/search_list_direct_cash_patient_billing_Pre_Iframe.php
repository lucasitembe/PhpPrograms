<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    $temp=1;
    
    //Get branch ID to filter list of patients
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    
    
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead" ><td style="width:5%;">SN</td><td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>DATE OF BIRTH</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,
            "select * from tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
		pr.registration_id = ci.registration_id and Check_In_Date = '$Today' and
		    sp.sponsor_id = pr.sponsor_id and
			ci.branch_id = '$Branch_ID' and
			    ci.check_in_status = 'saved'") or die(mysqli_error($conn));
		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        $select_admission = "SELECT admission_status FROM tbl_admission WHERE Registration_ID = ".$row['Registration_ID']."
	ORDER BY admision_id DESC LIMIT 1";
	$output = mysqli_query($conn,$select_admission);
	 if(mysql_numrows($output)>0){
	    $outputrow = mysqli_fetch_assoc($output);
	    if($outputrow['admission_status']!='Admitted'){
		echo "<tr><td id='thead'>".$temp."</td><td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
		$temp++;
		}
	}else{
	    echo "<tr><td id='thead'>".$temp."</td><td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
		echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
		$temp++;
	}
    }   echo "</tr>";
?></table></center>

