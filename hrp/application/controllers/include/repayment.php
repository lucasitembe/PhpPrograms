<?php
ob_start();
$this->load->library('pdf');
// set document information
$this->pdf->set_subtitle('EMPLOYEE REPAYMENY STATEMENT');
$this->pdf->start_pdf();
$this->pdf->SetSubject('miltone');
$this->pdf->SetKeywords('miltone');

// add a page
$this->pdf->AddPage();
$loan_info = $this->account->get_employee_loan(null,null,$id);
$basic_info = $this->account->employee_basic_info(null,$loan_info[0]->Employee);

$this->pdf->SetFont('times', '', 10);
$html='<table>
      <tr><td style="width:500px;">Employee Number :</td><td>'.  $loan_info[0]->Employee.'</td></tr>    
      <tr><td style="width:500px;">Employee Name :</td><td>'. $basic_info[0]->FirstName .' '.$basic_info[0]->LastName.'</td></tr>    
<tr><td style="width:500px;">Loan Number :</td><td>'.  $loan_info[0]->Loan_Number.'</td></tr>        
<tr><td style="width:500px;">Loan Amount :</td><td>'.  number_format($loan_info[0]->Loan_Amount,2).'</td></tr>    
      <tr><td>Installment Amount:</td><td>'.  number_format($loan_info[0]->Installment_Amount,2).'</td></tr>    
      <tr><td>Installment Term:</td><td>'.  ($loan_info[0]->Terms).' Month(s)</td></tr>    
      <tr><td>Start Deduct From:</td><td>'. month_generator($loan_info[0]->Month_D).', '.$loan_info[0]->Year_D.' </td></tr>    
     
</table>';
$html.='<div></div>';
$html.='<table border="1">
    <tr><td style="width:300px;"> &nbsp; S/No </td><td> &nbsp; Month </td><td> &nbsp; Repay Amount </td></tr>';

$i=1;
$total=0;
$paid=$this->account->repayment($loan_info[0]->Employeeid,$loan_info[0]->Employee,$loan_info[0]->Loan_Number);
if(count($paid) > 0){
    foreach ($paid as $key => $value) {
        
    $total+=$value->Amount;
    $html.='<tr><td align="center"> '.$i++.'</td><td> &nbsp; '.  month_generator($value->Month_D).', '.$value->Year_D.' </td><td> &nbsp; '.  number_format($value->Amount,2).'</td></tr>';
}
}
$html.='<tr><td align="right" colspan="2"><b>Grand Total: </b>&nbsp; &nbsp; &nbsp; </td><td> &nbsp; <b>'.  number_format($total,2).'</b></td></tr>';

$html.='</table>';
$html.='<b><br/>Current Dept : '.number_format(($loan_info[0]->Loan_Amount-$total),2).'<b>';

$html.='<p></p><table>
<tr><td>Name of  Accountant:..........................................<br/></td></tr>    
<tr><td>Signature:....................................................<br/></td></tr>    
<tr><td>Date:....................................................</td></tr>    
</table>';
/////////////////////////////////////////////////////////////////////
// print a line using Cell()
//$this->pdf->writeHTML($html);
$this->pdf->writeHTML($html, true, false, true, false, '');
//ob_clean();
//Close and output PDF document
$this->pdf->Output('Employee Loan Statement.pdf', 'I');
ob_flush();
exit;
?>