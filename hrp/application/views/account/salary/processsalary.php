<script type="text/javascript">
    function  savedata(){
        con = confirm("Are you sure you want to process this payroll ??");
        if(con){
            return true;
        }else{
            return false;
        }
    }
    
</script>
<?php 
$naration = 'PAYROLL FOR'.' '.strtoupper(month_generator($mwezi)).', '.$year;

$user_info = $this->ion_auth->user()->row();
$user_id = $user_info->id;
$name = $user_info->first_name.' '.$user_info->last_name;

 ?>
<div class="table_list" style="padding-top: 5px; width: 100%;">
    <h2><?php echo $naration; ?> </h2><hr/>
    <?php if(isset ($error_in)){?>
    <div class="message"><?php

     echo $error_in;
     
     

     ?></div>

     <div class="message"><?php
     echo $error_hrp;

     ?></div>

    <?php } ?>
    <div style="text-align: right; margin-right: 40px; color: brown; font-weight: bold;">Total Record : <?php echo count($employeelist); ?></div>
    <?php echo form_open('account/processsalary/'.$mwezi.'/'.$year,'style="width:100%; padding:0px; margin:0px;"'); ?>
    
    <input name="mwezi" value="<?php echo $mwezi; ?>" type="hidden"/>
    <input name="year" value="<?php echo $year; ?>" type="hidden"/>
    <table class="view_data" cellspacing="0" cellpadding="0" style="width: 100%;">
        <th style="width:50px;">S/No</th>
        <th style="width:100px;">EmployeeID</th>
        <th>Employee Name</th>
		
        <?php
        $category1=$this->account->salaryitem_process(1);
        if(count($category1) > 0){ 
            foreach ($category1 as $key => $value) { ?>
        <th><?php echo $value->Name; ?></th>
        <?php }  } ?>
        <?php
        $category1=$this->account->salaryitem_process(2);
        if(count($category1) > 0){ 
            foreach ($category1 as $key => $value) { ?>
        <th><?php echo $value->Name; ?></th>
        <?php }  } ?>
		<th>Net Pay</th>

</tr>

