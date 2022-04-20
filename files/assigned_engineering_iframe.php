<center><table width = 60%>
<?php
include("./includes/connection.php");
$Assigned_engineer = '';

$filter = " AND job_progress = 'not perfomed'";

if (isset($_GET['Assigned_engineer'])) {
    $Assigned_engineer = filter_input(INPUT_GET, 'Assigned_engineer');
}

if (!empty($Assigned_engineer)) {
    $filter .="  AND assigned_engineer='$Assigned_engineer'";
}

?>
<form name="myform" action="" method="POST">
	
        <center> 
	<?php
          $display_table="<table width=100%  class='row'> 
                <tr>
                <th>SN</th>
					<th>Requisition Number</th>
					<th>Requisition Date</th>
					<th>Equipment Name</th>
                                        <th>Reported  by</th>
        			        <th>Requested Department</th>
                    <th>Floor/Room/Place</th>
                    <th>Assigned Staff</th>
                    <th>Section Required</th>
                                        <th width='5%'>Action</th>";
             
             
             
             
             
             
			$display_table.="</tr>";
			$Assigned_engineer = "Assigned_engineer";
		
			$query_data=mysqli_query($conn,"SELECT * FROM tbl_engineering_requisition WHERE requisition_status = 'assigned' $filter ORDER BY requisition_ID DESC");
			
			while($result_query=mysqli_fetch_array($query_data)){
                                                 $employee_name=$result_query['employee_name'];
                                                 $department_name=$result_query['select_dept'];
                                                 $requisition_ID=$result_query['requisition_ID'];
                   $employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employee_name'"))['Employee_Name'];
                   $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$department_name'"))['Sub_Department_Name'];
                $num==0;
                if ($num=='')
                {
                    $num++;
                }
				$display_table.="
                        
				<tr><td style='text-align:center;'>".$num++."</td>
					<td style='text-align:center;'>".$result_query['requisition_ID']."</td>
					<td width='12%'>".$result_query['date_of_requisition']."</td>
					<td>".$result_query['equipment_name']."</td>
                                        <td width='15%'>".$employee."</td>
                                        <td>".$department."</td>
                                        <td>".$result_query['floor']."</td>
                                        <td width='15%'>".$result_query['assigned_engineer']."</td>
                                        <td width='8%'>".$result_query['section_required']."</td>
                                        <td><a href='job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."' class='art-button-green'>PROCESS REQUISITION</a></td>"
                                        ;
                        if(isset($_GET['lForm']))
			if($_GET['lForm']=='saveData'){
                            $display_table.="<td><input name='check_value' type='checkbox' value='".$result_query['requisition_ID']."'></td>";
                        }else{
				$display_table.="<td><a href='requisition_report.php?requision_id={$result_query['requisition_ID']}' class='art-button-green' target='_blank' >Print Preview</a></td>";
			}
				$display_table.="</tr>";
			}
			
			$display_table.="</table>";
			
			echo $display_table;




?>
</form>	
<br>