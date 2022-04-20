<?php
include("./includes/header.php");
include("./includes/connection.php");
?>
<a href='processed_engineering_works.php' class='art-button-green'>
            PROCESSED ENGINEERING REQUISITION
        </a>
    <a href='received_engineering_requisition.php?section=Engineering_Works=Engineering_WorksThisPage' class='art-button-green'>
        BACK
    </a>

		<br>
<?php
include("./includes/functions.php");
if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }

    
    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = '';
    }


    filterByDate();
	

?>
<fieldset>  
            <legend align=center><b>PREVIOUS ENGINEERING REQUISITIONS</b></legend>
        <center><table width = 60%>
<form name="myform" action="" method="POST">
	
        <center> 
	<?php
          $display_table="<table width=100%> 
				<tr> 
					<th>Requisition Number</th>
					<th>Requisition Date</th>
					<th>Equipment Name</th>
                                        <th>Reported  by</th>
        			        <th>Requested Department</th>
					<th>Floor/Room/Place</th>
                                        <th width='7%'>Action</th>";
             
             
             
             
             
             
			$display_table.="</tr>";
				
		
			$query_data=mysqli_query($conn,"SELECT * FROM tbl_engineering_requisition WHERE requisition_status = 'pending'
									 ORDER BY requisition_ID DESC");
			
			
			while($result_query=mysqli_fetch_array($query_data)){
                                                 $employee_name=$result_query['employee_name'];
                                                 $department_name=$result_query['select_dept'];
                                                 $requisition_ID=$result_query['requisition_ID'];
                   $employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employee_name'"))['Employee_Name'];
                   $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$department_name'"))['Sub_Department_Name'];
              
				$display_table.="
                        
				<tr> 
					<td style='text-align:center;'><a href='requisition_report.php'>".$result_query['requisition_ID']."</a></td>
					<td><a href='requisition_report.php'>".$result_query['date_of_requisition']."</a></td>
					<td><a href='requisition_report.php'>".$result_query['equipment_name']."</a></td>
                                        <td><a href='requisition_report.php'>".$employee."</a></td>
                                        <td><a href=''>".$department."</a></td>
                                        <td><a href='requisition_report.php'>".$result_query['floor']."</a></td>
                                        <td><a href='control_engineering_requisition.php?New_Process_Requisition=True&Requisition_ID='$requisition_ID' class='art-button-green'>PROCESS REQUISITION</a></td>"
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
<br><br><br><br><br><br><br><br>
<?php
    include("./includes/footer.php");
?>	
		
<!--functions to display date and time for filters-->
<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#date_From').datetimepicker({value:'',step:30});
    $('#date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:30});
    </script>
    <!--End datetimepicker-->