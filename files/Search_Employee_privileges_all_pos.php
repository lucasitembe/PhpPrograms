<?php
include("./includes/connection.php");
//dose_ID:dose_ID
echo "<link rel='stylesheet' href='fixHeader.css'>";

if (isset($_POST['privirage'])) {
    $privirage = $_POST['privirage'];
} else {
    $privirage = '';
}

$filteremployeeprivirage = "";
if ($filteremployeeprivirage != 'all') {
    $filteremployeeprivirage = "AND pr.$privirage='yes'";
} else {
    $filteremployeeprivirage = "";
}
?>
<fieldset style="overflow-y: scroll; height: 380px; background-color: white;" id="Employee_Area">
    <legend align="left"><b>LIST OF EMPLOYEES</b></legend>
    <table width="100%" class="fixTableHead">
        <?php
        $temp = 0;
        $Title = '
        <thead>
            <tr style="background-color: #ccc;">
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
        </thead>';

        echo $Title;
        $select = mysqli_query($conn, "select * from tbl_employee emp, tbl_department dep,tbl_privileges pr WHERE
                                emp.department_id = dep.department_id $filteremployeeprivirage AND Account_Status='active' AND emp.Employee_ID=pr.Employee_ID AND
                                emp.Employee_Name <> 'crdb' order by Employee_Name limit 500") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            if (isset($_GET['HRWork']) && $_GET['HRWork'] == 'true') {
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
            } else {
                while ($row = mysqli_fetch_array($select)) {
                    echo "<tr id='sss'><td>" . ++$temp . "<td><a href='#' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>" . strtoupper($row['Employee_Name']) . "</td>";
                    echo "<td><a href='#'  style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>" . strtoupper($row['Employee_Type']) . "</td>";
                    echo "<td><a href='#'  style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>" . strtoupper($row['Employee_Number']) . "</td>";
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
                        // echo $Title;
                    }
                }
            }
        }
        ?>
    </table>