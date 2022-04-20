<script type="text/javascript">
    function accept(){
        con = confirm("Are you sure you want to Accept this Loan ??");
        if(con){
            return true;
        }else{
            return false;
        }
    }
    function reject(){
        con = confirm("Are you sure you want to Reject this Loan ??");
        if(con){
            return true;
        }else{
            return false;
        }
    }
</script>

<h2>Approve Loans</h2><hr/>
<div class="table_list"  style="padding-top:  20px;">
    <table class="view_data" cellspacing="0" cellpadding="0" style="width: 100%;">
        <th style="width:50px;">S/No</th><th>Employee ID</th> <th> Employee Name</th><th>Loan Number</th><th>Base Amount</th><th>Interest Amount</th><th>Total Loan Amount</th><th>Term (Month)</th><th>Installment Amount</th><th>Action</th>
</tr>

<?php 
$i=1;
if(count($employee_loan) > 0){
 foreach ($employee_loan as $key => $value){ 
     $info =$this->account->employee_basic_info($value->Employeeid,$value->Employee);
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    
    <td><?php echo $value->Employee; ?></td>
    <td><?php  echo $info[0]->FirstName .'   '.$info[0]->LastName; ?></td>
    
    <td><?php echo $value->Loan_Number; ?></td>
    <td align="right"><?php echo number_format($value->Base_Amount,2); ?> &nbsp; </td>
    <td align="right"><?php echo number_format($value->Interest,2); ?>  &nbsp; </td>
    <td align="right"><?php echo number_format($value->Loan_Amount,2); ?>  &nbsp; </td>
    <td align="right"><?php echo $value->Terms; ?>  &nbsp; </td>
    <td align="right"><?php echo number_format($value->Installment_Amount,2); ?>  &nbsp; </td>
    <td><?php echo anchor('account/approveaction/'.$value->id.'/1','Accept','onclick="return accept()"').' &nbsp; |  '.anchor('account/approveaction/'.$value->id.'/2','Reject','onclick="return reject()"') ?></td>
    
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="10" align="center">No data found</td></tr>';
}
?>
 </table>
   
</div>