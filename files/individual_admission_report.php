<?php
include("./includes/connection.php");
$Registration_ID = filter_input(INPUT_GET, 'Registration_ID');
$select_Filtered_Patients = "
				SELECT 
					sp.Guarantor_Name, 
					pr.Gender,pr.Region,
					pr.Registration_ID,
					pr.Date_Of_Birth,
					hw.Hospital_Ward_Name,
					pr.Patient_Name,
					hw.Hospital_Ward_Name,
					a.Bed_ID,
					a.Admission_Status,
					a.Kin_Name,
					a.Admission_Date_Time,
					a.Kin_Phone,
                    a.Admision_ID
				FROM 	
					tbl_hospital_ward hw,
					tbl_patient_registration pr,
					tbl_sponsor sp,
					tbl_admission a
					WHERE 
						a.registration_id = pr.registration_id AND
                                                a.registration_id ='$Registration_ID'  AND
						pr.Sponsor_ID = sp.Sponsor_ID AND 
						hw.Hospital_Ward_ID = a.Hospital_Ward_ID ORDER BY a.Admission_Date_Time DESC LIMIT 1";
						
						
$select_Filtered_Mortuary = "
				SELECT 
					pr.Patient_Name,
					pr.Gender,pr.Registration_ID,
					hw.Hospital_Ward_Name, pr.Date_Of_Birth,
					pr.District,pr.Region, pr.Ward,pr.Country,ma.Date_Of_Death,
					ma.Bed_ID,ma.Corpse_ID, ad.Admission_Date_Time, ma.Nurse_On_Duty, ma.Corpse_Brought_By, ma.Corpse_Received_By,
					ma.Corpse_Kin_Relationship,ma.Corpse_Kin_Phone,ma.Corpse_Kin_Address,ma.Place_Of_Death,
					ma.Vehicle_No_In,ma.Other_Details,ma.case_type,ma.Police_No,ma.Police_Place,ma.Police_Phone,
					ma.Postmortem_Done_By,ma.Postmortem_No,ma.Police_Station,ma.Police_Title, ma.Police_Name,
					ma.Police_Postal_Box,ma.Mortuary_Admission_ID, ma.corpse_properties
				FROM 	
					tbl_hospital_ward hw,tbl_admission ad,
					tbl_patient_registration pr,
					tbl_mortuary_admission ma
					WHERE 
						ma.Corpse_ID = pr.registration_id AND ma.Admision_ID = ad.Admision_ID AND
                        ma.Corpse_ID ='$Registration_ID' ORDER BY ad.Admission_Date_Time DESC LIMIT 1";

echo '<center><table width ="100%" cellpadding="6" border="0" style="background-color:white;" id="patients-list">';
//echo '<thead>
//		<tr>
//			<td width="3%"><b>Sn</b></td>
//			<td width="15%"><b>Patient Name</b></td>
//			<td width="9%" style="text-align: left;"><b>Patient type</b></td>
//                        <td width="9%" style="text-align: left;"><b>Rank</b></td>
//			<td width="9%" style="text-align: left;"><b>Patient #</b></td>
//			<td width="8%" style="text-align: left;"><b>Sponsor</b></td>
//            <td width="8%" style="text-align: left;"><b>Admission_ID</b></td>
//			<td width="9%" style="text-align: left;"><b>Ward</b></td>
//			<td width="7%" style="text-align: left;"><b>Bed</b></td>
//			<td width="6%" style="text-align: left;"><b>Gend</b></td>
//			<td width="6%" style="text-align: center;"><b>Age</b></td>
//			<td width="10%" style="text-align: center;"><b>Admitted</b></td>
//			<td width="11%" style="text-align: left;"><b>Next of kin</b></td>
//			<td width="10%" style="text-align: left;"><b>Kin phone</b></td>
//			<td width="10%" style="text-align: left;"><b>Region</b></td>
//		</tr>
//                </thead>';
$results = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn));
$results_mortuary = mysqli_query($conn,$select_Filtered_Mortuary) or die(mysqli_error($conn));
$temp = 1;

$Today_Date = mysqli_query($conn,"select now() as today");
$htm = "";
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

