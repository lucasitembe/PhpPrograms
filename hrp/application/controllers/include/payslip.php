<?php
ob_start();
$this->load->library('pdf');
// set document information
$this->pdf->set_subtitle('EMPLOYEE REPAYMENY STATEMENT');
$this->pdf->start_pdf(FALSE);
$this->pdf->SetSubject('miltone');
$this->pdf->SetKeywords('miltone');

// add a page
$this->pdf->AddPage();
$y = $this->pdf->SetY(0);
//$y = $this->pdf->SetY(5);// $y+15;
$y =$y+15;
$x = $this->pdf->GetX();
$y2 = $this->pdf->getPageHeight()-8;
$x2 = $this->pdf->getPageWidth()-10;


$basic_info = $this->account->employee_basic_info($employee_id);

$this->pdf->SetFont('times', '', 10);

$html='<div></div>
    <table style="width:1000px;" border="1">
    <tr><td><p></p>';

$html.='<table style="width:1000px;">
<tr><td colspan="2"><b>'.  company_info()->Name.'</b></td></tr>    
<tr><td colspan="2"><b>SALARY PAYSLIP</b></td></tr>    
<tr><td style="width:400px;">Employee Number:</td><td>'.$basic_info[0]->EmployeeId.'</td></tr>    
<tr><td>Employee Name:</td><td>'.$basic_info[0]->FirstName .' '.$basic_info[0]->LastName.'</td></tr>    
<tr><td>Salary For:</td><td>'.  month_generator($mwezi).', '.$year.'</td></tr>    
</table><br/>';
$category1=$this->account->salaryitem_process(1);
$earning=0;
$html.='<table cellspacing="0" cellpadding="0"  >
    <tr><td align="left" colspan="2"><b>EARNINGS</b></td></tr>';
foreach ($category1 as $key => $value) {
    $vv=get_payroll_item($basic_info[0]->EmployeeId, $mwezi, $year, $value->id);
    $earning+=$vv;
    $html.='<tr><td align="right" style="width:570px; border-bottom:1px solid black;">'.$value->Name.' : </td><td style="width:400px; border-bottom:1px solid black;" align="right">'. ($vv <> '' ?  number_format($vv,2):0.00).'  &nbsp; </td></tr>';
}
$html.='<tr><td align="right" style="width:570px; border-bottom:1px solid black;"><b>Total Payments :</b> </td><td style="width:400px; border-bottom:1px solid black;" align="right"><b>'.  number_format($earning,2).'  &nbsp; </b></td></tr>';


$category1=$this->account->salaryitem_process(2);
$deduct=0;
$html.='<tr><td align="left" colspan="2"><p></p><b>DEDUCTIONS</b></td></tr>';
foreach($category1 as $key => $value) {
    $vv=get_payroll_item($basic_info[0]->EmployeeId, $mwezi, $year, $value->id);
    $deduct+=$vv;
    $html.='<tr><td align="right" style="width:570px; border-bottom:1px solid black;">'.$value->Name.' : </td><td style="width:400px; border-bottom:1px solid black;" align="right">'. ($vv <> '' ?   number_format($vv,2) : 0.00).'  &nbsp; </td></tr>';
}

$html.='<tr><td align="right" style="width:570px; border-bottom:1px solid black;"><b>Total Deductions :</b> </td><td style="width:400px; border-bottom:1px solid black;" align="right"><b>'.  number_format($deduct,2).'  &nbsp; </b></td></tr>';

$category1=$this->account->salaryitem_process(3);
$contr=0;
$html.='<tr><td align="left" colspan="2"><p></p><b>EMPLOYER CONTRIBUTIONS</b></td></tr>';
foreach ($category1 as $key => $value) {
    $vv=get_payroll_item($basic_info[0]->EmployeeId, $mwezi, $year, $value->id);
    $contr+=$vv;
    $html.='<tr><td align="right" style="width:570px; border-bottom:1px solid black;">'.$value->Name.' : </td><td style="width:400px; border-bottom:1px solid black;" align="right">'.  ($vv <> '' ? number_format($vv,2): 0.00).'  &nbsp; </td></tr>';
}
$html.='<tr><td align="right" style="width:570px; border-bottom:1px solid black;"><b>Total Employer Contributions :</b> </td><td style="width:400px; border-bottom:1px solid black;" align="right"><b>'.  number_format($contr,2).'  &nbsp; </b></td></tr>';

$html.='</table>';
$html.='<p></p><table><tr><td style="width:570px; text-align:center;"><b>NET PAY (BANK) : </b></td><td style="width:400px;" align="right"><b>'.  number_format(($earning-$deduct),2).'  &nbsp; </b></td></tr></table><p></p>';
$html.='</td></tr></table>';
/////////////////////////////////////////////////////////////////////
// print a line using Cell()
//$this->pdf->writeHTML($html);
$this->pdf->writeHTML($html, true, false, true, false, '');
//ob_clean();
//Close and output PDF document
$this->pdf->Output('Payslip.pdf', 'I');
ob_flush();
exit;
?>