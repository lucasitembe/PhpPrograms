<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    if(isset($_GET['Donor_Name'])){
        $Donor_Name = $_GET['Donor_Name'];   
    }else{
        $Donor_Name = '';
    }
    echo '<center>
	    <table width =100% border=0>';
	echo '<tr id="thead" ><td><b>DONOR NAME</b></td>
		   <td><b>DATE OF BIRTH</b></td>
		    <td><b>GENDER</b></td>
		    <td><b>PHONE NUMBER</b></td>
                                
		</tr>';
	    $select_Filtered_Donors = mysqli_query($conn,
            "select DISTINCT Registration_ID,Patient_Name, Date_Of_Birth, Gender,Phone_Number
		from tbl_patient_registration,tbl_patient_blood_data
		    where Registration_ID=Donor_ID and Patient_Name like '%$Donor_Name%'
			ORDER BY Registration_Date_And_Time ASC") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Donors)){
        echo "<tr><td><a href='blooddonordata.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</td>";
        echo "<td><a href='blooddonordata.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</td>";
        echo "<td><a href='blooddonordata.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</td>";
        echo "<td><a href='blooddonordata.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</td>";
        
    }   echo "</tr>";
?></table></center>

