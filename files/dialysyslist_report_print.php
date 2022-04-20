<?php include ("./includes/connection.php");
$Branch_ID = 0;
$Gender = '';
$Region = '';
$Hospital_Ward_ID = 0;
$end_date = '';
$start_date = '';
// $filter = "   (Check_In_Type ='Procedure' OR Check_In_Type ='Dialysis') AND il.Status='served' AND DATE(il.Transaction_Date_And_Time)=DATE(NOW())";
// if (isset($_GET['Sponsor'])) {
//     $Date_From = filter_input(INPUT_GET, 'Date_From');
//     $Date_To = filter_input(INPUT_GET, 'Date_To');
//     $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
//     $Sponsor = filter_input(INPUT_GET, 'Sponsor');
//     $Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
// }
// if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
//     $filter = "  Check_In_Type ='Dialysis' AND il.Status='served' AND il.Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
// }
// if (!empty($Sponsor) && $Sponsor != 'All') {
//     $filter.= "  AND sp.Sponsor_ID=$Sponsor";
// }
// if (!empty($Patient_Name)) {
//     $filter.= "  AND pr.Patient_Name like '%$Patient_Name%'";
// }
// if (!empty($Patient_Number)) {
//     $filter.= "  AND pr.Registration_ID = '$Patient_Number'";
// }
// $Today_Date = mysqli_query($conn, "select now() as today");
// while ($row = mysqli_fetch_array($Today_Date)) {
//     $original_Date = $row['today'];
//     $new_Date = date("Y-m-d", strtotime($original_Date));
//     $Today = $new_Date;
// }
// if (empty($Date_From) && empty($Date_To)) {
//     $betweenDate = "<b>Today </b>" . date('Y-m-d');
// } else {
//     $betweenDate = "<b>FROM</b> " . $Date_From . " <b>TO</b> " . $Date_To;
// }
// $Guarantor_Name = "All";
// if (!empty($Sponsor) && $Sponsor != 'All') {
//     $filter.= "  AND sp.Sponsor_ID=$Sponsor";
//     $rs = mysqli_query($conn, "SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));
//     $Guarantor_Name = mysqli_fetch_assoc($rs) ['Guarantor_Name'];
// }
// if (!empty($ward) && $ward != 'All') {
//     $filter.= " AND a.Hospital_Ward_ID  = $ward";
// }

$end_date = '';
$start_date = '';


// $filter = "   (Check_In_Type ='Dialysis' OR (Check_In_Type ='Dialysis' OR Sub_Department_ID IN (SELECT Sub_Department_ID FROM tbl_sub_department WHERE Department_ID IN (SELECT Department_ID FROM tbl_department WHERE Department_Location='Dialysis')))) AND il.Dialysis_Status=1 AND DATE(il.ServedDateTime)=DATE(NOW())";
// $filter = "   (Check_In_Type ='Procedure' OR Check_In_Type ='Dialysis') AND DATE(il.Transaction_Date_And_Time)=DATE(NOW())";

if(isset($_GET['Sponsor'])) {
    // $Date_From = filter_input(INPUT_GET, 'Date_From');
    // $Date_To = filter_input(INPUT_GET, 'Date_To');
    // $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    // $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    // $Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
}
if(isset($_GET['Date_From'])&&isset($_GET['Date_To'])){
    $Sponsor=$_GET['Sponsor'];
    $Date_From =$_GET['Date_From'];
    $Date_To =$_GET['Date_To'];
    $Patient_Name =$_GET['Patient_Name'];
    $Sponsor =$_GET['Sponsor'];
    $Patient_Number =$_GET['Patient_Number'];
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .=" AND sp.Sponsor_ID=$Sponsor";
}

if (!empty($Patient_Name)) {
    $filter .=" AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_Number)) {
    $filter .=" AND pr.Registration_ID = '$Patient_Number'";
}
$temp = 1;
$htm = '<table width ="100%" border="0" style="background-color:white;" class="nobordertable">
          <tr>
             <td style="text-align:center"><img src="branchBanner/branchBanner.png" width="100%" /></td>
          </tr>
          <tr>
             <td  style="text-align:center"><b>DIALYSYS CONSULTED PATIENT LIST</b><br/> </td>
          </tr>';
if (!empty($betweenDate)) {
    $htm.= '<tr>
             <td  style="text-align:center">' . $betweenDate . '<br/><br/></td>
          </tr>';
}
$htm.= '<tr>
          <td  style="text-align:center"><b>Sponsor: </b>' . $Guarantor_Name . ' <b><br/></td>
        </tr>
        </table><br/>';
$htm.= '<center><table width ="100%" style="background-color:white;" id="patients-list">';
$htm.= "<thead>
<tr>
<td><b>SN</b></td>
<td><b>PATIENT NAME</b></td>
<td><b>REG NO</b></td>
        <td><b>SPONSOR</b></td>
            <td><b>DATE OF BIRTH</b></td>
            <td><b>AGE</b></td>
                <td><b>GENDER</b></td>
                    <td><b>PHONE NUMBER</b></td>
                        </tr></thead>";
$sn = 1;
// $select_Filtered_Patients = mysqli_query($conn, "SELECT 'cache' as Status_From,pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,
//                                            pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
//                                            pc.Payment_Cache_ID as payment_id,il.Payment_Item_Cache_List_ID,il.Process_Status as Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time,pc.consultation_id
//                                             FROM tbl_sponsor AS sp,tbl_item_list_cache as il,tbl_items as i,tbl_payment_cache as pc,tbl_patient_registration AS pr
//                                             WHERE i.Item_ID = il.Item_ID 
//                                             AND pc.Payment_Cache_ID = il.Payment_Cache_ID 
//                                             AND pr.Registration_ID =pc.Registration_ID
//                                             AND sp.Sponsor_ID =pr.Sponsor_ID
//                                             AND $filter GROUP BY pr.Registration_ID ORDER BY Transaction_Date_And_Time DESC ") or die(mysqli_error($conn));
        $select_Filtered_Patients = mysqli_query($conn,  "SELECT * FROM tbl_dialysis_details dd, tbl_patient_registration pr, tbl_sponsor sp WHERE dd.Patient_reg = pr.Registration_ID AND Attendance_Date BETWEEN '$Date_From' AND '$Date_To'$filter AND "
        . " pr.Sponsor_ID = sp.Sponsor_ID LIMIT 100") or die(mysqli_error($conn));

while ($row2 = mysqli_fetch_assoc($select_Filtered_Patients)) {
    $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
    $htm.= "<tr><td>" . $sn++ . "</td> <td>" . $row2['Patient_Name'] . "</td>";
    $htm.= "<td>" . $row2['Registration_ID'] . "</td>";
    $htm.= "<td>" . $row2['Guarantor_Name'] . "</td>";
    $htm.= "<td>" . $row2['Date_Of_Birth'] . "</td>";
    $htm.= "<td>" . $age . "</td>";
    $htm.= "<td>" . $row2['Gender'] . "</td>";
    $htm.= "<td>" . $row2['Phone_Number'] . "</td>";
    $htm.= "</tr>";;
    echo '
	';
}
$htm.= "</table></center>";
include ("MPDF/mpdf.php");
$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($htm, 2);
$mpdf->Output();
exit;