<?php
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    
    
    echo '<center><table width =100% border=1>';
    echo '<tr><td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>DATE OF BIRTH</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,
            "select * from tbl_patient_registration pr , tbl_admission ad, tbl_sponsor sp where
                    ad.Registration_ID = pr.registration_id and
		    pr.sponsor_id = sp.sponsor_id and
		    ad.Admission_Status = 'Admitted'") or die(mysqli_error($conn));
		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
        echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
        echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='patientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
    }   echo "</tr>";
?></table></center>

