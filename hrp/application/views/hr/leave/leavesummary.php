
<h2>Leave Summary</h2><hr/>
<div class="table_list" style="padding-top: 5px; width: 100%;">
     <table class="view_data" cellspacing="0" cellpadding="0" style="width: 100%;">
        <th style="width:50px;">S/No</th>
        <th style="width:100px;">EmployeeID</th>
        <th>Employee Name</th>
        <th>Sex</th>
        <th>Leave Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Days Taken</th>
        <th>Leave Balance Days</th>
</tr>

<?php 
$i=1;
if(count($leave) > 0){
 foreach ($leave as $key => $value){ 
     $basic_info=check_employee_number($value->Employee);
     $leavetype= $this->HR->leavetype($value->LeaveType);
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->Employee; ?></td>
    <td><?php echo $basic_info[0]->FirstName.' '.$basic_info[0]->LastName; ?></td>
    <td><?php echo $basic_info[0]->Sex; ?></td>
    <td><?php echo $leavetype[0]->Name;?></td>
    <td><?php echo $value->Fromdate; ?></td>
    <td><?php echo $value->Todate; ?></td>
    <td><?php echo $value->days; ?></td>
    <td><?php echo $value->day_remain; ?></td>
    
    
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="9" align="center">No data found</td></tr>';
}
?>
 </table>

</div>