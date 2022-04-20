<?php
@session_start();
include("./includes/connection.php");
$Branch_ID = 0;
$Gender = '';
$Region = '';
$Hospital_Ward_ID = 0;
$end_date = '';
$start_date = '';
$row_num = '100';

$Guarantor_Name = "All";


$Printed_by_Name = $_SESSION['userinfo']['Employee_Name'];
$Printed_by_Name = ucwords(strtolower($Printed_by_Name));

$filter = "   AND DATE(pt.Created_Date_Time)=DATE(NOW())";

if (isset($_GET['Branch_ID'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $Gender = filter_input(INPUT_GET, 'Gender');
    $Branch_ID = filter_input(INPUT_GET, 'Branch_ID');
    $row_num = filter_input(INPUT_GET, 'row_num');
}

if (empty($Date_From) && empty($Date_To)) {
    $betweenDate = "<b>Today </b>" . date('Y-m-d');
} else {
    $betweenDate = "<b>FROM</b> " . $Date_From . " <b>TO</b> " . $Date_To;
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND pt.Created_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}
if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";

    $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));

    $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
}


if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}

if (!empty($ward) && $ward != 'All') {
    $filter .= " AND hw.Hospital_Ward_ID  = $ward";
}

if (!empty($Gender) && $Gender != 'All') {
    $filter .= " AND  pr.Gender = '$Gender'";
}

if (!empty($Branch_ID) && $Branch_ID != 'All') {
    $filter .= " AND  hw.Branch_ID = '$Branch_ID'";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}
if(isset($row_num) && !empty($row_num)){
   
    if($row_num=='ALL'){
      $filter .="";
    } else {
        $filter .=" LIMIT $row_num";
    }
}


$select_Filtered_Patients = "
SELECT sp.Guarantor_Name,pr.Gender,pr.Phone_Number,pr.Registration_ID,pr.Date_Of_Birth,hw.Hospital_Ward_Name,pr.Patient_Name,hw.Hospital_Ward_Name,e.Employee_Name,
        pt.Created_Date_Time,pt.Received_Date,pt.reasson_for_tranfer, e.Employee_Name AS 'TRANSFER',e.Employee_Name AS 'RECEIVER',pt.Employee_ID AS 'EMPLOYEE NAME',pt.Received_By AS 'RECEIVED BY',
        pt.room_id,pt.ward_from
              
                FROM 	
					tbl_hospital_ward hw,tbl_patient_registration pr,tbl_sponsor sp,tbl_employee e, tbl_patient_transfer_details pt
					WHERE 
						pt.registration_id = pr.registration_id AND						
						pr.Sponsor_ID = sp.Sponsor_ID AND 
                        hw.Hospital_Ward_ID = pt.Hospital_Ward_ID AND                        
                        pt.Employee_ID = e.Employee_ID  $filter";



$htm = '<table width ="100%" border="0" style="background-color:white;" class="nobordertable">
          <tr>
             <td style="text-align:center"><img src="./branchBanner/branchBanner.png" width="100%" /></td>
          </tr>
          <tr>
             <td  style="text-align:center"><b>PATIENT WARD TRANSFER LIST</b><br/> </td>
          </tr>';

if (!empty($betweenDate)) {
    $htm .= '<tr>
             <td  style="text-align:center">' . $betweenDate . '<br/><br/></td>
          </tr>';
}

$htm .='<tr>
             <td  style="text-align:center"><b>Sponsor: </b>' . $Guarantor_Name . ' <br/></td>
          </tr>
        </table><br/>';

$htm.= '<table width ="100%" border="0" style="background-color:white;" id="patients-list">';
$htm.= '<thead>
		<tr>
        <td width="3%"><b>Sn</b></td>
        <td width="10%"><b>Patient Name</b></td>
        <td width="8%" style="text-align: left;"><b>Patient#</b></td>
        <td width="8%" style="text-align: left;"><b>Gender</b></td>
        <td width="8%" style="text-align: left;"><b>Age</b></td>
        <td width="8%" style="text-align: left;"><b>Contacts</b></td>
        <td width="8%" style="text-align: left;"><b>Sponser</b></td>
        <td width="8%" style="text-align: left;"><b>Ward In</b></td>
        <td width="8%" style="text-align: left;"><b>Received By</b></td>
        <td width="7%" style="text-align: left;"><b>Received Date</b></td>
        <td width="6%" style="text-align: left;"><b>Ward From</b></td>
        <td width="8%" style="text-align: center;"><b>Tranfered By</b></td>
        <td width="10%" style="text-align: center;"><b>Transfered Date</b></td>
        <td width="15%" style="text-align: left;"><b>Reason</b></td>
		</tr>
        </thead>';

$temp = 1;

$Today_Date = mysqli_query($conn,"select now() as today");

$results = mysqli_query($conn,$select_Filtered_Patients);
$temp = 1;

$Today_Date = mysqli_query($conn,"select now() as today");

while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
while ($row = mysqli_fetch_assoc($results)) {

    $rc = $row['RECEIVED BY'];
    $receive = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$rc'");
    $receiver = mysqli_fetch_assoc($receive)['Employee_Name'];

    $wi = $row['ward_from'];
    $receive1 = mysqli_query($conn,"select Hospital_Ward_Name from tbl_hospital_ward where Hospital_Ward_ID = '$wi'");
    $wardin = mysqli_fetch_assoc($receive1)['Hospital_Ward_Name'];


    $htm.= '<tr>';
    $htm.= '<td style="text-align:center;">' . $temp . '</td>';
    $htm.= "<td style='font-size:13px;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Registration_ID'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Gender'] . "</td>";

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
    $htm.= "<td style='text-align: center; font-size:12px;'>" . $age . "</td>";
    $htm.= "<td style='text-align: center; font-size:12px;'>" . $row['Phone_Number']. "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" .  $row['Guarantor_Name'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Hospital_Ward_Name'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['TRANSFER'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Received_Date'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $wardin . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $receiver . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['Created_Date_Time'] . "</td>";
    $htm.= "<td style='text-align: left; font-size:12px;'>" . $row['reasson_for_tranfer'] . "</td>";
    
    $htm.= "</tr>";

    $temp++;
}
$htm .= "<tr><td colspan='14' style='text-align: right;'><b> TOTAL  : " . number_format($temp - 1) . "</td></tr>";
$htm .= "</table></center>";


    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Printed_by_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
exit;
?>