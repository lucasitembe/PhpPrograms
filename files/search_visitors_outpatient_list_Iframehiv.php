<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
	
	//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
	//end

	
   echo '<center><table width =100%>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td><td><b>PATIENT NAME</b></td>
            <td><b>PATIENT NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>AGE</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td><td><b>ACTION</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id order by Registration_ID Desc limit 500") or die(mysqli_error($conn));

			   
	  
		

		   
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	
	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
	
        echo "<tr><td width ='2%' id='thead'>".$temp."<td><a href='powercharts_antinatal_real.php?pn=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
	echo "<td><a href='powercharts_antinatal_real.php?pn=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='powercharts_antinatal_real.php?pn=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='powercharts_antinatal_real.php?pn=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        echo "<td><a href='powercharts_hiv.php?ctcno".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='powercharts_hiv.php?ctcno=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='powercharts_hiv.php?ctcno=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
		echo "<td><a href='powercharts_hiv.php?ctcno=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>Add to HIV</a></td>";
	$temp++;
    }   echo "</tr>";
?></table></center>

