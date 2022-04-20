<link rel="stylesheet" href="table.css" media="screen">
<?php
include("./includes/connection.php");
$temp = 1;
// if(isset($_GET['Patient_Name'])){
    $Patient_Name = $_GET['Patient_Name'];
// }

    $Patient_Number = $_GET['Patient_Number'];

    $Phone_Number = $_GET['Phone_Number'];


$filter='';

if(!empty($Patient_Name)){
   $filter .=" AND pr.Patient_Name LIKE '%$Patient_Name%'"; 
}

if(!empty($Patient_Number)){
   $filter .=" AND pm.Registration_ID = '$Patient_Number' ";
}

if(!empty($Phone_Number)){
   $filter .=" AND pr.Phone_Number LIKE '%$Phone_Number%'"; 
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

    //get section for back buttons
    if(isset($_GET['section'])){
	$section = $_GET['section'];
    }else{
	$section = '';
    }

echo '<center><table width =100%>';
echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td><td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NO</b></td>
                 <td><b>GENDER</b></td>
				<td><b>AGE</b></td>
                       <td><b>SPONSOR</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td></tr>';


$select_Filtered_Patients = mysqli_query($conn,
    "SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, Radiotherapy_ID, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, position_immobilization_ID
                            from tbl_patient_registration pr, tbl_sponsor sp, tbl_position_immobilization pm WHERE pr.Registration_ID = pm.Registration_ID AND 
                                pr.sponsor_id = sp.sponsor_id $filter GROUP BY Radiotherapy_ID ORDER BY position_immobilization_ID ASC LIMIT 100 ") or die(mysqli_error($conn));


while($row = mysqli_fetch_array($select_Filtered_Patients)){

	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
           $position_immobilization_ID = $row['position_immobilization_ID'];
           $Radiotherapy_ID = $row['Radiotherapy_ID'];
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";

                if(isset($_GET['this_page_from'])){
                    $this_page_from=$_GET['this_page_from'];
                 }else{
                    $this_page_from=""; 
                 }
    echo "<tr><td id='thead'>".$temp."<td><a href='treatment_delivery.php?Registration_ID=".$row['Registration_ID']."&section=".$section."&position_immobilization_ID=".$position_immobilization_ID."&Radiotherapy_ID=".$Radiotherapy_ID."&PatientFile=PatientFileThisForm&this_page_from=patient_record' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
    echo "<td><a href='treatment_delivery.php?Registration_ID=".$row['Registration_ID']."&section=".$section."&position_immobilization_ID=".$position_immobilization_ID."&Radiotherapy_ID=".$Radiotherapy_ID."&PatientFile=PatientFileThisForm&this_page_from=patient_record' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
	echo "<td><a href='treatment_delivery.php?Registration_ID=".$row['Registration_ID']."&section=".$section."&position_immobilization_ID=".$position_immobilization_ID."&Radiotherapy_ID=".$Radiotherapy_ID."&PatientFile=PatientFileThisForm&this_page_from=patient_record' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
    echo "<td><a href='treatment_delivery.php?Registration_ID=".$row['Registration_ID']."&section=".$section."&position_immobilization_ID=".$position_immobilization_ID."&Radiotherapy_ID=".$Radiotherapy_ID."&PatientFile=PatientFileThisForm&this_page_from=patient_record' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
	echo "<td><a href='treatment_delivery.php?Registration_ID=".$row['Registration_ID']."&section=".$section."&position_immobilization_ID=".$position_immobilization_ID."&Radiotherapy_ID=".$Radiotherapy_ID."&PatientFile=PatientFileThisForm&this_page_from=patient_record' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
     echo "<td><a href='treatment_delivery.php?Registration_ID=".$row['Registration_ID']."&section=".$section."&position_immobilization_ID=".$position_immobilization_ID."&Radiotherapy_ID=".$Radiotherapy_ID."&PatientFile=PatientFileThisForm&this_page_from=patient_record' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
    echo "<td><a href='treatment_delivery.php?Registration_ID=".$row['Registration_ID']."&section=".$section."&position_immobilization_ID=".$position_immobilization_ID."&Radiotherapy_ID=".$Radiotherapy_ID."&PatientFile=PatientFileThisForm&this_page_from=patient_record' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
    $temp++;
}   echo "</tr>";
?></table></center>

