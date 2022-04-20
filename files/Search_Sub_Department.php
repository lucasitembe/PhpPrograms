<?php
	include("./includes/connection.php");

	if(isset($_GET['Sub_Department_Name'])){
		$Sub_Department_Name = str_replace(" ", "%", $_GET['Sub_Department_Name']);
	}else{
		$Sub_Department_Name = '';
	}

	if(isset($_GET['Department_ID'])){
		$Department_ID = $_GET['Department_ID'];
	}else{
		$Department_ID = '0';
	}

	$filter = '';
	if($Department_ID != '0'){
		$filter .= " and dep.Department_ID = '$Department_ID' ";
	}

	if($Sub_Department_Name != null && $Sub_Department_Name != ''){
		$filter .= " and sdep.Sub_Department_Name like '%$Sub_Department_Name%' ";
	}
?>
<legend align="left"><b>SUB DEPARTMENTS LIST</b></legend>
<table width = "100%">
<?php
    $Title = '<tr><td colspan="4"><hr></td></tr>
                <tr>
                    <td width="5%"><b>SN</b></td>
                    <td><b>SUB DEPARTMENT NAME</b></td>
                    <td width="30%"><b>DEPARTMENT NAME</b></td>
                    <td width="15%" style="text-align: center;"><b>SUB DEPARTMENT STATUS</b></td>
                </tr>
                <tr><td colspan="4"><hr></td></tr>';
    echo $Title;

    $select = mysqli_query($conn,"select * from tbl_department dep, tbl_sub_department sdep
                            where dep.department_id = sdep.department_id $filter order by dep.department_id") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        $temp = 0;
        while ($row = mysqli_fetch_array($select)) {
            echo "<tr id='sss'>
                    <td>".++$temp."</td>
                    <td><a href='editsubdepartment.php?Sub_Department_ID=".$row['Sub_Department_ID']."&EditSubDepartment=EditSubDepartmentThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Sub_Department_Name']))."</a></td>
                    <td><a href='editsubdepartment.php?Sub_Department_ID=".$row['Sub_Department_ID']."&EditSubDepartment=EditSubDepartmentThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Department_Name']))."</a></td>
                    <td style='text-align: center;'><a href='editsubdepartment.php?Sub_Department_ID=".$row['Sub_Department_ID']."&EditSubDepartment=EditSubDepartmentThisForm' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Status']."</a></td>";
            if($temp%20 == 0){
                echo $Title;
            }
        }
    }    
?>
</table>