while ($row = mysqli_fetch_array($results)) {
    $admission_date = date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));

    $bed_id = $row['Bed_ID'];
    $get_bed_name = "SELECT * FROM tbl_beds WHERE Bed_ID = '$bed_id'";
    $got_bed_name = mysqli_query($conn,$get_bed_name) or die(mysqli_error($conn));
	$bed_name = '';
    while ($rowb = mysqli_fetch_assoc($got_bed_name)) {
        $bed_name = $rowb['Bed_Name'];
    }

  
    
//for printing..........
if(isset($_GET['intent'])){
  echo '<center><table width ="100%" cellpadding="6" border="0" style="background-color:white;" id="patients-list">';
    echo "<tr>
             <td colspan='2' style='text-align:center'><img src='./branchBanner/branchBanner.png' width='100%' /></td>
          </tr>"; 
	echo "<tr>
          <td colspan='2' style='text-align:center'><h2>ADMISSION PERMIT</h2></td>
          </tr>";
    echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>NAME:</b></td><td style='font-size:13px;'>" . ucwords(strtolower($row['Patient_Name'])) . "</td></tr>";
    echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>PATIENT #:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Registration_ID'] . "</td></tr>";
    echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>SPONSOR:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Guarantor_Name'] . "</td></tr>";
    echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>ADMISSION ID:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Admision_ID'] . "</td></tr>";
    echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>WARD:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Hospital_Ward_Name'] . "</td></tr>";
   echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>BED:</b></td><td style='text-align: left; font-size:15px;'>" . $bed_name . "</td></tr>";
    echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>GENDER:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Gender'] . "</td></tr>";
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
    if ($age == 0) {
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->m . " Months";
    }
    if ($age == 0) {
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->d . " Days";
    };
    echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>AGE:</b></td><td style='text-align: left; font-size:15px;'>" . $age . "</td></tr>";
    echo "<tr><td style='padding-bottom:5px;text-align:right'><b>ADMISSION DATE:</b></td><td style='txt-align: center; font-size:15px;'>" . $admission_date . "</td></tr>";
    echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>NEXT OF KIN:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Kin_Name'])) . "</td></tr>";
    echo "<tr><td style='padding-bottom:5px;text-align:right'><b>KIN PHONE:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Kin_Phone'] . "</td></tr>";
   echo "<tr><td  style='padding-bottom:5px;text-align:right'><b>REGION:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Region'] . "</td></tr>";  
 echo "</table></center>";

    // include("MPDF/mpdf.php");

    // $mpdf=new mPDF('','A4',0,'',12.7,12.7,14,12.7,8,8); 
    // $mpdf->SetFooter('{DATE d-m-Y}');
    // $mpdf->WriteHTML($htm);
    // $mpdf->Output();  
	echo '
	<script>
    window.print(false);
    CheckWindowState();

    function PrintWindow() {
        window.print();
        CheckWindowState();
    }

    function CheckWindowState() {
        if (document.readyState == "complete") {
            window.close();
        } else {
            setTimeout("CheckWindowState()", 2000);
        }
    }
</script>
	';
} 
} 

while ($row = mysqli_fetch_array($results_mortuary)) {
    $admission_date = date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));

    $bed_id = $row['Bed_ID'];
    $nurse_id = $row['Nurse_On_Duty'];
    $doctor_id = $row['Postmortem_Done_By'];
    $rcvd_id = $row['Corpse_Received_By'];
    $get_bed_name = "SELECT * FROM tbl_beds WHERE Bed_ID = '$bed_id'";
    $get_nurse_name = "SELECT * FROM tbl_employee WHERE Employee_ID = '$nurse_id'";
    $get_doctor_name = "SELECT * FROM tbl_employee WHERE Employee_ID = '$doctor_id'";
    $get_rcvd_name = "SELECT * FROM tbl_employee WHERE Employee_ID = '$rcvd_id'";
    $bed_name = '';
	$nurse = '';
	$doctor = '';
	$rcvd = '';
    $got_bed_name = mysqli_query($conn,$get_bed_name) or die(mysqli_error($conn));
    while ($rowb = mysqli_fetch_assoc($got_bed_name)) {
        $bed_name = $rowb['Bed_Name'];
    }
    $got_nurse_name = mysqli_query($conn,$get_nurse_name) or die(mysqli_error($conn));
    while ($rown = mysqli_fetch_assoc($got_nurse_name)) {
        $nurse = $rown['Employee_Name'];
    }
    $got_doctor_name = mysqli_query($conn,$get_doctor_name) or die(mysqli_error($conn));
    while ($rowd = mysqli_fetch_assoc($got_doctor_name)) {
        $doctor = $rowd['Employee_Name'];
    }
    $got_rcvd_name = mysqli_query($conn,$get_rcvd_name) or die(mysqli_error($conn));
    while ($rowd = mysqli_fetch_assoc($got_rcvd_name)) {
        $rcvd = $rowd['Employee_Name'];
    }

