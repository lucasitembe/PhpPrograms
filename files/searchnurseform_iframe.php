<?php
    include("./includes/connection.php");
    $temp = 1;
    $filter = " AND pp.Receipt_Date = CURDATE()";
    //$filter = " ppl.Nursing_Status='not served'  AND DATE(ppl.Transaction_Date_And_Time) = DATE(NOW())";

if(isset($_GET['Sponsor'])){
    
$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$ward= filter_input(INPUT_GET, 'ward');

$Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
$Patient_Old_Number = filter_input(INPUT_GET, 'Patient_Old_Number');

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "AND pp.Payment_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
   // $filter = " ppl.Nursing_Status='not served'  AND ppl.Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_Number)) {
    $filter .="  AND pr.Registration_ID = '$Patient_Number'";
}

if (!empty($Patient_Old_Number)) {
    $filter .="  AND pr.Old_Registration_Number = '$Patient_Old_Number'";
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
             <td><b>PATIENT NUMBER</b></td>
            <td><b>AGE</b></td>
            <td><b>SPONSOR</b></td>
            <td><b>GENDER</b></td>
            <td><b>DIRECTION</b></td>
            <td><b>DESTINATION</b></td>
            <td><b>TRANS DATE</b></td>
          </tr>
        </thead>';
    
    // $sql="SELECT Payment_Date_And_Time,pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,pp.Patient_Payment_ID,pr.Sponsor_ID FROM tbl_patient_registration pr,tbl_patient_payments pp WHERE pr.Registration_ID=pp.Registration_ID  $filter GROUP BY pp.Registration_ID ORDER BY pp.Payment_Date_And_Time LIMIT 100";
    $sql = "SELECT 
                    Payment_Date_And_Time,
                    pr.Registration_ID,
                    pr.Old_Registration_Number,
                    pr.Gender,
                    pr.Patient_Name,
                    pr.Phone_Number,
                    pp.Check_In_ID,
                    pr.Member_Number,
                    pr.Date_Of_Birth,
                    pp.Patient_Payment_ID,
                    pr.Sponsor_ID,
                    ppl.Clinic_ID,
                    ppl.Consultant_ID,
                    ppl.Patient_Payment_Item_List_ID,
                    ppl.Patient_Direction,
                    sp.Guarantor_Name
                FROM
                    tbl_patient_registration pr,
                    tbl_patient_payments pp,
                    tbl_patient_payment_item_list ppl,
                    tbl_sponsor sp
                WHERE
                    pp.Registration_ID = pr.Registration_ID
                    and ppl.Patient_Payment_ID = pp.Patient_Payment_ID
                    AND pr.Sponsor_ID = sp.Sponsor_ID
                    and ppl.Nursing_Status='not served' 
                    and pr.Diseased = 'no'
                    and ppl.Patient_Direction IN ('Direct To Doctor Via Nurse Station','Direct To Clinic Via Nurse Station','Direct To Doctor','Direct To Clinic') $filter
                GROUP BY pr.Registration_ID
                ORDER BY pp.Payment_Date_And_Time
                LIMIT 100";
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
                $Patient_Direction = $row['Patient_Direction'];
                $Consultant_ID = $row['Consultant_ID'];
                $Clinic_ID = $row['Clinic_ID'];
                
                $Sponsor_ID = $row['Sponsor_ID']; 
                $Patient_Payment_ID = $row['Patient_Payment_ID']; 
                $Patient_Payment_Item_List_ID=$row['Patient_Payment_Item_List_ID'];
                   
                $Guarantor_Name = $row['Guarantor_Name'];
                $Check_In_ID = $row['Check_In_ID'];
		
                $Registration_ID=$row['Registration_ID'];

                if($Patient_Direction == 'Direct To Doctor Via Nurse Station' || $Patient_Direction == 'Direct To Doctor'){
                    $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Consultant_ID'"))['Employee_Name'];
                    $Destination = ucwords(strtolower($Employee_Name));
                }else{
                    $Destination = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID = '$Clinic_ID'"))['Clinic_Name'];
                }
                $sql_select_cons_id_result=mysqli_query($conn,"SELECT consultation_ID FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' AND Check_In_ID = '$Check_In_ID' ORDER BY consultation_ID DESC LIMIT 1") or die(mysqli_error($conn));
                $rows_cons=mysqli_fetch_assoc($sql_select_cons_id_result);
                $consultation_ID=$rows_cons['consultation_ID'];

        echo "<tr><td id='thead'>".$temp."</td>
            <td><a href='nursecommunicationpage.php?Registration_ID=".$row['Registration_ID']."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Clinic_ID=".$Clinic_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Patient_Type=Outpatient' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
        echo "<td><a href='nursecommunicationpage.php?Registration_ID=".$row['Registration_ID']."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Clinic_ID=".$Clinic_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Patient_Type=Outpatient' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		echo "<td><a href='nursecommunicationpage.php?Registration_ID=".$row['Registration_ID']."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Clinic_ID=".$Clinic_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Patient_Type=Outpatient' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
		echo "<td><a href='nursecommunicationpage.php?Registration_ID=".$row['Registration_ID']."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Clinic_ID=".$Clinic_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Patient_Type=Outpatient' target='_parent' style='text-decoration: none;'>".$Guarantor_Name."</a></td>";
	    echo "<td><a href='nursecommunicationpage.php?Registration_ID=".$row['Registration_ID']."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Clinic_ID=".$Clinic_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Patient_Type=Outpatient' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
	    echo "<td><a href='nursecommunicationpage.php?Registration_ID=".$row['Registration_ID']."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Clinic_ID=".$Clinic_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Patient_Type=Outpatient' target='_parent' style='text-decoration: none;'>".$Patient_Direction."</a></td>";
	    echo "<td><a href='nursecommunicationpage.php?Registration_ID=".$row['Registration_ID']."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Clinic_ID=".$Clinic_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Patient_Type=Outpatient' target='_parent' style='text-decoration: none;'>".$Destination."</a></td>";
        echo "<td><a href='nursecommunicationpage.php?Registration_ID=".$row['Registration_ID']."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Clinic_ID=".$Clinic_ID."&Patient_Payment_Item_List_ID=".$Patient_Payment_Item_List_ID."&Patient_Type=Outpatient' target='_parent' style='text-decoration: none;'>".$row['Payment_Date_And_Time']."</a></td>";
        
		echo "</tr>";
	   	$temp++;
                //}

            //}
	}	
?></table></center>