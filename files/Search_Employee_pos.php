<?php
include("./includes/connection.php");

if (isset($_GET['Employee_Name'])) {
    $Employee_Name = str_replace(" ", "%", $_GET['Employee_Name']);
} else {
    $Employee_Name = '';
}
if (isset($_GET['Account_Status'])) {
    $Account_Status = $_GET['Account_Status'];
} else {
    $Account_Status = '';
}

if (isset($_GET['Department_ID'])) {
    $Department_ID = $_GET['Department_ID'];
} else {
    $Department_ID = '0';
}
if (isset($_GET['Designation'])) {
    $Designation = $_GET['Designation'];
} else {
    $Designation = 'All Designation';
}
if (isset($_GET['Employee_PFNO'])) {
    $Employee_PFNO = $_GET['Employee_PFNO'];
} else {
    $Employee_PFNO = '';
}
$filter = "";
if ($Department_ID != '0') {
    $filter .= " emp.Department_ID = '$Department_ID' and ";
}
if ($Designation != 'All Designation') {
    $filter .= " emp.Employee_Type = '$Designation' and ";
} else {
    $filter .= " ";
}
if ($Employee_PFNO != '') {
    $filter .= " emp.Employee_Number = '$Employee_PFNO' and ";
}
if ($Employee_Name != null && $Employee_Name != '') {
    $filter .= " emp.Employee_Name like '%$Employee_Name%' and ";
}
if ($Account_Status != null && $Account_Status != '') {
    $filter .= " emp.Account_Status = '$Account_Status' and ";
}
$is_hr = "";
if (isset($_GET['HRWork']) && $_GET['HRWork'] == 'true') {
    $is_hr = "&HRWork=true";
}
?>
<legend align="left"><b>LIST OF EMPLOYEES</b></legend>
<table width="100%">
    <?php
    $temp = 0;
    $Title = '<tr><td colspan="9"><hr></td></tr>
    <tr>
        <td width ="5%"><b>SN</b></td>
        <td><b>EMPLOYEE NAME</b></td>
        <td><b>EMPLOYEE PFNO</b></td>
        <td width="15%"><b>DESIGNATION</b></td>
        <td width="15%"><b>EMPLOYEE TITLE</b></td> 
        <td width="15%"><b>JOB CODE</b></td>
        <td width="15%"><b>DEPARTMENT</b></td>
        <td width="15%"><b>ActivatedBy</b></td>
        <td width="15%"><b>Activated Date</b></td>
    </tr>
    <tr><td colspan="9"><hr></td></tr>';

    echo $Title;
    $select = mysqli_query($conn, "select * from tbl_employee emp, tbl_department dep where
                            emp.department_id = dep.department_id and
                            $filter
                            emp.Employee_Name <> 'crdb' order by Employee_Name limit 500") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select)) {
            echo "<tr id='sss'><td>" . ++$temp . "<td><a href='#' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>" . strtoupper($row['Employee_Name']) . "</td>";
            echo "<td><a href='#'  style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>" . strtoupper($row['Employee_Number']) . "</td>";
            echo "<td><a href='#'  style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>" . strtoupper($row['Employee_Type']) . "</td>";
            echo "<td><a href='#'  style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>" . strtoupper($row['Employee_Title']) . "</td>";
            echo "<td><a href='#'  style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>" . strtoupper($row['Employee_Job_Code']) . "</td>";
            echo "<td><a href='#'  style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>" . strtoupper($row['Department_Name']) . "</td>";
            $id = $row['Employee_ID'];
            $qly = mysqli_query($conn, "select ByUserName,activatedDateAndTime from pos_users where UserID='$id'") or die(mysqli_error($conn));
            if (mysqli_num_rows($qly) > 0) {
                $objcts = mysqli_fetch_assoc($qly);
                $edname = $objcts['ByUserName'];
                $activatedDateAndTime = $objcts['activatedDateAndTime'];
                echo "<td><a href='#'  style='text-decoration: none;'>" . strtoupper($edname) . "</td>";
                echo "<td><a href='#'  style='text-decoration: none;'>" . $activatedDateAndTime . "</td>";
                echo "<td><a href='cashierposconfiguration.php?action=deactivate&Employee_ID=" . $row['Employee_ID'] . "' class='art-button btn-danger'>Deactivate</a></td>";
            } else {
                echo "<td></td>";
                echo "<td></td>";
                echo "<td><a href='cashierposconfiguration.php?action=activate&Employee_ID=" . $row['Employee_ID'] . "' class='art-button-green'>Activate</a></td>";
            }
            if ($temp % 20 == 0) {
                echo $Title;
            }
        }
    }
    ?>
</table>