if(isset($_GET['mortuary'])){
  $htm.= '<table width ="100%" cellpadding="6" border="0" style="background-color:white;" id="patients-list">';
    $htm.= "<tr>
             <td colspan=2 style='text-align:center'><img src='branchBanner/branchBanner.png' width='100%' /></td>
          </tr>"; 
	$htm.= "<tr>
          <td colspan=2 ><center><h2>HISTOPATHOLOGY DEPARTMENT</h2></center></td>
          </tr>"; 
	$htm.= "<tr>
          <td colspan=2 ><center><h2>MORTUARY CORPSE RECEPTION REPORT</h2></center></td>
          </tr>";
	$htm.= "</table>";
       $htm.='<table width ="100%" cellpadding="6" border="0" style="background-color:white;" id="patients-list">';
       $htm.="<tr>
				<td width:'50%' style='padding-bottom:5px;text-align:right' colspan=2><center><b><u> GENERAL INFORMATION:</u></b></center></td>
				<td width:'50%' style='padding-bottom:5px;text-align:right' colspan=2><center><b><u> POLICE INFORMATION:</u></b> </center></td>
		   </tr></br>";
		   
    $htm.= "<tr>
				<td width='25%'style='text-align:right;'><b>FULL NAME:</b></td><td          width='25%'style='font-size:15px;'>" . ucwords(strtolower($row['Patient_Name'])) . "</td>
				<td width='25%'style='text-align:right;'><b>POLICE NAME:</b></td><td   width='25%'style='font-size:15px;'>" . ucwords(strtolower($row['Police_Name'])) . "</td>
		   </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>REG ID #:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Registration_ID'] . "</td>
				<td  style='padding-bottom:5px;text-align:left'><b>TITLE :</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Police_Title'])) . "</td>
		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>GENDER:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Gender'])) . "</td>
				<td  style='padding-bottom:5px;text-align:left'><b>POLICE STATION :</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Police_Station'])) . "</td>
		 </tr>";
	
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $Date_Of_Death = $row['Date_Of_Death'];
    $dcd_age = floor((strtotime($Date_Of_Death) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
    if ($dcd_age == 0) {
        $date1 = new DateTime($Date_Of_Death);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $dcd_age = $diff->m . " Months";
    }
    if ($dcd_age == 0) {
        $date1 = new DateTime($Date_Of_Death);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $dcd_age = $diff->d . " Days";
    };
    $htm.= "<tr>
			<td  style='padding-bottom:5px;text-align:left'><b>     AGE:</b></td><td style='text-align: left; font-size:15px;'>" . $dcd_age . "</td>
	<td  style='padding-bottom:5px;text-align:left'><b>POLICE N<u>o</u> :</b></td><td style='text-align: left; font-size:15px;'>" . $row['Police_No'] . "</td>
		</tr>";
    $htm.= "<tr>
			<td  style='padding-bottom:10px;text-align:left'><b>DATE OF DEATH:</b></td><td style='text-align: left; font-size:15px;'>" . $Date_Of_Death . "</td>
			<td  style='padding-bottom:5px;text-align:left'><b>POLICE PHONE :</b></td><td style='text-align: left; font-size:15px;'>" . $row['Police_Phone'] . "</td>
		</tr>";
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>ADRESS :</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Ward']." ". $row['District']))." ". $row['Region'] . "</td>
				<td  style='padding-bottom:5px;text-align:left'><b>POSTAL BOX :</b></td><td style='text-align: left; font-size:15px;'>" . $row['Police_Postal_Box'] . "</td>
		
		 </tr>";
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>COUNTRY :</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Country'])).  "</td>
				<td  style='padding-bottom:5px;text-align:left'><b>PLACE :</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Police_Place'])) . "</td>

		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>PLACE OF DEATH:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Place_Of_Death'])) . "</td>
		 		<td  style='padding-bottom:5px;text-align:left'><b>POSTMORTEM N<u>o</u> :</b></td><td style='text-align: left; font-size:15px;'>" . $row['Postmortem_No'] . "</td>

		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>CASE TYPE:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['case_type'])) . "</td>
		 		<td  style='padding-bottom:5px;text-align:left'><b>POSTMORTEM DONE BY:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($doctor)) . "</td>

		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>WARD:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Hospital_Ward_Name'] . "</td>
		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>NURSE ON DUTY:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($nurse)) . "</td>
		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>DATE IN:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Admission_Date_Time'] . "</td>
		 </tr>"; 
			$deadline = $row['Admission_Date_Time'];
			$deadline = strtotime($deadline);
			$deadline = strtotime("+7 day", $deadline);
			//echo date('d/m/Y', strtotime('+7 days'));
			$deadline = date('Y-M-d', strtotime('+7 days'));
	$htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>DEADBODY'S PROPERTIES:</b></td><td style='text-align: left; font-size:15px;'>" . $row['corpse_properties'] . "</td>
		 </tr>";
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>MORTUARY DEADLINE:</b></td><td style='text-align: left; font-size:15px;'>" . $deadline . "</td>
		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>BROUGHT BY:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Corpse_Brought_By'])) . "</td>
				<td  style='padding-bottom:5px;text-align:right'><b></b></td><td style='text-align: left; font-size:15px;'>"." </td>
		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>RELATIONSHIP:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Corpse_Kin_Relationship'])) . "</td>
				<td  style='padding-bottom:5px;text-align:right'><b></b></td><td style='text-align: left; font-size:15px;'>" . "</td>
		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>PHONE:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Corpse_Kin_Phone'] . "</td>
				<td  style='padding-bottom:5px;text-align:right'><b></b></td><td style='text-align: left; font-size:15px;'>" . "</td>
		 </tr>"; 
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>NXT OF KIN ADDRESS :</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Corpse_Kin_Address'])) . "</td>
				<td  style='padding-bottom:5px;text-align:right'><b></b></td><td style='text-align: left; font-size:15px;'>" . "</td>
		 </tr>";
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>VEHICLE N<u>o</u> In:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($row['Vehicle_No_In'])) . "</td>
				<td  style='padding-bottom:5px;text-align:right'><b></b></td><td style='text-align: left; font-size:15px;'>" . "</td>
		 </tr>";
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:left'><b>OTHER DETAILS:</b></td><td style='text-align: left; font-size:15px;'>" . $row['Other_Details'] . "</td>
				<td  style='padding-bottom:5px;text-align:right'><b></b></td><td style='text-align: left; font-size:15px;'>" . "</td>
		 </tr>";
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:leftt'><b>MORTUARY STAFF:</b></td><td style='text-align: left; font-size:15px;'>" . ucwords(strtolower($rcvd)). "</td>
				<td  style='padding-bottom:5px;text-align:right'><b>BROUGHT BY</b></td><td style='text-align: left; font-size:15px;'>" .ucwords(strtolower($row['Corpse_Brought_By'])). "</td>
		 </tr>";
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:right'><b>SIGNATURE:</b></td><td style='text-align: left; font-size:15px;'>" . "______________________________________________". "</td>
				<td  style='padding-bottom:5px;text-align:right'><b>SIGNATURE:</b></td><td style='text-align: left; font-size:15px;'>" . "______________________________________________". "</td>
		 </tr>";   
 $htm.= "</table>";

    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','A4',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();  
	echo '
	<script>
    window.print(false);
    CheckWindowState();

    function PrintWindow() {
        window.print();
        CheckWindowState();
    }

    function CheckWindowState() {
        if (document.readyState == "complete") {
            window.close();
        } else {
            setTimeout("CheckWindowState()", 2000);
        }
    }
</script>
	';
}  
   
}
?>

