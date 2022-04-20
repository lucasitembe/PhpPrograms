<?php
 include("./includes/connection.php");
//dose_ID:dose_ID
if (isset($_POST['privirage'])) {
    $privirage = $_POST['privirage'];
} else {
    $privirage= '';
}

$filteremployeeprivirage="";
	if($filteremployeeprivirage!='all'){
        $filteremployeeprivirage="AND pr.$privirage='yes'";
    }else{
        $filteremployeeprivirage="";
    }
    
     ?>
<fieldset style="overflow-y: scroll; height: 380px; background-color: white;" id="Employee_Area">
    <legend align="left"><b>LIST OF EMPLOYEES</b></legend>
    <table width="100%">
    <?php
        $temp = 0;
        $Title = '<tr><td colspan="7"><hr></td></tr>
        <tr>
            <td width ="5%"><b>SN</b></td>
            <td><b>EMPLOYEE NAME</b></td>
            <td><b>EMPLOYEE PFNO</b></td>
            <td width="15%"><b>DESIGNATION</b></td>
            <td width="15%"><b>EMPLOYEE TITLE</b></td> 
            <td width="15%"><b>JOB CODE</b></td>
            <td width="15%"><b>DEPARTMENT</b></td>
        </tr>
        <tr><td colspan="7"><hr></td></tr>';

        echo $Title;
        $select = mysqli_query($conn,"select * from tbl_employee emp, tbl_department dep,tbl_privileges pr WHERE
                                emp.department_id = dep.department_id $filteremployeeprivirage AND Account_Status='active' AND emp.Employee_ID=pr.Employee_ID AND
                                emp.Employee_Name <> 'crdb' order by Employee_Name limit 500") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
            while ($row = mysqli_fetch_array($select)) {
                echo "<tr id='sss'><td>".++$temp."<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Name'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Number'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Type'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Title'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Job_Code'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm&HRWork=true' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Department_Name'])."</td>";
                if($temp%20 == 0){
                    echo $Title;
                }
            }}else{
                while ($row = mysqli_fetch_array($select)) {
                echo "<tr id='sss'><td>".++$temp."<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Name'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Type'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Number'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Title'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Employee_Job_Code'])."</td>";
                echo "<td><a href='editemployee.php?Employee_ID=".$row['Employee_ID']."&EditEmployee=EditEmployeeThisForm' target='_parent' style='text-decoration: none;' title='CLICK TO EDIT EMPLOYEE'>".strtoupper($row['Department_Name'])."</td>";
                if($temp%20 == 0){
                    echo $Title;
                }
            }
            }
        }
    ?>
    </table>