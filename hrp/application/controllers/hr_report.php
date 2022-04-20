<?php

$this->load->library("excel");

$papersize = 'PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4';
$fontstyle = 'Arial';
$font = 12;

$objPHPExcel = new PHPExcel();
$this->set_properties($objPHPExcel, $papersize, $fontstyle, $font);


// style used in formating border of the cell
$default_border = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb' => '1006A3'));

$set_borders = array(
    'borders' => array(
        'bottom' => $default_border,
        'left' => $default_border,
        'top' => $default_border,
        'right' => $default_border)
);

$number = array(
    'borders' => array(
        'bottom' => $default_border,
        'left' => $default_border,
        'top' => $default_border,
        'right' => $default_border,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);

  $style_shade = array(
	       'borders' => array( 
	               'bottom' => $default_border, 
	               'left' => $default_border,
	               'top' => $default_border,
	               'right' => $default_border, ), 
	         
	       'fill' => array( 
	             'type' => PHPExcel_Style_Fill::FILL_SOLID, 
	             'color' => array(
	                    'rgb'=>'DFD7CF'),
	                   ),
	                   
	    'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),    
	          );

   $style_disco = array(
	       'borders' => array( 
	               'bottom' => $default_border, 
	               'left' => $default_border,
	               'top' => $default_border,
	               'right' => $default_border, ), 
	         
	       'fill' => array( 
	             'type' => PHPExcel_Style_Fill::FILL_SOLID, 
	             'color' => array(
	                    'rgb'=>'FF5D57'),
	                   ),
	                   
	    'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),    
	          );

  
   $objPHPExcel->getActiveSheet()->setCellValue('B3', 'HUMAN RESOURCE DEPARTMENT');
$objPHPExcel->getActiveSheet()->setCellValue('B4', "EMPLOYEE LIST REPORT");
   
   
$sheet = $objPHPExcel->getActiveSheet();
for ($clo = 'A'; $clo < 'ZZ'; $clo++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($clo)->setAutoSize(true);
}

$rows = 6;
$col = "B";

$col = "B";
$rows++;

//heading/title
$sheet->setCellValue($col . $rows, ' S/No ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Employee # ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' FirstName ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' MiddleName ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' LastName ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Sex ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Date Of Birth ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Age ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Education Level ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Position ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Department ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Work Station ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Mobile ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Email ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' End Date ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Salary Grade ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Salary Amount ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$sheet->setCellValue($col . $rows, ' Retirement ');
$sheet->getStyle($col . $rows)->applyFromArray($style_shade);
$col++;
$rows++;
if(count($available) > 0){
    
    $i=1;
    foreach ($available as $key => $value) {
        $col="B";
        $sheet->setCellValue($col . $rows, $i++);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, ' '.$value->EmployeeId);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, $value->FirstName);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, $value->MiddleName);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, $value->LastName);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, $value->Sex);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, $value->dob);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, $value->Age);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$edu_label='';
if($value->EducationLevel > 0){
$edu=$this->HR->educationlevel($value->EducationLevel); $edu_label= $edu[0]->Name;
}
$sheet->setCellValue($col . $rows, ' '.$edu_label);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;

$pos_label='';
if($value->Position > 0){
$position=$this->HR->position($value->Position); $pos_label= $position[0]->Name;
}
$sheet->setCellValue($col . $rows, ' '.$pos_label);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$dep_label='';
if($value->Department > 0){
$depart=$this->HR->department($value->Department); $dep_label= $depart[0]->Name;
}
$sheet->setCellValue($col . $rows, ' '.$dep_label);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;

$work_label='';
if($value->Department > 0){
$station=$this->HR->workstation($value->WorkStation); $work_label= $station[0]->Name;
}
$sheet->setCellValue($col . $rows, ' '.$work_label);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, ' '.$value->Mobile);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, ' '.$value->Email);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, ' '.$value->Enddate);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;

$gra_label='';
if($value->SalaryGrade > 0){
$station=$this->HR->salarygrade($value->SalaryGrade); $gra_label= $station[0]->Name;
}
$sheet->setCellValue($col . $rows, ' '.$gra_label);
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);
$col++;
$sheet->setCellValue($col . $rows, ' '.  number_format($value->Amount));
$sheet->getStyle($col . $rows)->applyFromArray($number);
$col++;
$sheet->setCellValue($col . $rows, ' '.($value->Retere == 1 ? 'Yes':'No'));
$sheet->getStyle($col . $rows)->applyFromArray($set_borders);

$rows++;
    }
}
   
   
   
   
   
   
   
   
   
   
   

//Excel 2003
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="HR_report.xls"');
header('Cache-Control: max-age=0');

$objWriter = IOFactory::createWriter($objPHPExcel, "Excel5");
$objWriter->save('php://output');

exit;
?>