<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];   
    }else{
        $Patient_Number = '';
    }
    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }

    echo '<center><table width =100%>';
    echo '<tr id="thead"><td width = 5%><b>SN</b></td>
	    <td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NUMBER</b></td>
		    <td><b>SPONSOR</b></td>
			<td><b>DATE OF BIRTH</b></td>
			    <td><b>GENDER</b></td>
				<td><b>PHONE NUMBER</b></td>
				    <td><b>MEMBER NUMBER</b></td></tr>';
    
    if($Patient_Name != '' && $Patient_Name != null && $Patient_Number != '' && $Patient_Number != null){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and
			pr.Patient_Name like '%$Patient_Name%' and
			    pr.Registration_ID like '%$Patient_Number%' order by Registration_ID Desc limit 500") or die(mysqli_error($conn));
    }elseif($Patient_Name != '' && $Patient_Name != null && ($Patient_Number == '' || $Patient_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and
			pr.Patient_Name like '%$Patient_Name%' order by Registration_ID Desc limit 500") or die(mysqli_error($conn));
    }elseif($Patient_Number != '' && $Patient_Number != null && ($Patient_Name == '' || $Patient_Name == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and
			pr.Registration_ID like '%$Patient_Number%' order by Registration_ID Desc limit 500") or die(mysqli_error($conn));
    }else{
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id and
			pr.Patient_Name like '%$Patient_Name%' order by Registration_ID Desc limit 500") or die(mysqli_error($conn));
    }
    

		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td>".$temp."</td><td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
	echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
	$temp++;
    }    echo "</tr>";
?></table></center>

