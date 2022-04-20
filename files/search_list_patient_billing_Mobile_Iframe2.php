<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
    //GET BRANCH ID
    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    if(isset($_SESSION['userinfo']['Branch_ID'])){
	$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }   
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    
    
    //check system settings
    $get_reception_setting = mysqli_query($conn,"select Reception_Picking_Items from tbl_system_configuration where branch_id = '$Branch_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($get_reception_setting);
    if($no > 0){
	while($data = mysqli_fetch_array($get_reception_setting)){
	    $Reception_Picking_Items = $data['Reception_Picking_Items'];
	}
    }else{
	$Reception_Picking_Items = 'no';
    }
    
    
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>DATE OF BIRTH</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';
    
    
    $select_Filtered_Patients = mysqli_query($conn,
            "select * from tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
		pr.registration_id = ci.registration_id and
		    sp.sponsor_id = pr.sponsor_id and
			ci.check_in_status = 'pending' and Check_In_Date = '$Today' and
			    ci.Branch_ID = '$Folio_Branch_ID' and  
				Patient_Name like '%$Patient_Name%'") or die(mysqli_error($conn));
    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td><a href='patientbillingMobile.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
        echo "<td><a href='patientbillingMobile.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='patientbillingMobile.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
        echo "<td><a href='patientbillingMobile.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='patientbillingMobile.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='patientbillingMobile.php?Registration_ID=".$row['Registration_ID']."&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
        }
    
?></table></center>