<?php 
$jumla_salary= 0;
$jumla_nssf=0;
$jumla_mshaara=0;
$mkopo_jumla=0;
$payee_jumla=0;
$overtime_jumla =0;
$jumla_bonus =0;
$Work_absence =0;
$i=1;
if(count($employeelist) > 0){
 foreach ($employeelist as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->EmployeeId; ?></td>
    <td><?php echo $value->FirstName .'   '.$value->LastName; ?></td>
       
     <?php
        $category1=$this->account->salaryitem_process(1);
        if(count($category1) > 0){ 
            foreach ($category1 as $k => $val) { 
                if($val->id == 1 || $val->id == 5){ 
                    if($val->id == 1){
                        //basic salary
						
						$basic_salary = $value->Amount;
						$jumla_salary += $basic_salary;
						
						
                    ?>
    <td><?php echo number_format($value->Amount);?></td>
    
    <?php } }else{
        // other items
        $vv=  get_payroll_item($value->EmployeeId, $mwezi, $year, $val->id);
        if($val->id == 3){
            $get_overtime= $this->account->overtime_amount($value->EmployeeId, $mwezi, $year);
            $overtime_detail= $this->account->salaryitem($val->id);    
            $current_amount = ($vv > 0 ? $vv : ($get_overtime*$overtime_detail[0]->Percent));
           $overtime=$current_amount; 
		   $overtime_jumla += $overtime;
        ?>
    <td><input type="text" name="salaryid_<?php echo $val->id; ?>[<?php echo $value->EmployeeId ?>]" value="<?php echo $overtime; ?>" style="width: 80px;"/></td>
    <?php } else{ 
        $bonus = ($vv<>'' ? $vv:0);
		$jumla_bonus += $bonus;
        ?>
    <td><input type="text" name="salaryid_<?php echo $val->id; ?>[<?php echo $value->EmployeeId ?>]" value="<?php echo $bonus; ?>" style="width: 80px;"/></td>
        <?php } } } } ?>
     <?php
        $category1=$this->account->salaryitem_process(2);
        if(count($category1) > 0){ 
            foreach ($category1 as $k => $val) { 
      if($val->id == 8 || $val->id == 9 || $val->id == 5){
          if($val->id == 5){ 
        //payeeee
              $vv1 = get_payroll_item($value->EmployeeId, $mwezi, $year, $val->id);
			$payee_jumla +=$vv1;  
        ?>
    <td><?php echo ($vv1 <> '' ? number_format($vv1) : 0);?></td>
    <?php }else if($val->id ==8){
              // loan recovery
        $vv=  get_payroll_item($value->EmployeeId, $mwezi, $year, $val->id);
		$mkopo_jumla +=$vv;
          ?>
    
    <td><?php echo ($vv <> '' ? number_format($vv) : 0);?></td>
    <?php }else if($val->id == 9){
        // NSSF
        $nssf=employee_NSSF($value->EmployeeId);
        $mwenyewe=0;
        if(count($nssf) == 1){
            $mwenyewe=$nssf[0]->EmployeeNSSF;
			$jumla_nssf += $mwenyewe;
        }
        ?>
        <td><?php echo ($mwenyewe <> '' ? number_format($mwenyewe) :0);?></td>
    <?php    
    }
    }else{
        $vv=  get_payroll_item($value->EmployeeId, $mwezi, $year, $val->id);
        $config_salary = $this->account->check_config_salary_item($value->EmployeeId,$val->id);
        $current_value = '';
        if($vv <> ''){
            $current_value=$vv;
        }else if($config_salary<>''){
            $current_value = $config_salary->EmployeeContribution;
        }
        $current_value  = ($current_value<> '' ? $current_value : 0);
		$Work_absence += $current_value; 
		
        ?>
        <td><input type="text" name="salaryid_<?php echo $val->id; ?>[<?php echo $value->EmployeeId ?>]" value="<?php echo $current_value; ?>" style="width: 80px;"/></td>
        <?php }  } } ?>
		<td> <?php 
		$sum_salary =0;
		$total_salary  = (($basic_salary + $bonus + $overtime) - ($mwenyewe + $vv  + $vv1 + $current_value ));
		
		$jumla_mshaara +=$total_salary ;
		
		
		
		echo number_format($total_salary);
		
		
		?> </td>
        

 </tr>   
 

<?php


}
}else{

 echo '<tr ><td colspan="7" align="center">No data found</td></tr>';
}


?>
<tr ><td colspan="3" align="center">Grand Total</td>
<td align="center"><?php echo number_format($jumla_salary); ?></td> 
<td align="center">0</td> 
<td align="center"><?php echo number_format($overtime_jumla); ?></td>
<td align="center"><?php echo number_format($jumla_bonus); ?></td>
<td align="center"><?php echo number_format($payee_jumla); ?></td>
<td align="center">0</td>
<td align="center"><?php echo number_format($Work_absence); ?></td>
<td align="center"><?php echo number_format($mkopo_jumla); ?></td>
<td align="center"><?php echo number_format($jumla_nssf); ?></td>
<td align="center"><?php echo number_format($jumla_mshaara); ?></td>
</tr>

 </table>
     <div style="text-align: right; padding-top: 5px; margin-right: 20px;" class="subdata3">
        <input type="hidden" name="grandtotal" value="<?php echo $jumla_mshaara;?>">
        <input type="hidden" name="total_nssf" value="<?php echo $jumla_nssf;?>">
        <input type="hidden" name="total_payee" value="<?php echo $payee_jumla;?>">
        <input type="hidden" name="total_loan" value="<?php echo $mkopo_jumla;?>">
        <input type="hidden" name="userid" value="<?php echo $user_id;?>">
        <input type="hidden" name="name" value="<?php echo $name;?>">
        <input type="submit" name="SAVE" id="loan_deliver" value="Process Payroll" onclick="return savedata()"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                             <input type='hidden' name='submittedAddNewSupplierForm' value='true'/> 
                        </div>
 <?php echo form_close(); ?>
</div>
<?php

?>
<?php
