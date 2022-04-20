<?php
ob_start();
$this->load->library('pdf');
// set document information
$this->pdf->set_subtitle('MONTHLY PAYROLL : ' .strtoupper(month_generator($mwezi)).', '. $year);
$this->pdf->changepageformat_test('L');
$this->pdf->start_pdf();
$this->pdf->SetSubject('miltone');
$this->pdf->SetKeywords('miltone');
// add a page
$this->pdf->AddPage();
$this->pdf->SetFont('times', '', 8);
$check_payroll=  $this->db->query("SELECT DISTINCT Employee FROM payroll WHERE Month= '$mwezi' AND Year='$year' ORDER BY Employee ASC")->result();
$html='<table cellspacing="0" cellpadding="0" border="1">
    <tr><td style="width:100px;"> S/No </td><td style="width:250px;"> EMPLOYEE ID </td>
    <td align="center" style="width:970px;">EARNINGS</td>
    <td align="center" style="width:980px;">DEDUCTIONS</td>
    <td align="center" style="width:250px;">EMPLOYER CONTR</td>
    <td align="center" style="width:300px;"> NET PAY </td></tr>';


$html.='<tr>
    <td></td><td></td>
    <td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(1);
$earning=array();
$e=1;
$sometotal=array();
foreach ($category1 as $key => $value) {
    $title='E'.($e++);
    $sometotal[$value->id]=0;
    $earning[$title]=$value->Name;
    $html.='<td align="center">'.$title.'</td>';
}
$html.='</tr></table></td>';

$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(2);
$deducting=array();
$de=1;
foreach ($category1 as $key => $value) {
    $title='D'.($de++);
    $sometotal[$value->id]=0;
    $deducting[$title]=$value->Name;
    $html.='<td align="center" >'.$title.'</td>';
}
$html.='</tr></table></td>';

$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(3);
$emp_contr=array();
$empl=1;
foreach ($category1 as $key => $value) {
    $title='C'.($empl++);
    $sometotal[$value->id]=0;
    $emp_contr[$title]=$value->Name;
    $html.='<td align="center">'.$title.'</td>';
}
$html.='</tr></table></td>';
$html.="<td></td>";
$html.='</tr>';





$pp=1;
$net_pay=0;
foreach ($check_payroll as $k => $v) {
    
    
    $html.='<tr>
    <td align="right">'.$pp++.' &nbsp; </td><td> '.$v->Employee.'</td>
    <td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(1);
$earning_total=0;
foreach ($category1 as $key => $value) {
  $vv=get_payroll_item($v->Employee, $mwezi, $year, $value->id);
  $sometotal[$value->id]+=$vv;
  $earning_total+=$vv;
    $html.='<td align="right">'. ($vv <> '' ? number_format($vv,1) : 0.0).' &nbsp;</td>';
}
$html.='</tr></table></td>';


$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(2);

$deduction_total=0;
foreach ($category1 as $key => $value) {
   $vv=get_payroll_item($v->Employee, $mwezi, $year, $value->id);
   $sometotal[$value->id]+=$vv;
   $deduction_total+=$vv;
    $html.='<td align="right">'. ($vv <> '' ? number_format($vv,1) : 0.0).' &nbsp;</td>';
}
$html.='</tr></table></td>';


$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(3);
$employer_total=0;
foreach ($category1 as $key => $value) {
$vv=get_payroll_item($v->Employee, $mwezi, $year, $value->id);
$sometotal[$value->id]+=$vv;
$employer_total+=$vv;
    $html.='<td align="right">'. ($vv <> '' ? number_format($vv,1) : 0.0).' &nbsp;</td>';
}
$html.='</tr></table></td>';
$net_pay+=($earning_total-$deduction_total);
$html.='<td align="right" >'. number_format(($earning_total-$deduction_total),1).' &nbsp;</td>';
$html.='</tr>';
    
    
}









$html.='<tr>
    <td align="right" colspan="2"><b>Grand Total:</b> &nbsp; &nbsp;</td>
    <td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(1);

foreach ($category1 as $key => $value) {
    
    $html.='<td align="right"><b>'.number_format($sometotal[$value->id],1).' &nbsp;</b></td>';
}
$html.='</tr></table></td>';

$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(2);

foreach ($category1 as $key => $value) {
 $html.='<td align="right"><b>'.number_format($sometotal[$value->id],1).' &nbsp;</b></td>';
}
$html.='</tr></table></td>';

$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(3);

foreach ($category1 as $key => $value) {
 $html.='<td align="right"><b>'.number_format($sometotal[$value->id],1).' &nbsp;</b></td>';
}
$html.='</tr></table></td>';
$html.='<td align="right"><b>'.  number_format($net_pay,1).' &nbsp;</b></td>';
$html.='</tr>';
















$html.='</table>';
$html.='<p><b>KEY</b></p>';
$html.='<table cellspacing="0" cellpadding="0" ><tr>';
$html.='<td style="width:600px;"><table cellspacing="0" cellpadding="0" >';
foreach ($earning as $key=>$value){
    $html.='<tr><td style="width:70px;">'.$key.' - </td><td>'.$value.'</td></tr>';
}
$html.='</table></td>';

$html.='<td style="width:600px;"><table cellspacing="0" cellpadding="0" >';
foreach ($deducting as $key=>$value){
    $html.='<tr><td style="width:100px;"> '.$key.' - </td><td>'.$value.'</td></tr>';
}
$html.='</table></td>';

$html.='<td style="width:1200px;"><table>';
foreach ($emp_contr as $key=>$value){
    $html.='<tr><td style="width:100px;">'.$key.' - </td><td>'.$value.'</td></tr>';
}
$html.='</table></td>';




$html.='</tr></table>';

/////////////////////////////////////////////////////////////////////
// print a line using Cell()
//$this->pdf->writeHTML($html);
$this->pdf->writeHTML($html, true, false, true, false, '');
//ob_clean();
//Close and output PDF document
$this->pdf->Output('Monthly Report.pdf', 'I');
ob_flush();
exit;
?>