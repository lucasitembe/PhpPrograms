<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    echo '<center><table width =100%>';
    echo '<tr id="thead"><td width = 5%><b>SN</b></td><td><b>PATIENT NAME</b></td>
            <td><b>PATIENT NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>DATE OF BIRTH</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id order by Registration_ID Desc limit 500") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td>".$temp."<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
	echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='revenuecenterothersworkpage.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
	$temp++;
    }   echo "</tr>";
?></table></center>

