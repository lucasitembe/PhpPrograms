<?php
   include("./includes/connection.php");
$Branch_ID = 0;
$Gender = '';
$Region = '';
$Hospital_Ward_ID = 0;

$Guarantor_Name = "All";
$Branch_Name='All';
$row_num = '';

$filter = "AND Admission_Status != 'Discharged'";

if (isset($_GET['Sponsor'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Gender = filter_input(INPUT_GET, 'Gender');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
    $row_num = filter_input(INPUT_GET, 'row_num');
    
    $ptn_type= filter_input(INPUT_GET, 'patient_type');
    $mil_rank = filter_input(INPUT_GET, 'mil_rank');
    $unit = filter_input(INPUT_GET, 'unit');
}


// if(!empty($unit) && $unit!='All'){
//     $filter .="  AND pr.military_unit='$unit'";
// }
// if(!empty($mil_rank) && $mil_rank!='All'){
//     $filter .="  AND pr.rank='$mil_rank'";
// }
if (!empty($ptn_type) && $ptn_type != 'All') {
    $filter .="  AND a.Admission_Status='$ptn_type'";
}
if (empty($Date_From) && empty($Date_To)) {
    $betweenDate = "";
} else {
    $betweenDate = "<b>FROM</b> " . $Date_From . " <b>TO</b> " . $Date_To;
}


if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
    
    $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn)."query 0");

        $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
}

session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if (!empty($ward) && $ward != 'All') {
    $filter .= " AND a.Hospital_Ward_ID  = $ward";
}

if (!empty($Gender) && $Gender != 'All') {
    $filter .= " AND  pr.Gender = '$Gender'";
}

if (!empty($Branch_ID) && $Branch_ID != 'All') {
    $filter .= " AND  hw.Branch_ID = '$Branch_ID'";
    
    $select_branch = "SELECT * FROM tbl_branches WHERE Branch_ID = '$Branch_ID'";
           $result = mysqli_query($conn,$select_branch) or die(mysqli_error($conn)."query 1");
           $Branch_Name = mysqli_fetch_assoc($result)['Branch_Name'];
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if(isset($row_num) && !empty($row_num)){
   
    if($row_num=='ALL'){
      $filter .="";
    } else {
       // $filter .="LIMIT $row_num";
    }
}



$select_Filtered_Patients = "SELECT 
					sp.Guarantor_Name, 
					pr.Gender,pr.Region,
					pr.Registration_ID,
					pr.Date_Of_Birth,
					pr.patient_type,
					pr.rank,
                    Bed_Name,
                    room_name,
					hw.Hospital_Ward_Name,
					pr.Patient_Name,
					hw.Hospital_Ward_Name,
					a.Bed_ID,
					a.Admission_Status,
					a.Kin_Name,
					a.Admission_Date_Time,
					a.Kin_Phone,a.Admission_Status,
                    a.Admision_ID
				FROM 	
					tbl_hospital_ward hw,
					tbl_patient_registration pr,
					tbl_sponsor sp,
					tbl_admission a, tbl_ward_rooms wr
					WHERE a.registration_id = pr.registration_id AND pr.Sponsor_ID = sp.Sponsor_ID AND a.ward_room_id=wr.ward_room_id AND   a.ward_room_id<>'0' AND  Admission_Status <> 'Discharged' AND 	hw.Hospital_Ward_ID = a.Hospital_Ward_ID $filter AND ward_type='ordinary_ward'";



$htm = '<table width ="100%" border="0" style="background-color:white;" class="nobordertable">
          <tr>
             <td style="text-align:center"><img src="branchBanner/branchBanner.png" width="100%" /></td>
          </tr>
          <tr>
             <td  style="text-align:center"><b>CURRENT INPATIENT LIST</b><br/> </td>
          </tr>';

  if(!empty($betweenDate)){
       $htm .= '<tr>
             <td  style="text-align:center">'.$betweenDate.'<br/><br/></td>
          </tr>';
  }
  
          $htm .='<tr>
             <td  style="text-align:center"><b>Sponsor: </b>'.$Guarantor_Name.' <b> | <b> Branch:</b> '.$Branch_Name. '<br/><br/></td>
          </tr>
        </table>';

$htm .= '<table width ="100%" border="0" style="background-color:white;" id="patients-list">';
$htm .= '<thead>
            <tr>
                <td width="3%"><b>Sn</b></td>
                <td width="15%"><b>Patient Name</b></td>
                <td width="15%"><b>Patient Type</b></td>
                <td width="9%" style="text-align: left;"><b>Patient #</b></td>
                <td width="8%" style="text-align: left;"><b>Sponsor</b></td>
                <td width="8%" style="text-align: left;"><b>Admission_ID</b></td>
                <td width="9%" style="text-align: left;"><b>Ward</b></td>
                <td style="text-align: left; width:9%;">Room</td>
                <td width="7%" style="text-align: left;"><b>Bed</b></td>
                <td width="6%" style="text-align: left;"><b>Gender</b></td>
                <td width="6%" style="text-align: center;"><b>Age</b></td>
                <td width="10%" style="text-align: center;"><b>Admitted</b></td>
                <td width="11%" style="text-align: left;"><b>Next of kin</b></td>
                <td width="10%" style="text-align: left;"><b>Kin phone</b></td>
                <td width="10%" style="text-align: left;"><b>Region</b></td>
                <td width="10%" style="text-align: left;"><b>Admission Status</b></td>
            </tr>
                </thead>';

$results = mysqli_query($conn,$select_Filtered_Patients)or die(mysqli_error($conn))."query 2";
$temp = 1;

$Today_Date = mysqli_query($conn,"select now() as today");

while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

while ($row = mysqli_fetch_array($results)) {
    $admission_date = date("j/m/Y. g:i a", strtotime($row['Admission_Date_Time']));
    if($row['Admission_Status']=="pending"){
        $background="background:green;color:white;padding:5px;";
    }else{
         $background="";
    }
    

    $htm .= "<tr $background >";
    $htm .= '<td style="text-align:center;">'.$temp .'.</td>';
    $htm .= "<td style='font-size:13px;'>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['patient_type'] . "</td>";
    // $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['rank'] . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['Registration_ID'] . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['Guarantor_Name'] . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['Admision_ID'] . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['Hospital_Ward_Name'] . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['room_name'] . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['Bed_Name'] . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['Gender'] . "</td>";

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
    $htm .= "<td style='text-align: center; font-size:12px;'>" . $age . "</a></td>";
    $htm .= "<td style='text-align: center; font-size:12px;'>" . $admission_date . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . ucwords(strtolower($row['Kin_Name'])) . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['Kin_Phone'] . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['Region'] . "</td>";
    $htm .= "<td style='text-align: left; font-size:12px;'>" . $row['Admission_Status'] . "</td>";
    $htm .= "</tr>";
    
    $temp++;
}
    $htm .= "<tr><td colspan='15' style='text-align: right;'><b> TOTAL ADMITTED :</b> ".number_format($temp-1)."</td></tr>";
    $htm .= "</table></center>";
   
    //SIMPLE EXCELL FUNCTION STARTS HERE 
    header("Content-Type:application/xls");
    header("content-Disposition: attachement; filename= currentInpatient.xls");
    echo $htm;

?>