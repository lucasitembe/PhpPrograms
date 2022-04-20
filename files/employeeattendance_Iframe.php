<?php
@session_start();
include("./includes/connection.php");
// $filter = ' AND DATE(pl.Transaction_Date_And_Time)=DATE(NOW())'; 

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Search_Employee = filter_input(INPUT_GET, 'Search_Employee');
$Search_Employee_Number = filter_input(INPUT_GET, 'Search_Employee_Number'); //
$chkinstatus = filter_input(INPUT_GET, 'chkinstatus');

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

//echo $filter;exit;

$n = 1;


echo '<center><table width ="100%" id="myEmployee">';
echo " <thead><tr ><th style='width:5%;'>SN</th>
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


    echo "<tr ><td >" . $n . "</td>";
    echo "<td>" . ucwords(strtolower($row['Employee_Name'])) . "</td>";
    echo "<td>" . $row['Employee_Type'] . "</td>";
    echo "<td>" . $row['Employee_Number'] . "</td>";
    echo "<td>" . $row['Department_Name'] . "</td>";
    echo "<td>" . $row['Branch_Name'] . "</td>";
    echo "<td>" . $row['check_in'] . "</td>";
    echo "<td>" . $row['check_out'] . "</td>";

    echo "</tr>";

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
    

    echo "<tr ><td >" . $n . "</td>";
    echo "<td>" . ucwords(strtolower($row['Employee_Name'])) . "</td>";
    echo "<td>" . $row['Employee_Type'] . "</td>";
    echo "<td>" . $row['Employee_Number'] . "</td>";
    echo "<td>" . $row['Department_Name'] . "</td>";
    echo "<td>" . $row['Branch_Name'] . "</td>";
    echo "<td>" . $row['check_in'] . "</td>";
    echo "<td>" . (($row['check_out'] == '0000-00-00 00:00:00')?'Not Out': $row['check_out']) . "</td>";

    echo "</tr>";

    $n++;
}
}


echo '</table></center>';
