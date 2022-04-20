<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    $filter1 = "";
    $filter2 = "";
    $filter3 = "";

    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
        $filter1 = " AND pr.Patient_Name like '%$Patient_Name%'";
    }else{
        $Patient_Name = '';
    }
    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number']; 
        $filter2 = " AND pr.Registration_ID = '$Patient_Number'";  
    }else{
        $Patient_Number = '';
    }
    if(isset($_GET['Phone_Number'])){
        $Phone_Number = $_GET['Phone_Number'];   
        $filter3 = " AND pr.Phone_Number like '%$Phone_Number%'";
    }else{
        $Phone_Number = '';
    }
    
       
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }

    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td><td><b>PATIENT NAME</b></td>
	    <td><b>PATIENT NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>DATE OF BIRTH</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';
    
    
    if($Patient_Name != '' || $Patient_Name != null && $Patient_Number != '' || $Patient_Number != null && $Phone_Number != '' || $Phone_Number != null){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id $filter1 $filter2 $filter3 order by Registration_ID Desc limit 500 ") or die(mysqli_error($conn));
    }elseif($Patient_Name != '' && $Patient_Name != null && ($Patient_Number == '' || $Patient_Number == null) && ($Phone_Number == '' || $Phone_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id $filter1 order by Registration_ID Desc limit 500 ") or die(mysqli_error($conn));
    }elseif($Patient_Number != '' && $Patient_Number != null && ($Patient_Name == '' || $Patient_Name == null) && ($Phone_Number == '' || $Phone_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id $filter2 order by Registration_ID Desc limit 500 ") or die(mysqli_error($conn));
    }elseif($Phone_Number != '' && $Phone_Number !=null && ($Patient_Name == '' || $Patient_Name == null) && ($Patient_Number == '' || $Patient_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id $filter3 order by Registration_ID Desc limit 500 ") or die(mysqli_error($conn));
    }else{
	$select_Filtered_Patients = mysqli_query($conn,
            "select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name
		from tbl_patient_registration pr, tbl_sponsor sp where
		    pr.sponsor_id = sp.sponsor_id order by Registration_ID Desc limit 500 ") or die(mysqli_error($conn));
    }

		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td id='thead'>".$temp."<td><a href='editpatient.php?Registration_ID=".$row['Registration_ID']."&EditPatient=EditPatientThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
	echo "<td><a href='editpatient.php?Registration_ID=".$row['Registration_ID']."&EditPatient=EditPatientThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='editpatient.php?Registration_ID=".$row['Registration_ID']."&EditPatient=EditPatientThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
	if($row['Date_Of_Birth'] != null && $row['Date_Of_Birth'] != '' && $row['Date_Of_Birth'] != '0000-00-00'){
	    echo "<td><a href='editpatient.php?Registration_ID=".$row['Registration_ID']."&EditPatient=EditPatientThisForm' target='_parent' style='text-decoration: none;'>".@date('jS F, Y',date(strtotime($row['Date_Of_Birth'])))."</a></td>";
	}else{
	    echo "<td><a href='editpatient.php?Registration_ID=".$row['Registration_ID']."&EditPatient=EditPatientThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
	}

        echo "<td><a href='editpatient.php?Registration_ID=".$row['Registration_ID']."&EditPatient=EditPatientThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='editpatient.php?Registration_ID=".$row['Registration_ID']."&EditPatient=EditPatientThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='editpatient.php?Registration_ID=".$row['Registration_ID']."&EditPatient=EditPatientThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
	$temp++;
    }    echo "</tr>";
?></table></center>

