<?php
@session_start();
include("./includes/connection.php");
// $filter = ' AND DATE(pl.Transaction_Date_And_Time)=DATE(NOW())'; 

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Search_Employee = filter_input(INPUT_GET, 'Search_Employee');
$Search_Employee_Number = filter_input(INPUT_GET, 'Search_Employee_Number'); //
$chkinstatus = filter_input(INPUT_GET, 'chkinstatus');

$chkdtails='Checked In';

if($chkinstatus=='nochkin'){
  $chkdtails='Not Checked In';  
}

$filter = " DATE(at.check_in) = DATE(NOW()) ";  

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = " at.check_in BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
   // $filter = " at.check_in BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}


if (!empty($Search_Employee)) {
    $filter .="  AND e.Employee_Name like '%$Search_Employee%'";
}


if (!empty($Search_Employee_Number)) {
    $filter .="  AND e.Employee_ID = '$Search_Employee_Number'";
}

//$data .= $filter;exit;

$n = 1;
$data = "<table width ='100%' class='nobordertable'>
		    <tr>
                    <td width ='100%' > <img src='./branchBanner/branchBanner.png'></td></tr>
		    <tr><td style='text-align: center;'><span><b>Employee Attendance - $chkdtails</b></span></td></tr>";

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
                    $data .= " <tr><td style='text-align: center;'><b>From </b>" . $Date_To . " <b> To</b> " . $Date_From . "</td></tr>";
}else{
     $data .= " <tr><td style='text-align: center;'><b>Today</b></td></tr>";
}
          $data .= " </table>
		    ";

$data .= '<center><table width ="100%" id="myEmployee">';
$data .= " <thead><tr ><th style='width:5%;'>SN</th>
    <th><b>Employee NAME</b></th>
                <th><b>Employee Type</b></th>
                    <th><b>Employee #</b></th>
                        <th><b>Department</b></th>
                            <th><b>Branch Name</b></th>
                                <th><b>Check In</b></th>
                                <th><b>Check Out</b></th>
				</tr>
                                </thead>";

$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];

if($chkinstatus=='nochkin'){
  $sql = "SELECT e.Employee_ID,Employee_Name,Employee_Type,Employee_Number,Department_Name,b.Branch_Name,'' AS check_in,'' AS check_out FROM tbl_employee e 
          JOIN tbl_department d ON d.Department_ID=e.Department_ID 
          JOIN tbl_branch_employee be ON be.Employee_ID=e.Employee_ID 
          JOIN tbl_branches b ON b.Branch_ID=be.Branch_ID 
            "; 
  
  $select_Filtered_Patients = mysqli_query($conn,$sql) or die(mysqli_error($conn));


while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    $style = "";
    
    $chktosee=mysqli_query($conn,"SELECT employee_id FROM tbl_attendance at WHERE employee_id='".$row['Employee_ID']."' AND $filter") or die(mysqli_error($conn));


    $data .= "<tr ><td >" . $n . "</td>";
    $data .= "<td>" . ucwords(strtolower($row['Employee_Name'])) . "</td>";
    $data .= "<td>" . $row['Employee_Type'] . "</td>";
    $data .= "<td>" . $row['Employee_Number'] . "</td>";
    $data .= "<td>" . $row['Department_Name'] . "</td>";
    $data .= "<td>" . $row['Branch_Name'] . "</td>";
    $data .= "<td>" . $row['check_in'] . "</td>";
    $data .= "<td>" . $row['check_out'] . "</td>";

    $data .= "</tr>";

    $n++;
}
}else{
   $sql = "SELECT Employee_Name,Employee_Type,Employee_Number,Department_Name,b.Branch_Name,check_in,check_out FROM tbl_employee e 
          JOIN tbl_attendance at ON at.employee_id=e.Employee_ID 
          JOIN tbl_department d ON d.Department_ID=e.Department_ID 
          JOIN tbl_branch_employee be ON be.Employee_ID=e.Employee_ID 
          JOIN tbl_branches b ON b.Branch_ID=be.Branch_ID 
          WHERE 
        $filter
            ";  

$select_Filtered_Patients = mysqli_query($conn,$sql) or die(mysqli_error($conn));


while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    $style = "";
    

    $data .= "<tr ><td >" . $n . "</td>";
    $data .= "<td>" . ucwords(strtolower($row['Employee_Name'])) . "</td>";
    $data .= "<td>" . $row['Employee_Type'] . "</td>";
    $data .= "<td>" . $row['Employee_Number'] . "</td>";
    $data .= "<td>" . $row['Department_Name'] . "</td>";
    $data .= "<td>" . $row['Branch_Name'] . "</td>";
    $data .= "<td>" . $row['check_in'] . "</td>";
    $data .= "<td>" . $row['check_out'] . "</td>";

    $data .= "</tr>";

    $n++;
}
}


$data .= '</table></center>';

include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output();

