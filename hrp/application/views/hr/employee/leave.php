<script type="text/javascript">
   
    $(document).ready(function(){
        // Smart Wizard     	
 $("#district").chained("#city");


    });	
    function deletedata(){
   var conf=confirm('Are you sure you want to delete ? ');
  if(conf){
        return true;
    }else{
        return false;
    }
}
</script>
<div>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" style="width: 200px;">
                <?php
                $this->load->view('hr/employee/menu');
                ?>
            </td>
            <td valign="top" style="padding:10px; width: 1000px; ">
              <?php
              $in_data=employee_basic_data($id);
              
              ?>
                <h3 style=" color: green; padding: 0px 0px 0px 10px; margin: 5px;">Employee ID: <?php echo $in_data[0]->EmployeeId; ?>  &nbsp; &nbsp; &nbsp; Employee Name : <?php echo $in_data[0]->FirstName.' '.$in_data[0]->LastName; ?></h3>

                
                
                
<div class="table_list" style="padding-top: 5px; width: 100%;">
    <table class="view_data" cellspacing="0" cellpadding="0" style="width: 100%;">
        <th style="width:50px;">S/No</th>
        <th>Leave Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Days Taken</th>
        <th>Days Remain</th>
        <th>Status</th>
</tr>

<?php 
$i=1;
if(count($employeelist) > 0){
 foreach ($employeelist as $key => $value){ 
     $basic_info = check_employee_number($value->Employee);
     $leavetype= $this->HR->leavetype($value->LeaveType);
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
      <td><?php echo $leavetype[0]->Name;?></td>
    <td><?php echo $value->Fromdate;?></td>
    <td><?php echo $value->Todate;?></td>
    <td><?php echo $value->days;?></td>
    <td><?php echo $value->day_remain;?></td>
    <td><?php echo ($value->is_approved == 0 ? 'Pending Approval':($value->is_approved == 1 ? 'Accepted':'Rejected'));?></td>
     
    
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="10" align="center">No data found</td></tr>';
}
?>
 </table>
 <?php echo $links; ?> 
    
</div>
                
                
                
                
            </td>
        </tr>
    </table>
</div>

