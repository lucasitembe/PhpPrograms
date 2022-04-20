<?php
include("./includes/header.php");
include("./includes/connection.php");
?>
    <a href='./jobcard_menu.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
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
<style>
.row th{
        background: grey;
    }

    .row tr th{
        border: 1px solid #fff;
    }
    .row tr:nth-child(even){
        background-color: #eee;
    }
    .row tr:nth-child(odd){
        background-color: #fff;
        
    }
</style>
<fieldset>  
            <legend align=center><b>LIST OF CREATED JOBCARDS</b></legend>
        <center><table width = 60%>
<form name="myform" action="" method="POST">
	
        <center> 
	<?php
          $display_table="<table width=100% class='row'> 
				<tr> <th>SN</th>
					<th>JobCard Number</th>
					<th>Requested Date</th>
                                        <th>Requested by</th>
                            <th>Requested Department</th>
                            <th>Approval Status</th>
                                        <th width='7%'>Action</th>";
             
             
             
             
             
             
			$display_table.="</tr>";
				
		
			$query_data=mysqli_query($conn,"SELECT * FROM tbl_jobcards WHERE status='pending' ORDER BY jobcard_ID DESC");
			
			$num=0;
			while($result_query=mysqli_fetch_array($query_data)){
                                                 //$jobcard_ID=$result_query
                                                 $employee_name=$result_query['employee_name'];
                                                 $department_name=$result_query['select_dept'];
                                                 $requisition_ID=$result_query['requisition_ID'];
                   $employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employee_name'"))['Employee_Name'];
                   $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$department_name'"))['Sub_Department_Name'];

                   $department_requested = mysqli_fetch_assoc(mysqli_query($conn,"SELECT select_dept from tbl_engineering_requisition where requisition_ID='requisition_ID'"))['select_dept'];
                    $num++;
              
				$display_table.="
                        
				<tr> <td><center>".$num.".</center></td>
					<td style='text-align:center;'>".$result_query['jobcard_ID']."</td>
					<td>".$result_query['part_date']."</td>
					<td>".$result_query['requesting_engineer']."</td>
                                        <td>".$result_query['department_requesting']."</td>
                                        <td>".$result_query['status']."</td>
                                        <td><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$result_query['jobcard_ID']."' class='art-button-green'>PROCESS JOB CARD</a></td>"
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