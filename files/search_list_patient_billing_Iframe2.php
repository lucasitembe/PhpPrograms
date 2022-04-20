<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;

    $Patient_Name = $_GET['Patient_Name'];
    $Patient_Number = $_GET['Patient_Number'];
    $Phone_Number = $_GET['Phone_Number'];
    $Old_Phone_Number = $_GET['Old_Phone_Number'];

    $filter = '';

    if(!empty($Patient_Name)){
        $filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
    }else{
        $Patient_Name = '';
    }
    
    if(!empty($Patient_Number)){
        $filter .= " AND pr.Registration_ID = '$Patient_Number'";
    }else{
        $Patient_Number = '';
    } //

    if(!empty($Phone_Number)){
        $filter .= " AND pr.Phone_Number LIKE '%$Phone_Number%'";
    }else{
	$Phone_Number = '';
    }

    if(!empty($Old_Phone_Number)){
     $filter .= " AND pr.Old_Registration_Number = '$Old_Phone_Number'";
    }else{
	 $Old_Patient_Number = '';
    }


    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"SELECT now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    echo '<center><table width =100%>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
	    <td><b>PATIENT NAME</b></td>
            <td><b>PATIENT NUMBER</b></td>
		    <td><b>SPONSOR</b></td>
			<td><b>AGE</b></td>
			    <td><b>GENDER</b></td>
				<td><b>PHONE NUMBER</b></td>
				    <td><b>MEMBER NUMBER</b></td></tr>';

       $select_Filtered_Patients = mysqli_query($conn,
            "SELECT pr.Patient_Name,pr.Old_Registration_Number, pr.Diseased, pr.Registration_ID, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and patient_merge='Active' $filter order by pr.Registration_ID Desc limit 200") or die(mysqli_error($conn));

    if(mysqli_num_rows($select_Filtered_Patients)>0){
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
        $Diseased = $row['Diseased'];
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
        if($Diseased == 'no'){
            echo "<tr><td width ='2%' id='thead'>".$temp."</td><td><a href='visitorform.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
            echo "<td><a href='visitorform.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>";echo $row['Registration_ID'];   echo "</a></td>";;
            echo "<td><a href='visitorform.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
            echo "<td><a href='visitorform.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
            echo "<td><a href='visitorform.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
            echo "<td><a href='visitorform.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
            echo "<td><a href='visitorform.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
        }else{
            echo "<tr style='background: #000; color: #fff;' onclick='alert_death()'><td width ='2%'>".$temp."</td><td>".ucwords(strtolower($row['Patient_Name']))."</td>";
            echo "<td>";echo $row['Registration_ID'];   echo "</td>";;
            echo "<td>".$row['Guarantor_Name']."</td>";
            echo "<td>".$age."</td>";
            echo "<td>".$row['Gender']."</td>";
            echo "<td>".$row['Phone_Number']."</td>";
            echo "<td>".$row['Member_Number']."</td>";
	
        }
        $temp++;
    } echo "</tr>";
    }else{

    echo "<tr>";
    echo "<td colspan='8'><span style='text-align: center; color: red; font-size: 18; font-weight: bold;'>THIS PATIENT IS NOT AVAILABLE IN OUR HOSPITAL</span></td></tr>";
}

?></table></center>

