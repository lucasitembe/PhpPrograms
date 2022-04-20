<?php
    include("./includes/connection.php");
    $temp = 1;
    $filter = " ppl.Nursing_Status='served'  AND DATE(n.Nurse_DateTime) = DATE(NOW())";

if(isset($_GET['Sponsor'])){
    
$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$ward= filter_input(INPUT_GET, 'ward');

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = " ppl.Nursing_Status='served' AND n.Nurse_DateTime BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

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
	
    echo '<center><table width ="100%" border="0" id="PatientsList">';
    echo '<thead>
          <tr>
            <td style="width:5%;"><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td><b>PATIENT NO</b></td>
            <td><b>AGE</b></td>
            <td><b>GENDER</b></td>
            <td><b>VISIT DATE</b></td>
            <td><b>BP</b></td>
            <td><b>Pulse</b></td>
            <td><b>Phone #</b></td>
          </tr>
        </thead>';
    
    $sql="SELECT n.Nurse_ID,n.Nurse_DateTime,pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
                FROM  tbl_patient_payment_item_list ppl INNER JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                JOIN tbl_nurse n ON n.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID
                WHERE  $filter ORDER BY ppl.Transaction_Date_And_Time
            ";
    
   // die($sql);
							 
    $select_Patient = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	
    while($row = mysqli_fetch_array($select_Patient)){
	        $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
	        $Registration_ID = $row['Registration_ID'];
                $Patient_Name = $row['Patient_Name'];
	        $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name']; 
		
		echo "<tr><td id='thead'>".$temp."</td><td><a href='nurseregistration.php?checked=true&Nurse_ID=".$row['Nurse_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		
		echo "<td><a href='nurseregistration.php?checked=true&Nurse_ID=".$row['Nurse_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
       	
		echo "<td><a href='nurseregistration.php?checked=true&Nurse_ID=".$row['Nurse_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
		
		echo "<td><a href='nurseregistration.php?checked=true&Nurse_ID=".$row['Nurse_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
       
	        echo "<td><a href='nurseregistration.php?checked=true&Nurse_ID=".$row['Nurse_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
          
                echo "<td><a href='nurseregistration.php?checked=true&Nurse_ID=".$row['Nurse_ID']."&Registration_ID=".$row['Registration_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none;'>".$row['Nurse_DateTime']."</a></td>";
        
		echo "</tr>";
	   	$temp++;
	}	
?></table></center>