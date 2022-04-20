<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    
	$temp=1;
    //GET BRANCH ID
    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    if(isset($_SESSION['userinfo']['Branch_ID'])){
	$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
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
    echo '<tr id="thead"><td style="width:5%;">SN</td><td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NO</b></td>
                    <td><b>AGE</b></td>
                        <td><b>GENDER</b></td>
						 <td><b>SPONSOR</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';
    $select_Filtered_Patients = mysqli_query($conn,
            "select * from tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
		pr.registration_id = ci.registration_id and
		    sp.sponsor_id = pr.sponsor_id and
		    Check_In_Date = '$Today' and
			ci.Branch_ID = '$Folio_Branch_ID' and 
			    ci.check_in_status = 'pending'") or die(mysqli_error($conn));

			    
    if(strtolower($Reception_Picking_Items) == 'yes'){
	
	    while($row = mysqli_fetch_array($select_Filtered_Patients)){		
			$Registration_ID = $row['Registration_ID'];
			
			$date1 = new DateTime($Today);
			$date2 = new DateTime($row['Date_Of_Birth']);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months, ";
			$age .= $diff->d." Days";
	 
		
		
		if(strtolower($row['Guarantor_Name']) == 'cash'){
		    if(strtolower($_SESSION['systeminfo']['Departmental_Collection']) == 'yes'){
				/**	with this setting all cash patients will appear here if
				*	there is any problem appear while receptionist processing the list of items
				*	Receptionist has no privileges to perform cash transactions
				*	we need to redirect to prepared list if there is any prepared list based on selected patient
				*	otherwise we will select patient via emergence page (revenuecenterpatientbillingreception.php) 
				*/
				
				//check if there is any items based on selected patient
				$select_item = mysqli_query($conn,"select Patient_Payment_Cache_ID from tbl_patient_payments_cache where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select_item);
				if($num > 0){
					echo "<tr><td id='thead'>".$temp."</td><td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";				
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";				
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";				 
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";				
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";			 
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";				
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
				}else{
				    echo "<tr><td id='thead'>".$temp."</td><td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";				
				    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";				
				    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";				
				    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";				
					echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";				
				    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";				
				    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
				}
		    }else{
				$select_item = mysqli_query($conn,"select Patient_Payment_Cache_ID from tbl_patient_payments_cache where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select_item);
				if($num > 0){
					echo "<tr><td id='thead'>".$temp."</td><td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";				
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";				
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";				
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";				
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";				
					echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
				}else{
				    echo "<tr><td id='thead'>".$temp."</td><td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";				
					echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";				
				    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";				
				    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";				
					echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";				
				    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";				
				    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
				}
		    }    
		}else{
		    //check if there is any items based on selected patient
		    $Registration_ID = $row['Registration_ID'];
		    $select_item = mysqli_query($conn,"select Patient_Payment_Cache_ID from tbl_patient_payments_cache where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		    $num = mysqli_num_rows($select_item);
		    if($num > 0){
				echo "<tr><td id='thead'>".$temp."</td><td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";			
				echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";			
				echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";			
				echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";			
				echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";			
				echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";			
				echo "<td><a href='patientbillingprepared.php?Registration_ID=".$row['Registration_ID']."&NR=true&Check_In_ID=".$row['Check_In_ID']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
		    }else{
				echo "<tr><td id='thead'>".$temp."</td><td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";			
				echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";			
				echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";			
				echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";			
				echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";			
				echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";			
				echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
		    }
		}
			$temp++;	   
	   }	
    }else{
		while($row = mysqli_fetch_array($select_Filtered_Patients)){
		    echo "<tr><td id='thead'>".$temp."</td><td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";		
		    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";		
		    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";		
		    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";		
		    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";		
		    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";		
		    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
		}
    }
?></table></center>

