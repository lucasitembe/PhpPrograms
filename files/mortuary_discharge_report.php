<?php

include("./includes/connection.php");

$Registration_ID = filter_input(INPUT_GET, 'Registration_ID');
$select_Filtered_Mortuary = "
				SELECT 
					pr.Patient_Name,ad.Discharge_Employee_ID,pr.Date_Of_Birth,
					pr.Gender,pr.Registration_ID,ma.Discharged_By,
					ma.Kin_Out_Phone,ma.Corpse_ID, ad.Admission_Date_Time, ma.Time_Out, ma.Taken_By, ma.Kin_Out,
					ma.City_Staff,ma.Vehicle_No_Out,ma.Discharged_By,ma.Kin_Out_Address,ma.Kin_Out_Relationship, ma.nida_id,
					ma.Mortuary_Admission_ID,Death_Certificate,ma.Relative_Cell_Leader,ma.Burial_Location,ma.Place_Of_Death
				FROM 	
					tbl_admission ad,
					tbl_patient_registration pr,
					tbl_mortuary_admission ma
					WHERE 
						ma.Corpse_ID = pr.registration_id AND ma.Admision_ID = ad.Admision_ID AND
                        ma.Corpse_ID ='$Registration_ID' ORDER BY ma.Time_Out DESC LIMIT 1";

echo '<center><table width ="100%" cellpadding="6" border="0" style="background-color:white;" id="patients-list">';



