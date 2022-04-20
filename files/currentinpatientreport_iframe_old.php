<?php
    include("./includes/connection.php");
    $Branch_ID = $_GET['Branch_ID'];
    $Gender = $_GET['Gender'];
    $Region = $_GET['Region'];
    $Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
    $start_age = $_GET['start_age'];
    $end_age = $_GET['end_age'];
    
    $select_Filtered_Patients = "select  sp.Guarantor_Name, pr.Gender,pr.Region,
					pr.Registration_ID,pr.Date_Of_Birth,
					hw.Hospital_Ward_Name,
					pr.Patient_Name,
					hw.Hospital_Ward_Name,
					a.Admission_Status
				
				from 	tbl_hospital_ward hw,
						tbl_patient_registration pr,
					tbl_sponsor sp,
					tbl_admission a
					where a.registration_id = pr.registration_id and
					a.registration_id = pr.registration_id and
					pr.Sponsor_ID = sp.Sponsor_ID
					and hw.Hospital_Ward_ID = a.Hospital_Ward_ID and
					a.Admission_Status = 'Admitted'
					";
    if($Branch_ID!=''){
	if($Branch_ID!='ALL'){
	$select_Filtered_Patients.=" and hw.Branch_ID = '$Branch_ID'";
	}
    }
    
    if($Gender!=''){
	if($Gender!='ALL'){
	    $select_Filtered_Patients.=" and pr.Gender = '$Gender'";
	}
    }
    
    if($Region!=''){
	if($Region!='ALL'){
	    $select_Filtered_Patients.=" and pr.Region = '$Region'";
	}
    }
    
    if($Hospital_Ward_ID!=''){
	if($Hospital_Ward_ID!='ALL'){
	    $select_Filtered_Patients.=" and a.Hospital_Ward_ID = '$Hospital_Ward_ID'";
	}
    }
    
//    if($start_age!='' && $end_age!=''){
//	    $nowYear = 0+date('Y');
//	    $date1 = $nowYear - $start_age;
//	    $date1.='-00-00';
//	    $date2 = $nowYear - $end_age;
//	    $date2.='-00-00';
//	    $select_Filtered_Patients.=" and pr.Date_Of_Birth BETWEEN '$date1' AND '$date2'";
//    }
    
    echo '<center><table width =100% border=0>';
    echo '<tr><td width=5%><b>SN</b></td><td width="25%"><b>PATIENT NAME</b></td>
                    <td width="20%" style="text-align: center;"><b>MRN</b></td>
			<td width="15%" style="text-align: center;"><b>SPONSOR</b></td>
			<td width="15%" style="text-align: center;"><b>GENDER</b></td>
			    <td width="10%" style="text-align: right;"><b>AGE</b></td>
			<td width="10%" style="text-align: right;"><b>REGION</b></td>
				</tr>';
                        
    $results = mysqli_query($conn,$select_Filtered_Patients);
    $temp=1;
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
    
    while($row = mysqli_fetch_array($results)){
	echo "<tr><td colspan=7><hr></td></tr>";
	echo '<tr><td>'.$temp.'</td>';
        echo "<td><a href='#' target='_blank' style='text-decoration: none;'>".ucfirst($row['Patient_Name'])."</a></td>";
        echo "<td style='text-align: center;'><a href='#' target='_blank' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
	echo "<td style='text-align: center;'><a href='#' target='_blank' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td style='text-align: center;'><a href='#' target='_blank' style='text-decoration: none;'>".$row['Gender']."</a></td>";
	
	$Date_Of_Birth = $row['Date_Of_Birth'];
	$age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->m." Months";
	    }
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->d." Days";
	    };
	echo "<td style='text-align: right;'><a href='#' target='_blank' style='text-decoration: none;'>".$age."</a></td>";
	echo "<td style='text-align: right;'><a href='#' target='_blank' style='text-decoration: none;'>".$row['Region']."</a></td>";
	
	$temp++;
    }
    echo "<tr><td colspan=7><hr></td></tr>";
    echo "<tr><td colspan=7 style='text-align: right;'><b> TOTAL ADMITTED : ".number_format($temp-1)."</td></tr>";
    echo "<tr><td colspan=7><hr></td></tr>";
?></table></center>
