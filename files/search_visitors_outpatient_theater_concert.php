<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
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
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td><td><b>COSTUMER NAME</b></td>
            <td><b>COSTUMER NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>AGE</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name,pr.Old_Registration_Number,  pr.Registration_ID, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id order by Registration_ID Desc limit 200") or die(mysqli_error($conn));

			   
	  
		

		   
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	
	//AGE FUNCTION
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
	
        echo "<tr><td width ='2%' id='thead'>".$temp."<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
    echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>";echo ($row['Old_Registration_Number']==""?$row['Registration_ID']:$row['Old_Registration_Number']);   echo "</a></td>";
        echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
	$temp++;
    }   echo "</tr>";
?></table></center>


