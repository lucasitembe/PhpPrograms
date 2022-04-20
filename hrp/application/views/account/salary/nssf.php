
<div class="table_list" style="padding-top: 5px;  width: 100%;">
    
    <h2>Configuration for NSSF</h2><hr/>
    <?php echo form_open('account/nssf/'); ?>
    
    <table class="view_data" cellspacing="0" cellpadding="0" style="margin-left: 100px;">
        <th style="width:50px;">S/No</th>
        <th style="width:150px;">EmployeeID</th>
        <th>Employee Name</th>
        <th>Employee NSSF Contribution</th>
        <th>Employer NSSF Contribution</th>
        
</tr>

<?php 
$i=1;
if(count($employeelist) > 0){
    $payee_info = $this->account->salaryitem(5);
 foreach ($employeelist as $key => $value){
     $nssf=  employee_NSSF($value->EmployeeId);
     $mwenyewe=($payee_info[0]->Percent/100)*employee_basic_salary($value->EmployeeId);
     $mwajiri=($payee_info[0]->Percent/100)*employee_basic_salary($value->EmployeeId);
     if(count($nssf) == 1){
         $mwenyewe = $nssf[0]->EmployeeNSSF;
         $mwajiri = $nssf[0]->EmployerNSSF;
     }
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->EmployeeId; ?></td>
    <td><?php echo $value->FirstName .'   '.$value->LastName; ?></td>
    <td><input style="text-align: right;" type="text" value="<?php echo $mwenyewe; ?>" name="employee[<?php echo $value->EmployeeId; ?>]"/></td>
    <td><input style="text-align: right;" type="text" value="<?php echo $mwajiri; ?>" name="employer[<?php echo $value->EmployeeId; ?>]"/></td>
    
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