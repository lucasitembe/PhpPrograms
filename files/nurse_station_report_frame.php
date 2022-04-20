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
$Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
$Patient_Old_Number = filter_input(INPUT_GET, 'Patient_Old_Number');

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = " ppl.Nursing_Status='served' AND n.Nurse_DateTime BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
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
            <td><b>OLD PATIENT NUMBER</b></td>
             <td><b>PATIENT NUMBER</b></td>
            <td><b>AGE</b></td>
            <td><b>SPONSOR</b></td>
            <td><b>GENDER</b></td>';

            /*$resultVital = mysqli_query($conn,"SELECT * FROM tbl_vital");

             while ($row = mysqli_fetch_array($resultVital)) {
                echo' <td><b>'.strtoupper(($row['Vital'])).'</b></td>';
             }*/
             echo' <td><b>Blood Pressure</b></td>';
             echo' <td><b>Height</b></td>';
             echo' <td><b>PSA(Men Above 50 Yrs)</b></td>';
             echo' <td><b>Pulse</b></td>';
             echo' <td><b>Temperature</b></td>';
             echo' <td><b>Respiration</b></td>';
             echo' <td><b>RBG</b></td>';
             echo' <td><b>Weight</b></td>';
           echo' <td><b>BMI</b></td>';
           echo' <td><b>STATUS</b></td>';
           echo' <td><b>VISIT DATE</b></td>
          </tr>
        </thead>';
    
    $sql="SELECT n.Nurse_ID,n.bmi,n.Nurse_DateTime,pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
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
		
		echo "<tr><td id='thead'>".$temp."</td><td>".ucwords(strtolower($row['Patient_Name']))."</td>";
		
		echo "<td>".$row['Old_Registration_Number']."</td>";
        echo "<td>".$row['Registration_ID']."</td>";
       	
		echo "<td>".$age."</td>";
		
		echo "<td>".$row['Guarantor_Name']."</td>";
       
	    echo "<td>".$row['Gender']."</td>";

        $resultVitalValue = mysqli_query($conn,"SELECT v.Vital_ID,Vital,Vital_Value FROM tbl_vital v JOIN tbl_nurse_vital nv ON v.Vital_ID=nv.Vital_ID WHERE nv.Nurse_ID=" . $row['Nurse_ID'] . "");
        
        while ($row2 = mysqli_fetch_array($resultVitalValue)) {
                echo' <td><b>'.$row2['Vital_Value'].'</b></td>';
        }
         
         $bmi = $row['bmi'];

         $category = '';
        if ($bmi < 18.5) {
            $category = '<span style="color: rgb(222, 31, 31);" class="bmi-propert">Underweight</span>';
        } else if ($bmi >= 18.5 && $bmi <= 24.9) {
            $category = '<span style="color:green;" class="bmi-propert">Normal or Helathy Weight</span>';
        } else if ($bmi >= 25 && $bmi <= 29.9) {
            $category = '<span style="color:blue;" class="bmi-propert">Overweight</span>';
        } else if ($bmi >= 30) {
            $category = '<span style="color:red;" class="bmi-propert">Obese</span>';
        }

         echo "<td><b>".$bmi."</b></td>"; 
         echo "<td><b>".$category."</b></td>"; 

        echo "<td>".$row['Nurse_DateTime']."</td>";
        
		echo "</tr>";
	   	$temp++;
	}	
?></table></center>