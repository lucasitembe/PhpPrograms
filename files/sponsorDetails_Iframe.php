<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>
<center>
            <?php
		echo '<center><table width =100% border=0>';
		echo "<tr id='thead'>
                <td style='text-align:left;width=3%'><b>SN</b></td>
                <td style='width:30%'><b>PATIENT NAME</b></td>
                <td style='text-align: left;' width=10%><b>PATIENT NUMBER</b></td>
                <td style='text-align: left;' width=10%><b>REGION</b></td>
		<td style='text-align: left;' width=10%><b>DISTRICT</b></td>
		<td style='text-align: left;' width=5%><b>GENDER</b></td>
		<td style='text-align: left;' width=10%><b>AGE</b></td>
		<td style='text-align: left;' width=30%><b>REGISTRATION DATE</b></td>
		</tr>";
	   echo "<tr>
                <td colspan=4></td></tr>";
		$sponsorID=mysqli_real_escape_string($conn,$_GET['sponsorID']);
    //run the query to select all data from the database according to the branch id
    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE Sponsor_ID='$sponsorID' ORDER BY Registration_Date DESC");
    
    $res=mysqli_num_rows($select_patient);
    for($i=0;$i<$res;$i++){
	$row=mysqli_fetch_array($select_patient);
	//return rows
	$registration_ID=$row['Registration_ID'];
	$patientName=$row['Patient_Name'];
	$Registration_ID=$row['Registration_ID'];
	$Region=$row['Region'];
	$District=$row['District'];
	$Gender=$row['Gender'];
	$dob=$row['Date_Of_Birth'];
	$Registration_Date_And_Time=$row['Registration_Date_And_Time'];
	
	//these codes are here to determine the age of the patient
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($dob);
	$diff = $date1 -> diff($date2);
	$age = $diff->y." Years, ";
	$age .= $diff->m." Months, ";
	$age .= $diff->d." Days";
	
	echo "<tr><td>".($i+1)."</td>";
        echo "<td style='text-align:left; width:10%'>".$patientName."</td>";
	echo "<td style='text-align:left; width:10%'>".$Registration_ID."</td>";
	echo "<td style='text-align:left; width:10%'>".$Region."</td>";
	echo "<td style='text-align:left; width:10%'>".$District."</td>";
	echo "<td style='text-align:left; width:10%'>".$Gender."</td>";
	echo "<td style='text-align:left; width:15%'>".$age."</td>";
	echo "<td style='text-align:left; width:10%'>".$Registration_Date_And_Time."</td>";
    }
	    ?></table></center>
        </center>