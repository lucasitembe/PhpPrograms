<?php
	session_start();
	include("./includes/connection.php");

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

	//get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $End_Date = $row['today'];
        $Start_Date = date("Y-m-d", strtotime($End_Date)).' 00:00';
        $Today = date("Y-m-d", strtotime($End_Date));
    }
?>
<legend style="background-color:#006400;color:white" align="right"><b>PATIENTS LIST</b></legend>
	<?php
        echo '<center><table width =100% border=0>';
        echo "<tr><td colspan='9'><hr></tr>";
        echo '<tr id="thead" style="width:5%;">
                <td><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>AGE</b></td>
                <td><b>GENDER</b></td>
                <td><b>MEMBER NUMBER</b></td>
            </tr>';
        echo "<tr><td colspan='9'><hr></tr>";

        if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != '' && $_GET['Patient_Number'] != null){
        	$select_Filtered_Patients = mysqli_query($conn,"select Patient_Name, Registration_ID, Guarantor_Name, Gender, Member_Number, Date_Of_Birth
                                                    from tbl_patient_registration pr, tbl_sponsor s where
                                                    pr.Sponsor_ID = s.Sponsor_ID and Registration_ID = '$Patient_Number'") or die(mysqli_error($conn));
        }else if(isset($_GET['Patient_Name']) && $_GET['Patient_Name'] != '' && $_GET['Patient_Name'] != null){
        	$select_Filtered_Patients = mysqli_query($conn,"select Patient_Name, Registration_ID, Guarantor_Name, Gender, Member_Number, Date_Of_Birth
                                        from tbl_patient_registration pr, tbl_sponsor s where
                                        pr.Sponsor_ID = s.Sponsor_ID and Patient_Name like '%$Patient_Name%' order by Registration_ID desc limit 100") or die(mysqli_error($conn));
        }else{
        	$select_Filtered_Patients = mysqli_query($conn,"select Patient_Name, Registration_ID, Guarantor_Name, Gender, Member_Number, Date_Of_Birth
                                        from tbl_patient_registration pr, tbl_sponsor s where
                                        pr.Sponsor_ID = s.Sponsor_ID order by Registration_ID desc limit 100") or die(mysqli_error($conn));
        }
		$num = mysqli_num_rows($select_Filtered_Patients);
		$temp = 0;
		if($num > 0){
			while($row = mysqli_fetch_array($select_Filtered_Patients)){
		        echo "<tr><td id='thead' style='width:5%;' >".++$temp."</td>";
		    
		        //GENERATE PATIENT YEARS, MONTHS AND DAYS
		        $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";       
		        $date1 = new DateTime($Today);
		        $date2 = new DateTime($row['Date_Of_Birth']);
		        $diff = $date1 -> diff($date2);
		        $age = $diff->y." Years, ";
		        $age .= $diff->m." Months, ";
		        $age .= $diff->d." Days";
		    
		    
		        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";   
		        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>"; 
		        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";        
		        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";        
		        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";    
		        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
		        echo "</tr>"; 
		    }
		}
	    echo "</table>";
?>