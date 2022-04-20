<link rel="stylesheet" href="table.css" media="screen"> 
<style>
#thead{
    text-align: center;
}
.head{
    background: #dedede;
}
</style>
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['assigned_engineer'])){
        $assigned_engineer = $_GET['assigned_engineer'];
    }else{
        $assigned_engineer = '';
    }
    if(isset($_GET['section_required'])){
        $section_required = $_GET['section_required'];
    }else{
        $section_required = '';
    }

    // if (isset($_GET['assigned_engineer'])) {
    //     $assigned_engineer = str_replace(" ", "%", mysqli_real_escape_string($conn,$_GET['assigned_engineer']));
    // } else {
    //     $assigned_engineer = '';
    // }

    if(isset($_GET['src'])){
	 $src = $_GET['src'];
    }else{
	 $src = '';
    }
  //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
	
    if ($section_required !='All'){
        $filter .= " AND section_required = '$section_required'";
    }else{
        $filter .= "";
    }

    if (!empty($assigned_engineer)){
        $filter .= " AND assigned_engineer LIKE '%" . $assigned_engineer . "%'";
    }
	
	
    
                    $display_table="<table width=100% class='row'> 
                          <tr class='head'>
                              <th>S/N</th>
                              <th>Requisition Number</th>
                              <th style='width: 10%;'>Requisition Date</th>
                              <th>Equipment Name</th>
                            <th style='width: 15%;'>Reported  by</th>
                            <th>Requested Department</th>
                              <th>Floor/Room/Place</th>
                              <th style='width: 15%;'>Assigned Engineer</th>
                              <th>Required Section</th>
                            <th width='7%'>Action</th>";
                       
                       
                       
                       
                       
                       
                      $display_table.="</tr>";
                          
                  
                      $query_data=mysqli_query($conn,"SELECT * FROM tbl_engineering_requisition WHERE  requisition_status = 'assigned' and completed='no' and job_progress='not perfomed' $filter
                                               ORDER BY requisition_ID DESC LIMIT 200");
                      
                      
                      while($result_query=mysqli_fetch_array($query_data)){
                                                           $employee_name=$result_query['employee_name'];
                                                           $department_name=$result_query['select_dept'];
                                                           $requisition_ID=$result_query['requisition_ID'];
                             $employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employee_name'"))['Employee_Name'];
                             $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$department_name'"))['Sub_Department_Name'];
          
                             $completed = mysqli_fetch_assoc(mysqli_query($conn,"select completed where requisition_ID='$requisition_ID'"))['completed'];
                          
                             $emp==0;
                             if($emp==''){
                              $emp++;
                             }
                             if($completed == 'null'){ ?>
          
                             <style>
                             .done{
                                 background: #1b9e49;
                             }
                             </style>
          <?php
                             }
          
                          $display_table.="
                                  
                          <tr class='done'> 
                              <td style='text-align:center;'>".$emp++."</td>
                              <td style='text-align:center;'>".$result_query['requisition_ID']."</td>
                              <td>".$result_query['date_of_requisition']."</td>
                              <td>".$result_query['equipment_name']."</td>
                                                  <td>".$employee."</td>
                                                  <td><a href=''>".$department."</td>
                                                  <td>".$result_query['floor']."</td>
                                                  <td>".$result_query['assigned_engineer']."</td>
                                                  <td>".$result_query['section_required']."</td>
                                                  <td><a href='reasign_process.php?New_Preview_Requisition=True&Requisition_ID=".$requisition_ID."' class='art-button-green'>REASIGN THIS JOB</a></td>"
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
                    
?></table></center>
