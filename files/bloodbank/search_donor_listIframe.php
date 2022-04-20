<link rel="stylesheet" href="table.css" media="screen"> 

<?php
    include("./includes/connection.php");
	$temp=1;
    if(isset($_GET['Donor_Name'])){
        $Donor_Name = $_GET['Donor_Name'];   
    }else{
        $Donor_Name = '';
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead">
		<td width=2%><span style="font-size: small;"><b>SN</b></td>
	        <td width=30%><span style="font-size: small;"> <b>DONOR NAME</b></td>
		<td width=15%><span style="font-size: small;"><b>DATE OF BIRTH</b></td>
                <td width=15%><span style="font-size: small;"><b>GENDER</b></td>
                <td width=20%><span style="font-size: small;"><b>PHONE NUMBER</b></td>
                                
	</tr>';
	   
	    $select_Filtered_Donors = mysqli_query($conn,
            "select Registration_ID,Patient_Name, Date_Of_Birth, Gender,Phone_Number,Registration_Date_And_Time
		from tbl_patient_registration
		    where Patient_Name like '%$Donor_Name%'
			ORDER BY Registration_Date_And_Time ASC") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Donors)){
	    echo "<tr><td><a href='blooddonordata2.php?Registration_ID=".$row['Registration_ID']."&PatientAppointment=PatientAppointmentThisForm' target='_parent' style='text-decoration: none;'>".$temp."</a></td>";
        echo "<td><a href='blooddonordata2.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
        echo "<td><a href='blooddonordata2.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
        echo "<td><a href='blooddonordata2.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='blooddonordata2.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
       $temp++; 
    }   echo "</tr>";
?></table></center>

