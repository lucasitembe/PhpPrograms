<?php
include("./includes/header.php");
include("./includes/connection.php");
?>
<a href='./previous_engineering_works.php' class='art-button-green'>
        PREVIOUS REQUISITIONS
    </a>
<a href="#" id='menu' onclick="section_open_dialogy()" class='art-button-green'>SECTIONS WORK LOAD</a>
<a href='./engineering_works.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
        BACK
    </a>

    <?php

if ($_SESSION['userinfo']['Management_Works'] == 'yes') { 
    echo"<a href='./reassign_job_list.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
        RE-ASSIGN JOB
    </a>";
}else{
    echo "<a href='#' class='art-button-green'>
    RE-ASSIGN JOB
                                </a>";
}
    ?>
<div id='sections'></div>
<script>
    
</script>
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
            <legend align=center><b>ENGINEERING HELPDESK</b></legend>
        <center><table width = 60%>
<form name="myform" action="" method="POST">
	
        <center> 
	<?php
          $display_table="<table width=100% class='row'> 
                <tr>
                    <th>S/N</th>
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
                   $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Department_Name FROM tbl_department WHERE Department_ID='$department_name'"))['Department_Name'];
                
                   $emp==0;
                   if($emp==''){
                    $emp++;
                   }

				$display_table.="
                        
                <tr> 
                    <td style='text-align:center;'>".$emp++."</td>
					<td style='text-align:center;'>".$result_query['requisition_ID']."</td>
					<td>".$result_query['date_of_requisition']."</td>
					<td>".$result_query['equipment_name']."</td>
                                        <td>".$employee."</td>
                                        <td><a href=''>".$department."</td>
                                        <td>".$result_query['floor']."</td>
                                        <td><a href='assign_engineering_requisition.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."' class='art-button-green'>PROCESS REQUISITION</a></td>"
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
<div id="sectionsdiv"></div>
<div id="loadddddd"></div>
<?php
    include("./includes/footer.php");
?>	
		
<!--functions to display date and time for filters-->
<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
        function section_open_dialogy(){
            $.ajax({
                type:'POST',
                url:'section.php',
                data:{sectiondialog:''},
                success:function(responce){
                    $("#sectionsdiv").dialog({
                        title: "WORKING LOAD",
                        width: "60%",
                        height: 400,
                        modal: true
                    });
                    $("#sectionsdiv").html(responce);                    
                }
            })
        }
    </script>
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

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

    <script>
        // $(document).ready(()=>{
        //     $("#menu").click(()=>{
        //         $( "#sections" ).dialog({
        //             autoOpen: false
        //         });
                
        //         $.post( 
        //             "./section.php",{
        //         },(data) =>{
        //             $("#sections").dialog({
        //                 title: "OPEN JOB LIST",
        //                 width: "60%",
        //                 height: 400,
        //                 modal: true
        //             });
        //             $("#sections").html(data);
        //             $("#sections").dialog("open");
        //             alert('--')
        //         });
        //     });
        // })
    </script>
     <script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">