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
$this->pdf->SetFont('times', '', 10);
$check_payroll=  $this->db->query("SELECT DISTINCT Employee FROM payroll WHERE Month= '$mwezi' AND Year='$year' ORDER BY Employee ASC")->result();
$html='<table style="width:4000px;" cellspacing="0" cellpadding="0" border="1"><tr><td style="width:150px;"> S/No </td><td style="width:350px;"> EMPLOYEE ID </td><td align="center">EARNINGS</td><td align="center">DEDUCTIONS</td><td align="center">EMPLOYER CONTR</td><td>&nbsp;</td></tr>';
$html.='<tr>
    <td></td><td></td>
    <td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(1);
$earning=array();
$e=1;
foreach ($category1 as $key => $value) {
    $title='E'.($e++);
    $earning[$title]=$value->Name;
    $html.='<td align="center" style="width:350px;">'.$title.'</td>';
}
$html.='</tr></table></td>';

$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(2);
$deducting=array();
$de=1;
foreach ($category1 as $key => $value) {
    $title='D'.($de++);
    $earning[$title]=$value->Name;
    $html.='<td align="center" >'.$title.'</td>';
}
$html.='</tr></table></td>';

$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(3);
$emp_contr=array();
$empl=1;
foreach ($category1 as $key => $value) {
    $title='C'.($empl++);
    $earning[$title]=$value->Name;
    $html.='<td align="center">'.$title.'</td>';
}
$html.='</tr></table></td>';
$html.="<td>ddd</td>";
$html.='</tr>';

$pp=1;
foreach ($check_payroll as $k => $v) {
    
    
    $html.='<tr>
    <td align="right">'.$pp++.' &nbsp; </td><td> '.$v->Employee.'</td>
    <td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(1);

foreach ($category1 as $key => $value) {
  $vv=get_payroll_item($v->Employee, $mwezi, $year, $value->id);
    $html.='<td>'.  number_format($vv,2).'</td>';
}
$html.='</tr></table></td>';


$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(2);


foreach ($category1 as $key => $value) {
    $vv=get_payroll_item($v->Employee, $mwezi, $year, $value->id);
    $html.='<td>'.  number_format($vv,2).'</td>';
}
$html.='</tr></table></td>';


$html.='<td><table cellspacing="0" cellpadding="0" border="1"><tr>';
$category1=$this->account->salaryitem_process(3);

foreach ($category1 as $key => $value) {
$vv=get_payroll_item($v->Employee, $mwezi, $year, $value->id);
    $html.='<td>'.  number_format($vv,2).'</td>';
}
$html.='</tr></table></td>';
$html.="<td>ddd</td>";
$html.='</tr>';
    
    
}









$html.='</table>';

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