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
            "select * from tbl_patient_registration pr , tbl_admission ad,tbl_check_in ci, tbl_sponsor sp where
		pr.Registration_ID = ad.Registration_ID  and
		    sp.Sponsor_ID = pr.Sponsor_ID and
                        ci.Registration_ID=pr.Registration_ID AND
			ci.branch_id = '$Branch_ID' and
                        ci.Check_In_Status='saved' and    
			    ad.admission_status IN ('Admitted','pending') GROUP BY pr.Registration_ID ") or die(mysqli_error($conn));
		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        
		echo "<tr><td id='thead'>".$temp."</td><td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
		$temp++;
		
    }   echo "</tr>";
?></table></center>

