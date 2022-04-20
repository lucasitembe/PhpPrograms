<?php
    include("./includes/connection.php");
	$temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    echo '<center><table width =100% border=1>';
	
    echo '<tr><td width=5%><b>SN</b></td>
	       <td><b>PATIENT NAME</b></td>
              
                                
								</tr>';
    $select_Filtered_Donors = mysql_query(
            "select Registration_ID,Patient_Name, Date_Of_Birth, Gender,Phone_Number,Registration_Date_And_Time
		from tbl_patient_registration where status <> 'Donor' and Patient_Name like '%$Patient_Name%' ORDER BY Registration_Date_And_Time ASC") or die(mysql_error());

		    
    while($row = mysql_fetch_array($select_Filtered_Donors)){
	echo "<tr><td><a href='bloodissue.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$temp."</a></td>";
        echo "<td><a href='bloodissue.php?Registration_ID=".$row['Registration_ID']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";

        
       $temp++; 
    }   echo "</tr>";
?></table></center>

