
<div class="table_list" style="padding-top: 5px;  width: 100%;">
    
    <h2>Configuration for <?php echo $item[0]->Name; ?></h2><hr/>
    <?php
    $edit = $item[0]->id;
    ?>
    <?php echo form_open('account/salaryitemconfig/'.$edit);
    if(isset ($error_in)){
    ?>
    <div class="message" align="center"><?php echo $error_in; ?></div>
    <?php } ?>
    <table class="view_data" cellspacing="0" cellpadding="0" style="margin-left: 100px;">
        
       <tr> <th style="width:50px;">S/No</th>
        <th style="width:150px;">EmployeeID</th>
        <th>Employee Name</th>
        <th>Employee Contribution</th>
        
</tr>

<?php 
$i=1;
if(count($employeelist) > 0){
 foreach ($employeelist as $key => $value){
     $nssf=  employee_salaryitem($value->EmployeeId,$edit);
     $mwenyewe=($item[0]->Percent/100)*employee_basic_salary($value->EmployeeId);
     $mwajiri=($item[0]->Percent/100)*employee_basic_salary($value->EmployeeId);
     if(count($nssf) == 1){
         $mwenyewe = $nssf[0]->EmployeeContribution;
         $mwajiri = $nssf[0]->EmployeeContribution;
     }
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->EmployeeId; ?></td>
    <td><?php echo $value->FirstName .'   '.$value->LastName; ?></td>
    <td><input style="text-align: right;" type="text" value="<?php echo $mwenyewe; ?>" name="employee[<?php echo $value->EmployeeId; ?>]"/></td>
      
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="5" align="center">No data found</td></tr>';
}
?>
 
 </table>
    <div style="text-align: center; padding-top: 5px;" class="subdata3">
        <input type="submit" name="SAVE" value="Save Record"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div>
 <?php echo form_close(); ?>
</div>