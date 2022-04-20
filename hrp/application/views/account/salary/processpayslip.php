<h2>Salary Slip for <?php echo month_generator($mwezi).', '.$year; ?></h2><hr/>
<div class="table_list"  style="padding-top:  20px;">
    <table class="view_data" cellspacing="0" cellpadding="0" style="width: 100%;">
        <th style="width:50px;">S/No</th><th style="width: 200px;">Employee ID</th> <th> Employee Name</th><th>Work Station</th><th>Department</th><th>Position</th><th>Action</th>
</tr>

<?php 
$i=1;
if(count($payroll) > 0){
 foreach ($payroll as $key => $value){ 
     $info =$this->account->employee_basic_info(null,$value->Employee);
     $hello_info=$this->db->get_where('employee_view',array('EmployeeId'=>$value->Employee))->result();
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    
    <td><?php echo $value->Employee; ?></td>
    <td><?php  echo $info[0]->FirstName .'   '.$info[0]->LastName; ?></td>
     <td><?php if($hello_info[0]->WorkStation){$station=$this->HR->workstation($hello_info[0]->WorkStation); echo $station[0]->Name;} ?></td>
    <td><?php  if($hello_info[0]->Department > 0){ $depart=$this->HR->department($hello_info[0]->Department); echo $depart[0]->Name;} ?></td>
    <td><?php if($hello_info[0]->Position>0){$position=$this->HR->position($hello_info[0]->Position); echo $position[0]->Name;} ?></td>
   
    <td align="center"><?php echo anchor('account/printpayslip/'.$info[0]->id.'/'.$mwezi.'/'.$year,'Print'); ?></td>
  </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="10" align="center">No data found</td></tr>';
}
?>
 </table>
   
</div>