$results_mortuary = mysqli_query($conn,$select_Filtered_Mortuary) or die(mysqli_error($conn));
$temp = 1;
$htm = "";
while ($row = mysqli_fetch_array($results_mortuary)) {

    	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
     // if($age == 0){
      
      $date1 = new DateTime($Today);
      $date2 = new DateTime($row['Date_Of_Birth']);
      $diff = $date1 -> diff($date2);
      $age = $diff->y." Years, ";
      $age .= $diff->m." Months";
    //   $age .= $diff->d." Days";
if(isset($_GET['intent'])){ 
    // echo $row['Discharge_Employee_ID'];
    $Discharged_By = $row['Discharge_Employee_ID'];
    $get_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Discharged_By'";
	// echo  $get_name;
	// exit;
    $name =mysqli_fetch_array( mysqli_query($conn,$get_name))['Employee_Name']; 
    // while ($rown = mysqli_fetch_array($got_name)) {
        // $name = $rown['Employee_Name'];
    // }
	// echo $got_name; exit;
  $htm.= '<table  width ="100%" cellpadding="6" border="0" style="background-color:white;" id="patients-list">';
    $htm.= "<tr>
             <td colspan=2 style='text-align:center'><img src='branchBanner/branchBanner.png' width='100%' /></td>
          </tr>"; 
	$htm.= "<tr>
          <td colspan=2 ><center><h3>HISTOPATHOLOGY DEPARTMENT</h3></center></td>
          </tr>"; 
	$htm.= "<tr>
          <td colspan=2 ><center><h3>MORTUARY CORPSE RELEASE REPORT</h3></center></td>
          </tr>";
	$htm.= "</table>";
	$htm.= '<table width ="100%" cellpadding="6" border="0" style="background-color:white;" id="patients-list">';
    $htm.= "<tr>
				<td width='50%' style='text-align:right;font-size:13px;'><b>NAME:</b></td><td width='50%' style='font-size:13px;'>" . ucwords(strtoupper($row['Patient_Name'])) . "</td>
		   </tr>"; 
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right;font-size:13px;'><b>MORTUARY ID #:</b></td><td style='text-align: left; font-size:13px;'>" . $row['Corpse_ID'] . "</td>
		 </tr>"; 
    $htm.= "<tr>
         <td width='50%' style='text-align:right;font-size:13px;'><b>AGE:</b></td><td width='50%' style='font-size:13px;'>" . $age . "</td>
    </tr>"; 
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right;font-size:13px;'><b>GENDER:</b></td><td style='text-align: left; font-size:13px;'>" . ucwords(strtoupper($row['Gender'])) . "</td>
		 </tr>";
         $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right;font-size:13px;'><b>PLACE OF DEATH:</b></td><td style='text-align: left; font-size:13px;'>" . ucwords(strtoupper($row['Place_Of_Death'])) . "</td>
		 </tr>";
	/*
    $Date_In = $row['Date_In'];
    $Time_Out = $row['Time_Out'];
    $days = floor((strtotime($Time_Out) - strtotime($Date_In)) / 31556926) . " Years";
    if ($days == 0) {
        $date1 = new DateTime($Time_Out);
        $date2 = new DateTime($Date_In);
        $diff = $date1->diff($date2);
        $days = $diff->m . " Months";
    }
    if ($days == 0) {
        $date1 = new DateTime($Time_Out);
        $date2 = new DateTime($Date_In);
        $diff = $date1->diff($date2);
        $days = $diff->d . " Days";
    };*/
    $barcode=$row['Corpse_ID'];
    $Date_In = $row['Admission_Date_Time'];
	$Time_Out = $row['Time_Out'];
	// $Date_In = strtotime($Date_In);
	// $Time_Out = strtotime($Time_Out);
	// $Date_In = date('Y-m-d',$Date_In);
	// $Time_Out = date('Y-m-d',$Time_Out);
	$date1 = new DateTime($Date_In);
	$date2 = new DateTime($Time_Out);
//echo $Date_In; exit;
        echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>helo world";
$diff = $date2->diff($date1)->format("%a");
if($diff == 0){$diff=1;}
	echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>helo world";
    $htm.= "<tr>
			<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>DATE IN:</b></td>
            <td style='text-align: left; font-size:13px;'>" . $Date_In . "</td>
		</tr>";
    $htm.= "<tr>
			<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>DATE OUT:</b></td>
            <td style='text-align: left; font-size:13px;'>" . $Time_Out . "</td>
		 </tr>";
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>N<u>o</u> OF DAYS :</b></td>
                <td style='text-align: left; font-size:13px;'>" .$diff ."</td>
		
		  </tr>";
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>TAKEN BY:</b></td>
                <td style='text-align: left; font-size:13px;'>" . ucwords(strtoupper($row['Taken_By'])) . "</td>
		 </tr>"; 
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>NAME:</b></td>
                <td style='text-align: left; font-size:13px;'>" . ucwords(strtoupper($row['Kin_Out'])) . "</td>
		 </tr>";
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>RELATIONSHIP:</b></td>
                <td style='text-align: left; font-size:13px;'>" . ucwords(strtoupper($row['Kin_Out_Relationship'])) . "</td>
				
		 </tr>";
    $htm.= "<tr>
         <td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>NIDA IDENTIFICATION N<u>o</u>:</b></td>
         <td style='text-align: left; font-size:13px;'>" . ucwords(strtoupper($row['nida_id'])) . "</td>
         
        </tr>"; 
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>ADDRESS:</b></td>
                <td style='text-align: left; font-size:13px;'>" . ucwords(strtoupper($row['Kin_Out_Address'])) . "</td>
		 </tr>";  
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>PHONE:</b></td>
                <td style='text-align: left; font-size:13px;'>" . $row['Kin_Out_Phone'] . "</td>
		 </tr>";
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>VEHICLE N<u>o</u> OUT:</b></td>
                <td style='text-align: left; font-size:13px;'>" . ucwords(strtoupper($row['Vehicle_No_Out'])) . "</td>
		 </tr>";
    $htm.= "<tr><td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>BURRIAL PERMIT / BIRTH CERTIFICATE N<u>o</u>: </b></td>
    <td style='text-align: left; font-size:13px;'>" . $row['Death_Certificate']."</td>
		 </tr>";
         $htm.= "<tr><td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>PLACE OF BURRIAL:</b></td>
    <td style='text-align: left; font-size:13px;'>" . $row['Burial_Location']."</td>
		 </tr>";
         $htm.= "<tr><td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>RELATIVE'S TEN CELL LEADER: </b></td>
    <td style='text-align: left; font-size:13px;'>" . $row['Relative_Cell_Leader']."</td>
		 </tr>";
    $htm.= "<tr><td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>DEATH CERTIFICATE GIVEN BY</b></td>
    <td style='text-align: left; font-size:13px;'>" . "________________________________________". "</td>
		 </tr>";
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>SIGNATURE:</b></td>
                <td style='text-align: left; font-size:13px;'>" . "________________________________________". "</td>
		 </tr>"; 
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>AUTHORIZED BY (MORTUARY STAFF) :</b></td>
                <td style='text-align: left; font-size:13px;'>" . ucwords(strtoupper($name)) . "</td>
		 </tr>"; 
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>SIGNATURE:</b></td>
                <td style='text-align: left; font-size:13px;'>" . "________________________________________". "</td>
		 </tr>";
    $htm.= "<tr>
				<td style='padding-bottom:5px;text-align:right; font-size:13px;'><b>RELATIVE THUMB/ SIGNATURE</b></td>
                <td style='text-align: left; font-size:13px;'>" . "________________________________________". "</td>
		 </tr>";
    $htm.= "<tr>
				<td  style='padding-bottom:5px;text-align:right; font-size:13px;'><br/></td><td style='text-align: left; font-size:13px;'>" . "</td>
			</tr>";
    // $htm.= "<tr>
	// 			<td  style='padding-bottom:5px;text-align:right; font-size:13px;'><br/></td><td style='text-align: left; font-size:13px;'>" . "</td>
	// 		</tr>";
    
    $htm.= "<tr width='10' >
				<td  style='padding-bottom:5px;text-align:right;  font-size:15px;'><b>OFFICIAL STAMP: &nbsp;&nbsp;&nbsp;&nbsp; </b></td><td style='text-align: left; font-size:15px;'>"."</td>
				<td  style='padding-bottom:5px;text-align:right'><br/></td><td style='text-align: left; font-size:15px;'>" . "</td>
		 </tr>";   
 
 $htm.='';
 $htm.= "</table>";
    include("MPDF/mpdf.php");
        
    $mpdf=new mPDF('','A4',0,'',12.7,12.7,12,12.7,8,8); 
    $mpdf->SetFooter('{DATE d-m-Y}                                                 Powered by GPITG');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();

}  
   
}
?>

