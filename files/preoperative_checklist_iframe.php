<link rel="stylesheet" href="table.css" media="screen"> 
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    /* tr:hover{
        background-color:#dedede;
        cursor:pointer;
    }
    td a:hover{
        color: #000;
    } */
</style> 

<?php
    include("./includes/connection.php");
	$temp = 1;
    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];   
    }else{
        $Patient_Number = '';
    }

	if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];   
    }else{
        $Date_To = '';
    }

	if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];   
    }else{
        $Date_From = '';
    }


    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
$filter = '';

if(!empty($Date_To) && !empty($Date_From)){
	$filter .= " AND poc.Operative_Date_Time BETWEEN '$Date_From' AND '$Date_To'";
}else{
	$filter .='';
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

if(!empty($Patient_Name)){
	$filter .= " AND pr.Patient_Name like '%$Patient_Name%'";
}else{
	$filter .='';
}

if(!empty($Patient_Number)){
	$filter .= " AND poc.Registration_ID = '$Patient_Number'";
}else{
	$filter .='';
}

// if(!empty($ward_id)){
// 	$filter .= " AND  poc.Hospital_Ward_ID = '$ward_id'";
// }
	
echo '<center>
        <table width ="100%" border="0" style="background-color:white;" id="patients-list">';
echo '<thead>
	<tr style="font-size: 24px;"  id="thead">
 <td style="width:5%;"><b>SN</b></td>
	     <td><b>PATIENT NAME</b></td>
	    <td><b>PATIENT NO</b></td>
                    <td><b>AGE</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                            <td><b>SURGERY NAME</b></td>
				<td><b>OPERATION DATE TIME</b></td></tr>
        </thead>';


    $select_Filtered_Donors = mysqli_query($conn, "SELECT poc.Theatre_Time, poc.Registration_ID, i.Product_Name, poc.Pre_Operative_ID, pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number FROM tbl_items i, tbl_patient_registration pr, tbl_pre_operative_checklist poc, tbl_item_list_cache ilc WHERE pr.Registration_ID = poc.Registration_ID AND ilc.Payment_Item_Cache_List_ID = poc.Payment_Item_Cache_List_ID AND poc.acceptance <> '' AND i.Item_ID = ilc.Item_ID $filter ORDER BY poc.Pre_Operative_ID DESC LIMIT 100") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Donors)){
		$Admision_ID = $row['Admision_ID'];
		$Pre_Operative_ID = $row['Pre_Operative_ID'];
		$Registration_ID = $row['Registration_ID'];
		$Product_Name = $row['Product_Name'];

	
		//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
		$Registration_ID = $row['Registration_ID'];
		$Theatre_Time = $row['Theatre_Time'];
		//check if is available

		// $check = mysqli_query($conn,"SELECT Registration_ID, Operative_Date_Time from tbl_pre_operative_checklist 
		// 						where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
		// $num = mysqli_num_rows($check);
		// if($num > 0){


			echo "<tr><td id='thead'>".$temp."</td>";
			echo "<td><a href='Checklist_Form.php?Pre_Operative_ID=".$Pre_Operative_ID."&Admision_ID=".$Admision_ID."&Registration_ID=".$Registration_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
			echo "<td><a href='Checklist_Form.php?Pre_Operative_ID=".$Pre_Operative_ID."&Admision_ID=".$Admision_ID."&Registration_ID=".$Registration_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
			echo "<td><a href='Checklist_Form.php?Pre_Operative_ID=".$Pre_Operative_ID."&Admision_ID=".$Admision_ID."&Registration_ID=".$Registration_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$age."</a></td>";
			echo "<td><a href='Checklist_Form.php?Pre_Operative_ID=".$Pre_Operative_ID."&Admision_ID=".$Admision_ID."&Registration_ID=".$Registration_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Gender']."</a></td>";
			echo "<td><a href='Checklist_Form.php?Pre_Operative_ID=".$Pre_Operative_ID."&Admision_ID=".$Admision_ID."&Registration_ID=".$Registration_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
			echo "<td><a href='Checklist_Form.php?Pre_Operative_ID=".$Pre_Operative_ID."&Admision_ID=".$Admision_ID."&Registration_ID=".$Registration_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$Product_Name."</a></td>";
			echo "<td><a href='Checklist_Form.php?Pre_Operative_ID=".$Pre_Operative_ID."&Admision_ID=".$Admision_ID."&Registration_ID=".$Registration_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$Theatre_Time."</a></td>";
		// }else{
		// 	echo "<tr><td colspan='8'><span style='color: red; font-size: 20px;'>NO RECORD FOUND</span></td></tr>";
		// }
       $temp++; 
    }   echo "</tr>";
?></table></center>

